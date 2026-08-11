<?php

namespace App\Jobs;

use App\Contracts\DocumentTextExtractor;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\DocumentStatus;
use App\Services\Documents\Parsing\DocumentParserFactory;
use App\Services\Documents\Validation\ParsedDocumentValidationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProcessApplicationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 900;

    public function __construct(
        public int $application_id
    ) {
    }

    public function handle(
        DocumentTextExtractor $extractor,
        DocumentParserFactory $parserFactory,
        ParsedDocumentValidationService $validator
    ): void {
        try {
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Load application and documents
            |--------------------------------------------------------------------------
            */

            $application = Application::with([
                'documents.document_type',
            ])->findOrFail($this->application_id);


            /*
            |--------------------------------------------------------------------------
            | Load required statuses
            |--------------------------------------------------------------------------
            */

            $applicationProcessingStatus = ApplicationStatus::where(
                'slug',
                'processing'
            )->firstOrFail();

            $applicationEligibleStatus = ApplicationStatus::where(
                'slug',
                'eligible'
            )->firstOrFail();

            $documentProcessingStatus = DocumentStatus::where(
                'slug',
                'processing'
            )->firstOrFail();

            $documentProcessedStatus = DocumentStatus::where(
                'slug',
                'processed'
            )->firstOrFail();

            $documentFailedStatus = DocumentStatus::where(
                'slug',
                'failed'
            )->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | 1. Mark application as processing
            |--------------------------------------------------------------------------
            */

            $application->update([
                'application_status_id' => $applicationProcessingStatus->id,
                'processing_started_at' => now(),
                'processed_at' => null,
                'failure_reason' => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | 2. Mark all application documents as processing
            |--------------------------------------------------------------------------
            */

            $application->documents()->update([
                'document_status_id' => $documentProcessingStatus->id,
                'failure_reason' => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | 3. Extract and parse every document
            |--------------------------------------------------------------------------
            */

            foreach ($application->documents as $document) {
                try {
                    /*
                    |--------------------------------------------------------------------------
                    | Extract machine-readable PDF text
                    |--------------------------------------------------------------------------
                    */

                    $text = $extractor->extract($document);

                    $document->update([
                        'extracted_text' => $text,
                        'extracted_character_count' => mb_strlen($text),
                        'failure_reason' => null,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Refresh model before parsing
                    |--------------------------------------------------------------------------
                    */

                    $document->refresh();

                    /*
                    |--------------------------------------------------------------------------
                    | Resolve correct parser and parse structured data
                    |--------------------------------------------------------------------------
                    */

                    $parser = $parserFactory->resolve($document);

                    $parsedData = $parser->parse($document);

                    logger()->info(
                        "Document {$document->id} parsed successfully.",
                        $parsedData
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Only now mark document as processed
                    |--------------------------------------------------------------------------
                    */

                    $document->update([
                        'document_status_id' => $documentProcessedStatus->id,
                        'processed_at' => now(),
                        'failure_reason' => null,
                    ]);
                } catch (Throwable $exception) {
                    /*
                    |--------------------------------------------------------------------------
                    | Mark only this document as failed
                    |--------------------------------------------------------------------------
                    */

                    $document->update([
                        'document_status_id' => $documentFailedStatus->id,
                        'failure_reason' => $exception->getMessage(),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Stop processing the whole application
                    |--------------------------------------------------------------------------
                    */

                    throw $exception;
                }
            }
            /*
            |--------------------------------------------------------------------------
            | 4. Validate all parsed documents
            |--------------------------------------------------------------------------
            |
            | Validation runs AFTER all documents have been extracted and parsed.
            |
            */

            $application->refresh();

            $validator->validate($application);


            /*
            |--------------------------------------------------------------------------
            | 5. Application successfully processed
            |--------------------------------------------------------------------------
            */

            $application->update([
                'application_status_id' => $applicationEligibleStatus->id,
                'processed_at' => now(),
                'failure_reason' => null,
            ]);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            /*
            |--------------------------------------------------------------------------
            | Persist application failure after rollback
            |--------------------------------------------------------------------------
            */

            $this->failed($exception);

            throw $exception;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Job failure handler
    |--------------------------------------------------------------------------
    */

    public function failed(Throwable $exception): void
    {
        $applicationFailedStatus = ApplicationStatus::query()
            ->where('slug', 'processing_failed')
            ->first();

        if (! $applicationFailedStatus) {
            return;
        }

        Application::query()
            ->whereKey($this->application_id)
            ->update([
                'application_status_id' => $applicationFailedStatus->id,
                'failure_reason' => $exception->getMessage(),
            ]);
    }
}

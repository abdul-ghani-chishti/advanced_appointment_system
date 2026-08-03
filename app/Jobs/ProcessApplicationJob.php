<?php

namespace App\Jobs;

use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\DocumentStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Contracts\DocumentTextExtractor;
use App\Services\Documents\Parsing\DocumentParserFactory;

class ProcessApplicationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public int $tries = 3;
    public int $timeout = 900;
    public function __construct(public int $application_id)
    {
//        dd($application_id);
    }

    /**
     * Execute the job.
     */
    public function handle(DocumentTextExtractor $extractor, DocumentParserFactory $parserFactory): void
    {
        try {
            DB::beginTransaction();
            // Get the application together with its documents
            $application = Application::with('documents.document_type')
                ->findOrFail($this->application_id);

            // Application status: Processing
            $applicationProcessingStatus = ApplicationStatus::where(
                'slug',
                'processing'
            )->firstOrFail();

            // Document status: Processing
            $documentProcessingStatus = DocumentStatus::where(
                'slug',
                'processing'
            )->firstOrFail();

            // Document status: Processed
            $documentProcessedStatus = DocumentStatus::where(
                'slug',
                'processed'
            )->firstOrFail();

            $documentFailedStatus = DocumentStatus::where(
                'slug',
                'failed'
            )->firstOrFail();

            // Application status: Eligible
            $applicationEligibleStatus = ApplicationStatus::where(
                'slug',
                'eligible'
            )->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | 1. Start application processing
            |--------------------------------------------------------------------------
            */

            $application->update([
                'application_status_id' => $applicationProcessingStatus->id,
                'processing_started_at' => now(),
                'failure_reason' => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | 2. Mark all application documents as processing
            |--------------------------------------------------------------------------
            */

            $application->documents()->update([
                'document_status_id' => $documentProcessingStatus->id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | 3. text extraction eventually happen here
            |--------------------------------------------------------------------------
            */

            foreach ($application->documents as $document) {
                try {
                    $text = $extractor->extract($document);
//dd($text);
                    $document->update([
                        'document_status_id' => $documentProcessedStatus->id,
                        'extracted_text' => $text,
                        'extracted_character_count' => mb_strlen($text),
                        'failure_reason' => null,
                    ]);

                    $document->refresh();

                    $parser = $parserFactory->resolve($document);

                    $parsedData = $parser->parse($document);
//dd($parsedData);
                    // Temporary debugging:
                    logger()->info(
                        "Document {$document->id} parsed successfully.",
                        $parsedData
                    );

                    $document->update([
                        'document_status_id' => $documentProcessedStatus->id,
                        'failure_reason' => null,
                    ]);

                } catch (\Throwable $exception) {
                    $document->update([
                        'document_status_id' => $documentFailedStatus->id,
                        'failure_reason' => $exception->getMessage(),
                    ]);

                    throw $exception;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | 4. Application processing finished
            |--------------------------------------------------------------------------
            */

            $application->update([
                'application_status_id' => $applicationEligibleStatus->id,
                'processed_at' => now(),
            ]);
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            $this->failed($exception);
            throw $exception;
        }
    }

    public function failed(Throwable $exception): void
    {
        $failedStatus = ApplicationStatus::query()
            ->where('slug', 'processing_failed')
            ->first();

        if (!$failedStatus) {
            return;
        }

        Application::query()
            ->whereKey($this->application_id)
            ->update([
                'application_status_id' => $failedStatus->id,
                'failure_reason' => $exception->getMessage(),
            ]);
    }
}

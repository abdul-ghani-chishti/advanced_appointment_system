<?php

namespace App\Jobs;

use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\DocumentStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

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
    public function handle(): void
    {
        // Get the application together with its documents
        $application = Application::with('documents')
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
        | 3. OCR will eventually happen here
        |--------------------------------------------------------------------------
        */

        foreach ($application->documents as $document) {

            // Later:
            // $ocrResult = $ocrService->process($document);

            // Temporary behaviour for testing
            $document->update([
                'document_status_id' => $documentProcessedStatus->id,
                'failure_reason' => null,
            ]);
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

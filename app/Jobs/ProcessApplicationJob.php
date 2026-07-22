<?php

namespace App\Jobs;

use App\Models\Application;
use App\Models\ApplicationStatus;
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
        $application = Application::findOrFail($this->application_id);

        $processing_status = ApplicationStatus::query()
        ->where('slug','processing')
        ->firstOrFail();

        $completed_status = ApplicationStatus::query()
            ->where('slug', 'eligible')
            ->firstOrFail();

        $application->update([
            'application_status_id' => $processing_status->id,
            'processing_started_at' => now(),
            'failure_reason' => null,
        ]);

        /*
        * OCR will be added here later.
        *
        * Text extraction will be added here later.
        *
        * Priority calculation will be added here later.
        */

        $application->update([
            'application_status_id' => $completed_status->id,
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

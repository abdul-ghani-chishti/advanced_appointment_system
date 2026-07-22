<?php

namespace App\Console\Commands;

use App\Jobs\ProcessApplicationJob;
use App\Models\Application;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
class ProcessNightlyApplications extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'applications:process-nightly';
    protected $description = 'Dispatch waiting applications for nightly processing';

    public function handle()
    {
        Application::query()
            ->whereHas('status', function ($query) {
                $query->where('slug', 'waiting_processing');
            })
            ->select('id')
            ->orderBy('id')
            ->chunkById(50, function ($applications) {
                foreach ($applications as $application) {
                    ProcessApplicationJob::dispatch($application->id)
                        ->onQueue('applications');
                }
            });

        $this->info('Waiting applications have been dispatched.');

        return self::SUCCESS;
    }
}

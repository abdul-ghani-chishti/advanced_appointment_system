<?php

namespace Database\Seeders;

use App\Models\ApplicationStatus;
use App\Models\AppointmentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Documents Required',
                'slug' => 'documents_required',
                'description' => 'One or more required documents have not been uploaded.',
            ],
            [
                'name' => 'Waiting for Processing',
                'slug' => 'waiting_processing',
                'description' => 'All required documents are uploaded and waiting for processing.',
            ],
            [
                'name' => 'Processing',
                'slug' => 'processing',
                'description' => 'OCR, text extraction, and priority calculation are running.',
            ],
            [
                'name' => 'Processing Failed',
                'slug' => 'processing_failed',
                'description' => 'The application could not be processed successfully.',
            ],
            [
                'name' => 'Eligible',
                'slug' => 'eligible',
                'description' => 'The application has been processed and is eligible for an appointment.',
            ],
            [
                'name' => 'Appointment Assigned',
                'slug' => 'appointment_assigned',
                'description' => 'An appointment has been assigned to the applicant.',
            ],
            [
                'name' => 'Rejected',
                'slug' => 'rejected',
                'description' => 'The application has been rejected.',
            ],
        ];

        foreach ($statuses as $status) {
            ApplicationStatus::updateOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }
    }
}

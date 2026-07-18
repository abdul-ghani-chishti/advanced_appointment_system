<?php

namespace Database\Seeders;

use App\Models\AppointmentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Accepted',
                'slug' => 'accepted',
                'description' => 'The application has been assigned.',
            ],
            [
                'name' => 'Email Pending',
                'slug' => 'email_pending',
                'description' => 'The appointment email has not yet been sent.',
            ],
            [
                'name' => 'Email Sent',
                'slug' => 'email_sent',
                'description' => 'The applicant has been notified by email.',
            ],
            [
                'name' => 'Confirmed',
                'slug' => 'confirmed',
                'description' => 'The applicant has confirmed the appointment.',
            ],
            [
                'name' => 'Completed',
                'slug' => 'completed',
                'description' => 'The appointment has been completed.',
            ],
            [
                'name' => 'Cancelled',
                'slug' => 'cancelled',
                'description' => 'The appointment has been cancelled.',
            ],
            [
                'name' => 'Missed',
                'slug' => 'missed',
                'description' => 'The applicant did not attend the appointment.',
            ],
        ];

        foreach ($statuses as $status) {
            AppointmentStatus::updateOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\DocumentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id' => 1,
                'name' => 'Pending Upload',
                'slug' => 'pending upload',
                'description' => 'Need to upload required documents.',
            ],
            [
                'id' => 2,
                'name' => 'Uploaded',
                'slug' => 'Uploaded',
                'description' => 'Document uploaded successfully and waiting for processing.',
            ],
            [
                'id' => 3,
                'name' => 'Processing',
                'slug' => 'processing',
                'description' => 'OCR and text extraction are currently running.',
            ],
            [
                'id' => 4,
                'name' => 'Processed',
                'slug' => 'processed',
                'description' => 'OCR completed successfully and extracted data is available.',
            ],
            [
                'id' => 5,
                'name' => 'Failed',
                'slug' => 'failed',
                'description' => 'Document processing failed.',
            ],
            [
                'id' => 6,
                'name' => 'Rejected',
                'slug' => 'rejected',
                'description' => 'Document was rejected by an administrator.',
            ],
        ];

        foreach ($statuses as $status) {
            DocumentStatus::updateOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }
    }
}

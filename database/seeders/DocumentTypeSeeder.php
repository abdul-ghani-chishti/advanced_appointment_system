<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Passport',
                'slug' => 'passport',
            ],
            [
                'name' => 'Previous Degree',
                'slug' => 'degree',
            ],
            [
                'name' => 'Transcript',
                'slug' => 'transcript',
            ],
            [
                'name' => 'Admission Letter',
                'slug' => 'admission-letter',
            ],
        ];
        foreach ($types as $type) {
            DocumentType::updateOrCreate(
                ['slug' => $type['slug']],
                [
                    'name' => $type['name'],
                    'is_required' => true,
                    'max_size_kb' => 5120,
                    'allowed_extensions' => 'pdf,jpg,jpeg,png',
                ]
            );
        }
    }
}

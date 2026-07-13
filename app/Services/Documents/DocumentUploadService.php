<?php

namespace App\Services\Documents;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class DocumentUploadService
{
    private const FIELD_TO_SLUG = [
        'passport' => 'passport',
        'degree' => 'degree',
        'transcript' => 'transcript',
        'admission_letter' => 'admission-letter',
    ];

    public function store(User $user, array $files): void
    {
        DB::transaction(function () use ($user, $files) {
            foreach (self::FIELD_TO_SLUG as $field => $slug) {
                $file = $files[$field] ?? null;

                if (! $file instanceof UploadedFile) {
                    continue;
                }

                $this->storeFile($user, $slug, $file);
            }
        });
    }

    private function storeFile(
        User $user,
        string $documentTypeSlug,
        UploadedFile $file
    ): void {
        $documentType = DocumentType::where('slug', $documentTypeSlug)
            ->firstOrFail();

        $existingDocument = Document::where('user_id', $user->id)
            ->where('document_type_id', $documentType->id)
            ->first();

        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            "documents/{$user->id}/{$documentTypeSlug}",
            $storedName,
            'local'
        );

        try {
            Document::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'document_type_id' => $documentType->id,
                ],
                [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => $storedName,
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size_bytes' => $file->getSize(),
                    'failure_reason' => null,
                ]
            );

            if ($existingDocument && $existingDocument->path !== $path)
            {
                Storage::disk('local')->delete($existingDocument->path);
            }
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);

            throw $exception;
        }
    }
}

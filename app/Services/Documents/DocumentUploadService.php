<?php

namespace App\Services\Documents;

use App\Models\Application;
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

                if (!$file instanceof UploadedFile) {
                    continue;
                }

                $this->storeFile($user, $slug, $file);
            }
        });
    }

    private function storeFile(User $user, string $documentTypeSlug, UploadedFile $file): void
    {
        $create_application = null;
        $documentType = DocumentType::where('slug', $documentTypeSlug)
            ->firstOrFail();

        $already_exist = Document::where('user_id', $user->id);

        $existing_document = $already_exist
            ->where('document_type_id', $documentType->id)
            ->first();

        $required_doc_count = DocumentType::all()->count();

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
                    'application_id' => $create_application ? $create_application->id : null,
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => $storedName,
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size_bytes' => $file->getSize(),
                    'document_status_id' => 2, //uploaded
                    'failure_reason' => null,
                ]
            );

            if ($existing_document && $existing_document->path !== $path) {
                Storage::disk('local')->delete($existing_document->path);
            }

            $exist_doc_count = Document::where('user_id', $user->id)->count();
            if ($required_doc_count == $exist_doc_count) {

                $create_application = Application::updateOrCreate(
                    ['user_id' => $user->id, 'application_status_id' => 2]
                );

                Document::where('user_id',$user->id)->update(['application_id'=>$create_application->id]);
            }

        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);

            throw $exception;
        }
    }
}

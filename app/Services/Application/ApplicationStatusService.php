<?php

namespace App\Services\Application;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;

//This service becomes the central place for calculating the user’s current status

class ApplicationStatusService
{
    public function getSummary(User $user): array
    {
        $requiredTypes = DocumentType::query()
            ->where('is_required', true)
            ->orderBy('id')
            ->get(['id', 'name', 'slug']);

        $userDocuments = $user->documents()
            ->with('documentType:id,name,slug') // eager loading concept (everything load at once)
            ->get()
            ->keyBy('document_type_id');

        $documents = $requiredTypes->map(function ($type) use ($userDocuments) {
//            dd($type,$type->id,$userDocuments,$userDocuments->get($type->id),$userDocuments->get(3));
            $document = $userDocuments->get($type->id);

            return [
                'type_id' => $type->id,
                'name' => $type->name,
                'slug' => $type->slug,
                'uploaded' => $document !== null,
                'status' => $document?->document_status_id,
                'filename' => $document?->original_name,
                'uploaded_at' => $document?->created_at?->format('d M Y H:i'),
            ];
        })->values();

        $requiredCount = $requiredTypes->count();
        $uploadedCount = $documents
            ->where('uploaded', true)
            ->count();

        return [
            'status' => $this->determineStatus(
                $documents->all(),
                $requiredCount,
                $uploadedCount,
            ),

            'required_count' => $requiredCount,
            'uploaded_count' => $uploadedCount,
            'all_documents_uploaded' => ($requiredCount > 0 && $requiredCount === $uploadedCount),

            'documents' => $documents,
        ];
    }
    private function determineStatus(array $documents, int $requiredCount, int $uploadedCount): string
    {
        if ($uploadedCount < $requiredCount) {
            return 'pending_upload';
        }

        $statuses = collect($documents)
            ->pluck('status')
            ->filter();
//dd($statuses);
        if ($statuses->contains(0)) {
            return 'failed';
        }

        if ($statuses->contains(1)) {
            return 'processing';
        }

        if ($statuses->isNotEmpty() && $statuses
                ->every(fn (string $status) => $status == 2))
        {
            return 'processed';
        }

        return 'waiting_processing';
    }
}

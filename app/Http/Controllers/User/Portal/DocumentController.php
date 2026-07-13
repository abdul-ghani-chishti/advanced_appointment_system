<?php

namespace App\Http\Controllers\User\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentsRequest;
use App\Models\DocumentType;
use App\Services\Documents\DocumentUploadService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentUploadService $documentUploadService
    ) {
    }

    public function index(): Response //Index page of Document
    {
        $documents = auth()->user()
            ->documents()
            ->with('documentType:id,name,slug')
            ->latest()
            ->get()
            ->map(fn ($document) => [
                'id' => $document->id,
                'type' => $document->documentType->name,
                'slug' => $document->documentType->slug,
                'filename' => $document->original_name,
                'status' => $document->status,
                'uploaded_at' => $document->created_at->format('d M Y H:i'),
            ]);

        return Inertia::render('Portal/Documents/Index', [
            'documents' => $documents,
        ]);
    }

    public function create(): Response //Upload page of document
    {
        $documentTypes = DocumentType::query()
            ->where('is_required', true)
            ->orderBy('id')
            ->get([
                'id',
                'name',
                'slug',
                'max_size_kb',
                'allowed_extensions',
            ]);

        return Inertia::render('Portal/Documents/Upload', [
            'documentTypes' => $documentTypes,
        ]);
    }

    public function store(StoreDocumentsRequest $request): RedirectResponse //store function to process documents from frontend to database
    {
        $this->documentUploadService->store($request->user(), $request->allFiles());

        return back()->with('success', 'Your documents have been uploaded successfully.');
    }
}

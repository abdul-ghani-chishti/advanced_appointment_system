<?php

namespace App\Services\Documents;

use App\Contracts\DocumentTextExtractor;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\Process\Process;
class PdfTextExtractor implements DocumentTextExtractor
{
    public function extract(Document $document): string
    {
        if ($document->mime_type !== 'application/pdf') {
            throw new RuntimeException(
                'Only PDF documents are supported.'
            );
        }

        $absolutePath = Storage::disk('local')->path($document->path);

        if (! is_file($absolutePath)) {
            throw new RuntimeException(
                "Document file was not found: {$absolutePath}"
            );
        }

        $process = new Process([
            config('documents.pdftotext_binary', 'pdftotext'),
            '-layout',
            '-enc',
            'UTF-8',
            $absolutePath,
            '-',
        ]);

        $process->setTimeout(120);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                'PDF text extraction failed: '.$process->getErrorOutput()
            );
        }

        $text = $this->normaliseText($process->getOutput());

        if (! $this->containsEnoughText($text)) {
            throw new RuntimeException(
                'The PDF does not contain enough machine-readable text. '
                .'Please upload a digital PDF instead of a scanned document.'
            );
        }

        return $text;
    }

    private function normaliseText(string $text): string
    {
        $text = str_replace("\0", '', $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\R{3,}/', PHP_EOL.PHP_EOL, $text);

        return trim($text);
    }

    private function containsEnoughText(string $text): bool
    {
        $characters = preg_replace('/\s+/u', '', $text);

        return mb_strlen($characters)
            >= config('documents.minimum_text_characters', 50);
    }
}

<?php

namespace App\Services\Documents\Parsing;

use App\Contracts\DocumentParserInterface;
use App\Models\Document;
use InvalidArgumentException;

class DocumentParserFactory
{
    public function __construct(
        private readonly TranscriptParserService $transcriptParser,
        private readonly DegreeParserService $degreeParser,
        private readonly PassportParserService $passportParser,
        private readonly AdmissionLetterParserService $admissionLetterParser,
    ) {
    }

    public function resolve(Document $document): DocumentParserInterface
    {
        $slug = $document->document_type->slug;

        return match ($slug) {
            'transcript' => $this->transcriptParser,
            'degree' => $this->degreeParser,
            'passport' => $this->passportParser,
            'admission-letter' => $this->admissionLetterParser,

            default => throw new InvalidArgumentException(
                "No parser is configured for document type: {$slug}"
            ),
        };
    }
}

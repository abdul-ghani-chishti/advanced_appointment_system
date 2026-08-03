<?php

namespace App\Services\Documents\Parsing;

use App\Contracts\DocumentParserInterface;
use App\Models\Document;
use App\Models\Transcript;

class TranscriptParserService implements DocumentParserInterface
{
    public function parse(Document $document): array
    {
        $text = $document->extracted_text ?? '';

        $data = [
            'student_name' => $this->extractStudentName($text),
            'university_name' => $this->extractUniversityName($text),
            'degree_name' => $this->extractDegreeName($text),
            'cgpa' => $this->extractCgpa($text),
            'total_credits' => $this->extractTotalCredits($text),
            'graduation_date' => null,
            'parser_version' => 1,
        ];

        Transcript::updateOrCreate(
            ['document_id' => $document->id],
            $data
        );

        return $data;
    }

    private function extractStudentName(string $text): ?string
    {
        if (preg_match(
            '/(?:student\s*name|name)\s*[:\-]\s*([^\r\n]+)/i',
            $text,
            $matches
        )) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractUniversityName(string $text): ?string
    {
        if (preg_match(
            '/(?:university|institution)\s*[:\-]\s*([^\r\n]+)/i',
            $text,
            $matches
        )) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractDegreeName(string $text): ?string
    {
        if (preg_match(
            '/(?:degree|program|programme)\s*[:\-]\s*([^\r\n]+)/i',
            $text,
            $matches
        )) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractCgpa(string $text): ?float
    {
        if (preg_match(
            '/\b(?:CGPA|GPA)\s*[:\-]?\s*(\d+(?:\.\d+)?)/i',
            $text,
            $matches
        )) {
            return (float) $matches[1];
        }

        return null;
    }

    private function extractTotalCredits(string $text): ?int
    {
        if (preg_match(
            '/(?:total\s+credits?|credits?\s+earned)\s*[:\-]?\s*(\d+)/i',
            $text,
            $matches
        )) {
            return (int) $matches[1];
        }

        return null;
    }
}

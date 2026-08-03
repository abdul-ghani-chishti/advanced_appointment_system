<?php

namespace App\Services\Documents\Parsing;

use App\Contracts\DocumentParserInterface;
use App\Models\Degree;
use App\Models\Document;

class DegreeParserService implements DocumentParserInterface
{
    public function parse(Document $document): array
    {
        $text = $document->extracted_text ?? '';

        $data = [
            'student_name' => $this->extractStudentName($text),
            'degree_name' => $this->extractDegreeName($text),
            'university_name' => $this->extractUniversityName($text),
            'graduation_date' => null,
            'parser_version' => 1,
        ];

        Degree::updateOrCreate(
            ['document_id' => $document->id],
            $data
        );

        return $data;
    }

    private function extractStudentName(string $text): ?string
    {
        if (preg_match(
            '/(?:student\s*name|awarded\s+to|name)\s*[:\-]?\s*([^\r\n]+)/i',
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
            '/(?:degree|qualification)\s*[:\-]?\s*([^\r\n]+)/i',
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
            '/(?:university|institution)\s*[:\-]?\s*([^\r\n]+)/i',
            $text,
            $matches
        )) {
            return trim($matches[1]);
        }

        return null;
    }
}

<?php

namespace App\Services\Documents\Parsing;

use App\Contracts\DocumentParserInterface;
use App\Models\AdmissionLetter;
use App\Models\Document;

class AdmissionLetterParserService implements DocumentParserInterface
{
    public function parse(Document $document): array
    {
        $text = $document->extracted_text ?? '';

        $data = [
            'student_name' => $this->extractStudentName($text),
            'university_name' => $this->extractUniversityName($text),
            'program_name' => $this->extractProgramName($text),
            'admission_date' => null,
            'semester' => $this->extractSemester($text),
            'parser_version' => 1,
        ];

        AdmissionLetter::updateOrCreate(
            ['document_id' => $document->id],
            $data
        );

        return $data;
    }

    private function extractStudentName(string $text): ?string
    {
        if (preg_match(
            '/(?:dear|student\s*name)\s*[:\-]?\s*([^\r\n,]+)/i',
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

    private function extractProgramName(string $text): ?string
    {
        if (preg_match(
            '/(?:program|programme|course)\s*[:\-]\s*([^\r\n]+)/i',
            $text,
            $matches
        )) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractSemester(string $text): ?string
    {
        if (preg_match(
            '/\b(?:winter|summer|fall|spring)\s+semester(?:\s+\d{4})?/i',
            $text,
            $matches
        )) {
            return trim($matches[0]);
        }

        return null;
    }
}

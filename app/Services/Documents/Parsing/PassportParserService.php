<?php

namespace App\Services\Documents\Parsing;

use App\Contracts\DocumentParserInterface;
use App\Models\Document;
use App\Models\Passport;

class PassportParserService implements DocumentParserInterface
{
    public function parse(Document $document): array
    {
        $text = $document->extracted_text ?? '';

        $data = [
            'passport_number' => $this->extractPassportNumber($text),
            'full_name' => $this->extractFullName($text),
            'nationality' => $this->extractNationality($text),
            'date_of_birth' => null,
            'issue_date' => null,
            'expiry_date' => null,
            'parser_version' => 1,
        ];

        Passport::updateOrCreate(
            ['document_id' => $document->id],
            $data
        );

        return $data;
    }

    private function extractPassportNumber(string $text): ?string
    {
        if (preg_match(
            '/(?:passport\s*(?:no|number)|document\s*(?:no|number))\s*[:\-]?\s*([A-Z0-9]+)/i',
            $text,
            $matches
        )) {
            return strtoupper(trim($matches[1]));
        }

        return null;
    }

    private function extractFullName(string $text): ?string
    {
        if (preg_match(
            '/(?:full\s*name|name)\s*[:\-]\s*([^\r\n]+)/i',
            $text,
            $matches
        )) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractNationality(string $text): ?string
    {
        if (preg_match(
            '/nationality\s*[:\-]\s*([^\r\n]+)/i',
            $text,
            $matches
        )) {
            return trim($matches[1]);
        }

        return null;
    }
}

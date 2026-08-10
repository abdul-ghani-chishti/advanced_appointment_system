<?php

namespace App\Services\Documents\Parsing;

use App\Contracts\DocumentParserInterface;
use App\Models\Document;
use App\Models\Passport;
use DateTime;

class PassportParserService implements DocumentParserInterface
{
    public function parse(Document $document): array
    {
        $text = $document->extracted_text ?? '';

        $surname = $this->extractSurname($text);
        $givenNames = $this->extractGivenNames($text);

        $data = [
            'passport_number' => $this->extractPassportNumber($text),

            'surname' => $surname,
            'given_names' => $givenNames,

            'full_name' => $this->buildFullName(
                $givenNames,
                $surname
            ),

            'nationality' => $this->extractNationality($text),

            'date_of_birth' => $this->extractDateOfBirth($text),

            'place_of_birth' => $this->extractPlaceOfBirth($text),

            'sex' => $this->extractSex($text),

            'issue_date' => $this->extractIssueDate($text),

            'expiry_date' => $this->extractExpiryDate($text),

            'issuing_authority' => $this->extractIssuingAuthority($text),

            'mrz_line_1' => $this->extractMrzLine1($text),

            'mrz_line_2' => $this->extractMrzLine2($text),
        ];

        Passport::updateOrCreate(
            [
                'document_id' => $document->id,
            ],
            $data
        );

        return $data;
    }

    private function extractPassportNumber(string $text): ?string
    {
        if (
            preg_match(
                '/Passport Number\s*[:\-]?\s*([A-Z0-9]+)/i',
                $text,
                $matches
            )
        ) {
            return strtoupper(trim($matches[1]));
        }

        return null;
    }

    private function extractSurname(string $text): ?string
    {
        if (
            preg_match(
                '/^Surname\s+([^\r\n]+)/mi',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractGivenNames(string $text): ?string
    {
        if (
            preg_match(
                '/^Given Names?\s+([^\r\n]+)/mi',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function buildFullName(
        ?string $givenNames,
        ?string $surname
    ): ?string {
        $parts = array_filter([
            $givenNames,
            $surname,
        ]);

        if (empty($parts)) {
            return null;
        }

        return trim(
            implode(' ', $parts)
        );
    }

    private function extractNationality(string $text): ?string
    {
        if (
            preg_match(
                '/^Nationality\s+([^\r\n]+)/mi',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractDateOfBirth(string $text): ?string
    {
        if (
            preg_match(
                '/Date of Birth\s+([0-9]{1,2}\s+[A-Za-z]+\s+[0-9]{4})/i',
                $text,
                $matches
            )
        ) {
            return $this->convertEnglishDate(
                $matches[1]
            );
        }

        return null;
    }

    private function extractPlaceOfBirth(string $text): ?string
    {
        if (
            preg_match(
                '/^Place of Birth\s+([^\r\n]+)/mi',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractSex(string $text): ?string
    {
        if (
            preg_match(
                '/^Sex\s+([MF])\s*$/mi',
                $text,
                $matches
            )
        ) {
            return strtoupper(
                trim($matches[1])
            );
        }

        return null;
    }

    private function extractIssueDate(string $text): ?string
    {
        if (
            preg_match(
                '/Date of Issue\s+([0-9]{1,2}\s+[A-Za-z]+\s+[0-9]{4})/i',
                $text,
                $matches
            )
        ) {
            return $this->convertEnglishDate(
                $matches[1]
            );
        }

        return null;
    }

    private function extractExpiryDate(string $text): ?string
    {
        if (
            preg_match(
                '/Date of Expiry\s+([0-9]{1,2}\s+[A-Za-z]+\s+[0-9]{4})/i',
                $text,
                $matches
            )
        ) {
            return $this->convertEnglishDate(
                $matches[1]
            );
        }

        return null;
    }

    private function extractIssuingAuthority(string $text): ?string
    {
        if (
            preg_match(
                '/^Issuing Authority\s+([^\r\n]+)/mi',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractMrzLine1(string $text): ?string
    {
        if (
            preg_match(
                '/^(P<[A-Z0-9<]+)$/mi',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractMrzLine2(string $text): ?string
    {
        /*
         * Your extracted text contains:
         *
         * MRZ (Sample) P12345678PAK9803158F3412312<<<<<<<<<<<<04
         */
        if (
            preg_match(
                '/MRZ\s*(?:\(Sample\))?\s+([A-Z0-9<]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function convertEnglishDate(string $value): ?string
    {
        $date = DateTime::createFromFormat(
            'd F Y',
            trim($value)
        );

        if (! $date) {
            return null;
        }

        return $date->format('Y-m-d');
    }
}

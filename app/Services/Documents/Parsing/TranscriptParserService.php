<?php

namespace App\Services\Documents\Parsing;

use App\Contracts\DocumentParserInterface;
use App\Models\Document;
use App\Models\Transcript;
use DateTime;

class TranscriptParserService implements DocumentParserInterface
{
    public function parse(Document $document): array
    {
        $text = $document->extracted_text ?? '';

        $data = [
            'student_last_name' => $this->extractLastName($text),
            'student_first_name' => $this->extractFirstName($text),
            'matriculation_number' => $this->extractMatriculationNumber($text),
            'degree_name' => $this->extractStudyProgram($text),

            'date_of_birth' => $this->extractDateOfBirth($text),
            'place_of_birth' => $this->extractPlaceOfBirth($text),
            'start_of_studies' => $this->extractStartOfStudies($text),

            'total_credits' => $this->extractCurrentCredits($text),
            'required_credits' => $this->extractRequiredCredits($text),

            'cgpa' => $this->extractOverallGrade($text),
        ];

        Transcript::updateOrCreate(
            [
                'document_id' => $document->id,
            ],
            $data
        );

        return $data;
    }

    private function extractLastName(string $text): ?string
    {
        if (
            preg_match(
                '/Name:\s*(.+?)(?=\s+Date of Birth:|\r|\n)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractFirstName(string $text): ?string
    {
        /*
         * Expected normal format:
         *
         * First Name: Abdul Ghani
         *
         * But your current pdftotext output has:
         *
         * First Name: Karachi
         * Matriculation-Nr: Abdul Ghani Place of Birth: 01.10.2025
         *
         * So we first try the normal pattern.
         */

        if (
            preg_match(
                '/First Name:\s*(.+?)(?=\s+Place of Birth:|\r|\n)/i',
                $text,
                $matches
            )
        ) {
            $value = trim($matches[1]);

            /*
             * If value contains multiple words and doesn't look like a date,
             * it may be a valid name.
             *
             * However, in your current IU extraction "Karachi" lands here.
             * So we continue to the fallback if the Matriculation-Nr line
             * clearly contains a human name.
             */
            if (
                ! preg_match(
                    '/Matriculation-Nr:\s*([A-Za-zÀ-ÿ\'\-\s]+?)\s+Place of Birth:/i',
                    $text
                )
            ) {
                return $value;
            }
        }

        /*
         * IU transcript fallback:
         *
         * Matriculation-Nr: Abdul Ghani Place of Birth: 01.10.2025
         *
         * The first-name value was shifted into this position.
         */
        if (
            preg_match(
                '/Matriculation-Nr:\s*([A-Za-zÀ-ÿ\'\-\s]+?)\s+Place of Birth:/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractMatriculationNumber(string $text): ?string
    {
        // Normal layout:
        // Matriculation-Nr: 4242853
        if (
            preg_match(
                '/Matriculation-Nr:\s*(\d{5,12})/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        // Current IU pdftotext layout:
        //
        // Study Program: 25 / 120
        // 4242853 Start of Studies:
        if (
            preg_match(
                '/(?:^|\R)\s*(\d{5,12})\s+Start of Studies:/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        // More tolerant fallback:
        // Search between Matriculation-Nr and Start of Studies
        if (
            preg_match(
                '/Matriculation-Nr:.*?(\d{5,12})\s+Start of Studies:/is',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractStudyProgram(string $text): ?string
    {
        if (
            preg_match(
                '/Study Program:\s*(.+?)(?=\s+Current Credits:|\r|\n)/i',
                $text,
                $matches
            )
        ) {
            $value = trim($matches[1]);

            /*
             * Current extraction wrongly produces:
             *
             * Study Program: 25 / 120
             *
             * We do not want to return credits as the program.
             */
            if (! preg_match('/^\d+\s*\/\s*\d+$/', $value)) {
                return $value;
            }
        }

        /*
         * IU transcript fallback:
         *
         * Master of Science Computer Science Current Credits:
         */
        if (
            preg_match(
                '/^(.+?)\s+Current Credits:/mi',
                $text,
                $matches
            )
        ) {
            $value = trim($matches[1]);

            if (
                stripos($value, 'master') !== false ||
                stripos($value, 'bachelor') !== false ||
                stripos($value, 'science') !== false
            ) {
                return $value;
            }
        }

        return null;
    }

    private function extractDateOfBirth(string $text): ?string
    {
        if (
            preg_match(
                '/Date of Birth:\s*(\d{2}\.\d{2}\.\d{4})/i',
                $text,
                $matches
            )
        ) {
            return $this->convertGermanDate($matches[1]);
        }

        return null;
    }

    private function extractPlaceOfBirth(string $text): ?string
    {
        if (
            preg_match(
                '/Place of Birth:\s*([A-Za-zÀ-ÿ\'\-\s]+?)(?=\s+Start of Studies:|\r|\n)/i',
                $text,
                $matches
            )
        ) {
            $value = trim($matches[1]);

            /*
             * In the broken current extraction this value may actually be a date.
             */
            if (! preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $value)) {
                return $value;
            }
        }

        if (
            preg_match(
                '/First Name:\s*([A-Za-zÀ-ÿ\'\-\s]+?)\s*(?:\r?\n|$)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractStartOfStudies(string $text): ?string
    {
        if (
            preg_match(
                '/Start of Studies:\s*(\d{2}\.\d{2}\.\d{4})/i',
                $text,
                $matches
            )
        ) {
            return $this->convertGermanDate($matches[1]);
        }

        if (
            preg_match(
                '/Place of Birth:\s*(\d{2}\.\d{2}\.\d{4})/i',
                $text,
                $matches
            )
        ) {
            return $this->convertGermanDate($matches[1]);
        }

        return null;
    }

    private function extractCurrentCredits(string $text): ?int
    {
        if (
            preg_match(
                '/Current Credits:\s*(\d+)\s*\/\s*(\d+)/i',
                $text,
                $matches
            )
        ) {
            return (int) $matches[1];
        }

        if (
            preg_match(
                '/Study Program:\s*(\d+)\s*\/\s*(\d+)/i',
                $text,
                $matches
            )
        ) {
            return (int) $matches[1];
        }


        if (
            preg_match(
                '/^Total\s+\d+,\d+\s+(\d+)\s*$/mi',
                $text,
                $matches
            )
        ) {
            return (int) $matches[1];
        }

        return null;
    }

    private function extractRequiredCredits(string $text): ?int
    {
        if (
            preg_match(
                '/Current Credits:\s*(\d+)\s*\/\s*(\d+)/i',
                $text,
                $matches
            )
        ) {
            return (int) $matches[2];
        }


        if (
            preg_match(
                '/Study Program:\s*(\d+)\s*\/\s*(\d+)/i',
                $text,
                $matches
            )
        ) {
            return (int) $matches[2];
        }

        return null;
    }

    private function extractOverallGrade(string $text): ?float
    {

        if (
            preg_match(
                '/^Total\s+(\d+,\d+)\s+\d+\s*$/mi',
                $text,
                $matches
            )
        ) {
            return $this->convertGermanDecimal($matches[1]);
        }


        if (
            preg_match(
                '/(?:overall\s+grade|final\s+grade|average\s+grade)\s*[:\-]?\s*(\d+(?:[,.]\d+)?)/i',
                $text,
                $matches
            )
        ) {
            return $this->convertGermanDecimal($matches[1]);
        }

        return null;
    }

    private function convertGermanDecimal(string $value): float
    {
        return (float) str_replace(',', '.', trim($value));
    }

    private function convertGermanDate(string $value): ?string
    {
        $date = DateTime::createFromFormat(
            'd.m.Y',
            trim($value)
        );

        if (! $date) {
            return null;
        }

        return $date->format('Y-m-d');
    }
}

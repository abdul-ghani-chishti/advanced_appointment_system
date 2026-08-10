<?php

namespace App\Services\Documents\Parsing;

use App\Contracts\DocumentParserInterface;
use App\Models\AdmissionLetter;
use App\Models\Document;
use DateTime;

class AdmissionLetterParserService implements DocumentParserInterface
{
    public function parse(Document $document): array
    {
        $text = $document->extracted_text ?? '';

        $data = [
            'student_name' => $this->extractStudentName($text),
            'student_id' => $this->extractStudentId($text),

            'university_name' => $this->extractUniversityName($text),
            'program_name' => $this->extractProgramName($text),

            'admission_date' => $this->extractAdmissionDate($text),
            'regular_study_period_months' =>
                $this->extractStudyPeriodMonths($text),
            'study_mode' => $this->extractStudyMode($text),

            'language_of_instruction' =>
                $this->extractLanguageOfInstruction($text),

            'latest_enrolment_date' =>
                $this->extractLatestEnrolmentDate($text),

            'letter_issue_date' => $this->extractLetterDate($text),

        ];
//dd($data);
        AdmissionLetter::updateOrCreate(
            [
                'document_id' => $document->id,
            ],
            $data
        );

        return $data;
    }

    private function extractStudentName(string $text): ?string
    {
        /*
         * Strongest English sentence:
         *
         * Herewith we confirm that Abdul Ghani Chishti,
         * born on 12.01.1994 ...
         */
        if (
            preg_match(
                '/Herewith we confirm that\s+(.+?),\s+born on/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        /*
         * Fallback:
         *
         * Dear Abdul Ghani
         */
        if (
            preg_match(
                '/Dear\s+([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractStudentId(string $text): ?string
    {
        if (
            preg_match(
                '/Student ID:\s*(\d+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractUniversityName(string $text): ?string
    {
        /*
         * This exact university appears repeatedly in the document.
         */
        if (
            preg_match(
                '/IU International University of\s+Applied Sciences/i',
                $text
            )
        ) {
            return 'IU International University of Applied Sciences';
        }

        if (
            preg_match(
                '/IU Internationale Hochschule/i',
                $text
            )
        ) {
            return 'IU Internationale Hochschule';
        }

        return null;
    }

    private function extractProgramName(string $text): ?string
    {
        /*
         * English structure:
         *
         * for the following study programme:
         * M.Sc. Computer Science
         */
        if (
            preg_match(
                '/following study programme:\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        /*
         * German fallback:
         *
         * zu folgendem Studiengang zugelassen wurde:
         * M.Sc. Computer Science
         */
        if (
            preg_match(
                '/Studiengang zugelassen wurde:\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        /*
         * Generic fallback:
         */
        if (
            preg_match(
                '/\b(M\.Sc\.\s+[^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractAdmissionDate(string $text): ?string
    {
        /*
         * English:
         *
         * Commencing on: 1st April 2024
         */
        if (
            preg_match(
                '/Commencing on:\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return $this->convertEnglishDate(
                trim($matches[1])
            );
        }

        /*
         * German:
         *
         * Beginn: 1. April 2024
         */
        if (
            preg_match(
                '/Beginn:\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return $this->convertGermanTextDate(
                trim($matches[1])
            );
        }

        /*
         * First-page fallback:
         *
         * M.Sc. Computer Science, 1st April 2024
         */
        if (
            preg_match(
                '/M\.Sc\.\s+Computer Science,\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return $this->convertEnglishDate(
                trim($matches[1])
            );
        }

        return null;
    }

    private function extractStudyPeriodMonths(string $text): ?int
    {
        /*
         * Regular Study Period: 24 months
         */
        if (
            preg_match(
                '/Regular Study Period:\s*(\d+)\s+months/i',
                $text,
                $matches
            )
        ) {
            return (int) $matches[1];
        }

        /*
         * German:
         * Regelstudienzeit: 24 Monate
         */
        if (
            preg_match(
                '/Regelstudienzeit:\s*(\d+)\s+Monate/i',
                $text,
                $matches
            )
        ) {
            return (int) $matches[1];
        }

        return null;
    }

    private function extractStudyMode(string $text): ?string
    {
        /*
         * Regular Study Period:
         * 24 months, full time study mode
         */
        if (
            preg_match(
                '/Regular Study Period:\s*\d+\s+months,\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        /*
         * German:
         * 24 Monate, Vollzeitstudium
         */
        if (
            preg_match(
                '/Regelstudienzeit:\s*\d+\s+Monate,\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractLanguageOfInstruction(string $text): ?string
    {
        /*
         * Language of Instruction:
         * English is the only language of instruction
         */
        if (
            preg_match(
                '/Language of Instruction:\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        /*
         * German:
         * Unterrichtssprache: ausschließlich Englisch
         */
        if (
            preg_match(
                '/Unterrichtssprache:\s*([^\r\n]+)/i',
                $text,
                $matches
            )
        ) {
            return trim($matches[1]);
        }

        return null;
    }

    private function extractLatestEnrolmentDate(string $text): ?string
    {
        /*
         * English:
         *
         * latest date for enrolment (10th of April 2024)
         */
        if (
            preg_match(
                '/latest date for enrolment\s*\(([^)]+)\)/i',
                $text,
                $matches
            )
        ) {
            return $this->convertEnglishDate(
                trim($matches[1])
            );
        }

        /*
         * German:
         *
         * Einschreibungsdatum (10. April 2024)
         */
        if (
            preg_match(
                '/Einschreibungsdatum\s*\(([^)]+)\)/i',
                $text,
                $matches
            )
        ) {
            return $this->convertGermanTextDate(
                trim($matches[1])
            );
        }

        return null;
    }

    private function extractLetterDate(string $text): ?string
    {
        /*
         * Berlin, 23 November 2023
         */
        if (
            preg_match(
                '/Berlin,\s*(\d{1,2}\s+[A-Za-z]+\s+\d{4})/i',
                $text,
                $matches
            )
        ) {
            return $this->convertEnglishDate(
                trim($matches[1])
            );
        }

        return null;
    }

    private function convertGermanDate(string $value): ?string
    {
        $date = DateTime::createFromFormat(
            'd.m.Y',
            trim($value)
        );

        return $date
            ? $date->format('Y-m-d')
            : null;
    }

    private function convertEnglishDate(string $value): ?string
    {
        /*
         * Converts:
         *
         * 1st April 2024
         * 10th of April 2024
         * 23 November 2023
         */

        $value = preg_replace(
            '/(\d+)(st|nd|rd|th)/i',
            '$1',
            $value
        );

        $value = preg_replace(
            '/\bof\b/i',
            '',
            $value
        );

        $value = preg_replace(
            '/\s+/',
            ' ',
            trim($value)
        );

        $timestamp = strtotime($value);

        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
    }

    private function convertGermanTextDate(string $value): ?string
    {
        /*
         * Converts:
         *
         * 1. April 2024
         * 10. April 2024
         */

        $months = [
            'Januar' => 'January',
            'Februar' => 'February',
            'März' => 'March',
            'April' => 'April',
            'Mai' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'August' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Dezember' => 'December',
        ];

        $value = str_replace(
            array_keys($months),
            array_values($months),
            $value
        );

        $value = preg_replace(
            '/(\d+)\./',
            '$1',
            $value
        );

        $timestamp = strtotime($value);

        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
    }
}

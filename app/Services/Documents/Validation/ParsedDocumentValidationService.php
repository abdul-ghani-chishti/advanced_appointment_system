<?php

namespace App\Services\Documents\Validation;

use App\Models\Application;
use RuntimeException;

class ParsedDocumentValidationService
{
    public function validate(Application $application): void
    {
        $application->load([
            'documents.document_type',
            'documents.transcript',
            'documents.passport',
            'documents.admissionLetter',
        ]);

        foreach ($application->documents as $document) {

            $slug = $document->document_type->slug;

            match ($slug) {
                'transcript' => $this->validateTranscript($document),
                'passport' => $this->validatePassport($document),
                'admission-letter' => $this->validateAdmissionLetter($document),

                // degree intentionally ignored for now
                'degree' => null,

                default => null,
            };
        }
    }
    private function validateTranscript($document): void
    {
        $transcript = $document->transcript;

        if (! $transcript) {
            throw new RuntimeException(
                "Transcript parsed data is missing for document {$document->id}."
            );
        }

        if ($transcript->student_first_name === null) {
            throw new RuntimeException(
                'Transcript student first name could not be extracted.'
            );
        }

        if ($transcript->student_last_name === null) {
            throw new RuntimeException(
                'Transcript student last name could not be extracted.'
            );
        }

        if ($transcript->cgpa === null) {
            throw new RuntimeException(
                'Transcript overall grade could not be extracted.'
            );
        }

        if ($transcript->total_credits === null) {
            throw new RuntimeException(
                'Transcript credits could not be extracted.'
            );
        }

        if ($transcript->required_credits === null) {
            throw new RuntimeException(
                'Transcript credits could not be extracted.'
            );
        }
    }
    private function validatePassport($document): void
    {
        $passport = $document->passport;

        if (! $passport) {
            throw new RuntimeException(
                "Passport parsed data is missing for document {$document->id}."
            );
        }

        if ($passport->passport_number === null) {
            throw new RuntimeException(
                'Passport number could not be extracted.'
            );
        }

        if ($passport->full_name === null) {
            throw new RuntimeException(
                'Passport holder name could not be extracted.'
            );
        }

        if ($passport->date_of_birth === null) {
            throw new RuntimeException(
                'Passport date of birth could not be extracted.'
            );
        }

        if ($passport->expiry_date === null) {
            throw new RuntimeException(
                'Passport expiry date could not be extracted.'
            );

        }

        if ($passport->expiry_date->isPast()) {
            throw new RuntimeException(
                'The uploaded passport has expired.'
            );
        }
    }
    private function validateAdmissionLetter($document): void
    {
        $letter = $document->admissionLetter;

        if (! $letter) {
            throw new RuntimeException(
                "Admission letter parsed data is missing for document {$document->id}."
            );
        }

        if ($letter->student_name === null) {
            throw new RuntimeException(
                'Student name could not be extracted from the admission letter.'
            );
        }

        if ($letter->program_name === null) {
            throw new RuntimeException(
                'Program name could not be extracted from the admission letter.'
            );
        }

        if ($letter->admission_date === null) {
            throw new RuntimeException(
                'Admission date could not be extracted from the admission letter.'
            );
        }
    }
}

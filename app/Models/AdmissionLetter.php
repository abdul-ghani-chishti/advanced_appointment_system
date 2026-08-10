<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionLetter extends Model
{
    protected $fillable = [
        'document_id',
        'student_name',
        'student_id',
        'university_name',
        'program_name',
        'admission_date',
        'regular_study_period_months',
        'study_mode',
        'language_of_instruction',
        'latest_enrolment_date',
        'letter_issue_date',
        'parser_version',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

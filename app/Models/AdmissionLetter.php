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
        'regular_study_period_months',
        'study_mode',
        'language_of_instruction',
        'parser_version',
        'admission_date',
        'latest_enrolment_date',
        'letter_issue_date',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'latest_enrolment_date' => 'date',
        'letter_issue_date' => 'date',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

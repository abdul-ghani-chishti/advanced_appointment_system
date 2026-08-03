<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionLetter extends Model
{
    protected $fillable = [
        'document_id',
        'student_name',
        'university_name',
        'program_name',
        'admission_date',
        'semester',
        'parser_version',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

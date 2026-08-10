<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    protected $fillable = [

        'document_id',
        'student_last_name',
        'student_first_name',
        'matriculation_number',
        'university_name',
        'degree_name',
        'cgpa',
        'total_credits',
        'required_credits',
        'start_of_studies',
        'end_of_studies',
        'date_of_birth',
        'place_of_birth',
        'parser_version',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

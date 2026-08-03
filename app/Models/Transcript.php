<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    protected $fillable = [

        'document_id',

        'student_name',

        'university_name',

        'degree_name',

        'cgpa',

        'total_credits',

        'graduation_date',

        'parser_version',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

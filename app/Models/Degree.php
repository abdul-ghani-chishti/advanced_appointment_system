<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    protected $fillable = [
        'document_id',
        'student_name',
        'degree_name',
        'university_name',
        'graduation_date',
        'parser_version',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

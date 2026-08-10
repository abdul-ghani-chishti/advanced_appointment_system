<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    protected $fillable = [
        'document_id',
        'passport_number',
        'surname',
        'given_names',
        'full_name',
        'nationality',
        'date_of_birth',
        'place_of_birth',
        'sex',
        'issue_date',
        'expiry_date',
        'mrz_line_1',
        'mrz_line_2',
        'parser_version',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

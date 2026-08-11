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
        'place_of_birth',
        'sex',
        'mrz_line_1',
        'mrz_line_2',
        'parser_version',
        'date_of_birth',
        'issue_date',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'issue_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

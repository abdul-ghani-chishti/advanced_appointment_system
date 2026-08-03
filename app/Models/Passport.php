<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    protected $fillable = [
        'document_id',
        'passport_number',
        'full_name',
        'nationality',
        'date_of_birth',
        'issue_date',
        'expiry_date',
        'parser_version',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

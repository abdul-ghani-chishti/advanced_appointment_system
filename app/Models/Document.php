<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'application_id',
        'document_type_id',
        'original_name',
        'stored_name',
        'extracted_text',
        'extracted_character_count',
        'path',
        'mime_type',
        'size_bytes',
        'document_status_id',
        'failure_reason',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document_type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(
            DocumentStatus::class,
            'document_status_id'
        );
    }
}

<?php

namespace App\Contracts;

use App\Models\Document;

interface DocumentTextExtractor
{
    public function extract(Document $document): string;
}

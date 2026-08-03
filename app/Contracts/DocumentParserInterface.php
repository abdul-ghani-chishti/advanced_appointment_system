<?php

namespace App\Contracts;

use App\Models\Document;

interface DocumentParserInterface
{
    public function parse(Document $document): array;
}

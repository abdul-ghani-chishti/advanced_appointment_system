<?php

return [
    'pdftotext_binary' => env(
        'PDFTOTEXT_BINARY',
        'pdftotext'
    ),

    'minimum_text_characters' => env(
        'DOCUMENT_MINIMUM_TEXT_CHARACTERS',
        50
    ),
];

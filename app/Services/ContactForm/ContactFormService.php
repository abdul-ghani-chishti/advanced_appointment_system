<?php

namespace App\Services\ContactForm;
use App\Models\ContactForm;

class ContactFormService
{
    static function store(array $data): ContactForm
    {
        return ContactForm::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);
    }
}

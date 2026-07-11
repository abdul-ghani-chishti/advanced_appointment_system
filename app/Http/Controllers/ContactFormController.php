<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactFormRequest;
use App\Services\ContactForm\ContactFormService;
use Illuminate\Http\RedirectResponse;
class ContactFormController extends Controller
{
    public function __construct(private readonly ContactFormService $contactFormService) //dependency injection approach
    {
    }

    public function store_contact_form(StoreContactFormRequest $request) : RedirectResponse
    {
        $this->contactFormService->store(
            $request->validated()
        );
        return back()->with(
            'success',
            'Your message has been sent successfully.'
        );
    }
}

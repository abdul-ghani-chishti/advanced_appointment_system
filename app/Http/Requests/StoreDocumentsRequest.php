<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'passport' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'degree' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'transcript' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'admission_letter' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasFile = collect([
                'passport',
                'degree',
                'transcript',
                'admission_letter',
            ])->contains(
                fn (string $field) => $this->hasFile($field)
            );

            if (! $hasFile) {
                $validator->errors()->add(
                    'documents',
                    'Please select at least one document.'
                );
            }
        });
    }
}

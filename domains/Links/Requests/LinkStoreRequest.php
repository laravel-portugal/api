<?php

namespace Domains\Links\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'link' => ['required', 'string'],
            'description' => ['required', 'string'],
            'author_name' => ['required', 'string'],
            'author_email' => ['required', 'email'],
            'cover_image' => ['required', 'image'],
            'tags' => ['required', 'array'],
            'tags.*.id' => ['required', 'integer', 'exists:tags'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
            'body' => ['required', 'string'],
            'template' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
            'seo_meta' => ['nullable', 'array'],
            'seo_meta.meta_title' => ['nullable', 'string', 'max:255'],
            'seo_meta.meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
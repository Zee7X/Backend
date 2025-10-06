<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search.value' => 'nullable|string|max:100',
            'order.0.column' => 'nullable|integer',
            'order.0.dir' => 'nullable|in:asc,desc',
        ];
    }
}

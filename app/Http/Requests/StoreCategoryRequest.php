<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',
            'slug.unique'   => 'The slug has already been taken.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category') instanceof \App\Models\Category
            ? $this->route('category')->id
            : $this->route('category');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:categories,slug,' . $categoryId,
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',
            'slug.unique'   => 'The slug has already been used by another category.',
        ];
    }
}

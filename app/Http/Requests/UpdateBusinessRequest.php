<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id'  => 'sometimes|exists:categories,id',
            'name'         => 'sometimes|string|max:255',
            'slug'         => ['sometimes', 'string', 'max:255', 'unique:businesses,slug,' . $this->business->id],
            'description'  => 'sometimes|string',
            'logo_path'    => 'sometimes|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'phone_number' => 'sometimes|string|max:20',
            'email'        => 'sometimes|email|max:255',
            'address'      => 'sometimes|string|max:255',
            'city'         => 'sometimes|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique'          => 'The slug has already been taken. Please choose another one.',
            'logo_path.image'      => 'The logo file must be an image.',
            'logo_path.mimes'      => 'The logo must have a jpg, jpeg, or png extension.',
            'logo_path.max'        => 'The logo file size must not exceed 2MB.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules()
    {
        return [
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:businesses,slug',
            'description'   => 'required|string',
            'logo_path'     => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'phone_number'  => 'required|string|max:20',
            'email'         => 'required|email|max:255',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'logo_path.image' => 'The logo file must be an image.',
            'logo_path.mimes' => 'The logo file must have a jpg, jpeg, or png extension.',
            'logo_path.max'   => 'The logo file size must not exceed 2MB.',
        ];
    }
}

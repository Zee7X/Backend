<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email|exists:users,email',
            'token'    => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email is required.',
            'email.email'       => 'Invalid email format.',
            'email.exists'      => 'Email not found in our system.',
            'token.required'    => 'Reset token is required.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 6 characters.',
            'password.confirmed'=> 'Password confirmation does not match.',
        ];
    }
}

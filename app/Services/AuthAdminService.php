<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\InvalidCredentialsException;


class AuthAdminService
{
    public function loginAdmin(string $email, string $password): array
    {
        $user = User::where('email', $email)
            ->where('is_admin', true)
            ->first();

        if (!$user && !$password) {
            throw new InvalidCredentialsException('Invalid email and password');
        }

        if (!$user) {
            throw new InvalidCredentialsException('Invalid email');
        }

        if (!Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException('Invalid password');
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}

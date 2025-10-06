<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserRegistrationException;
use App\Exceptions\InvalidCredentialsException;

class AuthUserService
{
    public function register(array $data): array
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $user->createToken('user-token')->plainTextToken;

            return ['user' => $user, 'token' => $token];
        } catch (\Exception $e) {
            throw new UserRegistrationException('Failed to register user.');
        }
    }

    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user && !$password) {
            throw new InvalidCredentialsException('Invalid email and password');
        }

        if (!$user) {
            throw new InvalidCredentialsException('Invalid email');
        }

        if (!Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException('Invalid password');
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}

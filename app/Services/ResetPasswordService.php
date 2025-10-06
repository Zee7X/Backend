<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Exceptions\ResetPasswordException;

class ResetPasswordService
{
    public function handle(string $email, string $token, string $password): void
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw new ResetPasswordException('User not found.');
        }

        if ($user->is_admin) {
            throw new ResetPasswordException('Admins cannot reset password via API.', 403);
        }

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (! $tokenData) {
            throw new ResetPasswordException('Invalid or expired token.', 400);
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }
}

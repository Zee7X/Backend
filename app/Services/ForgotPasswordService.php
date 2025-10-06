<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Exceptions\ForgotPasswordException;

class ForgotPasswordService
{
    public function handle(string $email): string
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new ForgotPasswordException('User not found.');
        }

        if ($user->is_admin) {
            throw new ForgotPasswordException('Admins cannot reset password via API.', 403);
        }

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => $token,
            'created_at' => now(),
        ]);

        return $token;
    }
}

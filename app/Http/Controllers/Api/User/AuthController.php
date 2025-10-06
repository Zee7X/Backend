<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthUserService;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Services\ForgotPasswordService;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\ResetPasswordService;

class AuthController extends Controller
{
    protected AuthUserService $authUserService;
    protected ForgotPasswordService $forgotPasswordService;
    protected ResetPasswordService $resetPasswordService;

    public function __construct(AuthUserService $authUserService, ForgotPasswordService $forgotPasswordService, ResetPasswordService $resetPasswordService)
    {
        $this->authUserService = $authUserService;
        $this->forgotPasswordService = $forgotPasswordService;
        $this->resetPasswordService = $resetPasswordService;
    }

    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $result = $this->authUserService->register($data);

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $data = $this->authUserService->login(
            $request->email,
            $request->password
        );

        return response()->json($data, 200);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token or already logged out'], 401);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $token = $this->forgotPasswordService->handle($request->validated()['email']);

        return response()->json([
            'message' => 'Password reset token generated successfully.',
            'token'   => $token,
        ], 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $this->resetPasswordService->handle(
            $data['email'],
            $data['token'],
            $data['password']
        );

        return response()->json([
            'message' => 'Password reset successful.',
        ], 200);
    }
}

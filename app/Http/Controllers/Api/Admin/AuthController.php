<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginAdminRequest;
use App\Services\AuthAdminService;

class AuthController extends Controller
{

    protected $authAdminService;

    public function __construct(AuthAdminService $authAdminService)
    {
        $this->authAdminService = $authAdminService;
    }

    public function login(LoginAdminRequest $request)
    {
        $data = $this->authAdminService->loginAdmin(
            $request->email,
            $request->password
        );

        return response()->json($data, 200);
    }
}

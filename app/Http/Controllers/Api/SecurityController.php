<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Services\SecurityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function __construct(private readonly SecurityService $securityService)
    {
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        return $this->securityService->loginApi($request);
    }
    
    public function logout(Request $request): JsonResponse
    {
        return $this->securityService->logoutApi($request);
    }
}

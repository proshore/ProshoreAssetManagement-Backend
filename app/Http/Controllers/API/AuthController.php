<?php

namespace App\Http\Controllers\API;

use App\Services\UserService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $token = $this->userService->authenticateUser($validated);

        return $this->successResponse($token, null, Response::HTTP_OK);
    }
}

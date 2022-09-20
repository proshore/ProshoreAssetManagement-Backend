<?php

namespace App\Http\Controllers\API;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\LoginUserRequest;

class AuthController extends Controller
{
    public function __construct(protected UserService $userService)
    {

    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $validatedUserLogin = $request->validated();

        $token = $this->userService->authenticateUser($validatedUserLogin);

        return $this->successResponse($token, null, Response::HTTP_OK);
    }
}

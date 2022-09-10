<?php

namespace App\Http\Controllers\API;

use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(UserRequest $request): JsonResponse
    {
        $data = $this->userService->authenticateUser($request);

        return response()->json([
            'access_token' => $data->createToken('authToken')->plainTextToken,
            'token_type' => 'Bearer',
        ], Response::HTTP_OK);
    }
}

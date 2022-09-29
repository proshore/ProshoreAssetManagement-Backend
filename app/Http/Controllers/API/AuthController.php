<?php

namespace App\Http\Controllers\API;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected UserService $userService)
    {

    }

    public function register(StoreUserRequest $request): JsonResponse
    {
        $validatedStoreUser = $request->validated();

        $storeUser = $this->userService->storeUser($validatedStoreUser);

        return $this->successResponse(UserResource::make($storeUser), 'User registered successfully', Response::HTTP_OK);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $validatedUserLogin = $request->validated();

        $LoginUser = $this->userService->authenticateUser($validatedUserLogin);

        return $this->successResponse($LoginUser, 'User login successfully', Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        $revokedToken = $this->userService->revokeUserToken();

        return $this->successResponse(
            $revokedToken,
            'Logout Successfully',
            Response::HTTP_OK
        );
    }
}

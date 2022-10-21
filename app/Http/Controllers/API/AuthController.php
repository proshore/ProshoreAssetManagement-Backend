<?php

namespace App\Http\Controllers\API;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\{Response, JsonResponse};
use App\Http\Requests\{ForgotPasswordRequest, LoginUserRequest, StoreUserRequest};

class AuthController extends Controller
{
    public function __construct(protected UserService $userService)
    {

    }

    public function register(StoreUserRequest $request): JsonResponse
    {
        $validatedStoreUser = $request->validated();

        $storeUser = $this->userService->storeUser($validatedStoreUser);

        return $this->successResponse(
            UserResource::make($storeUser),
            'User registered successfully',
            Response::HTTP_OK
        );
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $validatedUserLogin = $request->validated();

        $loginUser = $this->userService->authenticateUser($validatedUserLogin);

        return $this->successResponse(
            [
                'user' => UserResource::make($loginUser['user']),
                'token' => $loginUser['token']
            ],
            'User login successfully',
            Response::HTTP_OK
        );
    }

    public function logout(): JsonResponse
    {
        $this->userService->revokeUserToken();

        return $this->successResponse(
            null,
            'Logout Successfully',
            Response::HTTP_OK
        );
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $validatedForgetPassword = $request->safe()->only(['email']);

        $this->userService->passwordResetLink($validatedForgetPassword);

        return $this->successResponse(
            null,
            'Password reset email sent successfully'
        );
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\{Response, JsonResponse};
use App\Http\Requests\{ForgotPasswordRequest, LoginUserRequest, ResetPasswordRequest, StoreUserRequest};
use Illuminate\Support\Facades\Password;

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
        );
    }

    public function logout(): JsonResponse
    {
        $this->userService->revokeUserToken();

        return $this->successResponse(
            null,
            'Logout Successfully',
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

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $validatedResetPassword = $request->safe()->only(['email', 'password', 'password_confirmation', 'token']);

        $updateUserPassword = $this->userService->updatePassword($validatedResetPassword);

        if ($updateUserPassword !== Password::INVALID_TOKEN) {
            return $this->successResponse(
                null,
                'Password reset successfully'
            );
        }

        return $this->errorResponse(
            'Token expired or not valid',
            Response::HTTP_BAD_REQUEST
        );
    }
}

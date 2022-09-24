<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class UserService
{
    public function authenticateUser(array $validatedUserLogin): string
    {
        if (!Auth::attempt($validatedUserLogin)) {
            throw new UnauthorizedException("Invalid login credentials");
        }

        $user = Auth::user();

        return $user->createToken('authToken')->plainTextToken;
    }

    public function revokeUserToken(): void
    {
        Auth::user()->currentAccessToken()->delete();
    }
}

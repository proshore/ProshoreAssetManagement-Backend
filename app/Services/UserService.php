<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class UserService
{
    public function authenticateUser($validated)
    {
        if (!Auth::attempt($validated)) {
            throw new UnauthorizedException("Invalid login credentials" );
        }

        $data = User::where('email', $validated['email'])->firstOrFail();

        return $data->createToken('authToken')->plainTextToken;
    }
}

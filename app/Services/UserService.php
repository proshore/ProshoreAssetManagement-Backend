<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class UserService
{
    public function authenticateUser($validated)
    {
        if (!Auth::attempt($validated)) {
            throw new Exception("Invalid login credentials", Response::HTTP_UNAUTHORIZED);
        }

        $data = User::where('email', $validated['email'])->firstOrFail();

        return $data->createToken('authToken')->plainTextToken;
    }
}

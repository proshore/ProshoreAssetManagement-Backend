<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class UserService
{
    public function authenticateUser(UserRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            throw new UnauthorizedException("Invalid login credentials");
        }

        return User::where('email', $request['email'])->firstOrFail();
    }
}

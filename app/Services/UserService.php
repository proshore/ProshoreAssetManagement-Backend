<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function authenticateUser(UserRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            // return response()->json([
            //     'message' => 'Invalid login credentials'
            // ], Response::HTTP_UNAUTHORIZED);
            throw new Exception("Invalid login credentials");
        }

        return User::where('email', $request['email'])->firstOrFail();
    }
}

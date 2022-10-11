<?php

namespace App\Services;

use Firebase\JWT\{JWT, Key};
use App\Models\{User, Invite, UserRole};
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class UserService
{
    public function authenticateUser(array $validatedUserLogin): array
    {
        if (!Auth::attempt($validatedUserLogin)) {
            throw new UnauthorizedException("Invalid login credentials");
        }

        $user = Auth::user();

        $token = $user->createToken('authToken')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function revokeUserToken(): void
    {
        Auth::user()->currentAccessToken()->delete();
    }

    public function storeUser(array $validatedStoreUser): User
    {
        $decodedToken = JWT::decode(
            $validatedStoreUser['token'],
            new Key(config('jwt.secret_key'), config('jwt.algorithm'))
        );

        $decodedTokenArray = (array) $decodedToken;

        $invitedUser = Invite::where(
            [
                ['email', $decodedTokenArray['email']],
                ['name', $decodedTokenArray['name']]
            ]
        )->first();

        $createdUser = User::create($validatedStoreUser);

        UserRole::create([
            'user_id' => $createdUser['id'],
            'role_id' => $invitedUser['role_id'],
        ]);

        $invitedUser->delete();

        return $createdUser;
    }
}

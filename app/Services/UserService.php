<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Firebase\JWT\{JWT, Key};
use App\Models\{User, Invite};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

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

        $createdUser->roles()->attach($invitedUser->role);

        $invitedUser->status = 'inactive';
        $invitedUser->save();

        $invitedUser->delete();

        return $createdUser;
    }

    public function passwordResetLink(array $validatedForgetPassword): string
    {
        return Password::sendResetLink($validatedForgetPassword);
    }

    public function updatePassword(array $validatedResetPassword): mixed
    {
        $user = User::where('email', $validatedResetPassword['email'])->first();

        if (Hash::check($validatedResetPassword['password'], $user->password)) {
            return throw new BadRequestException('cannot use old password as a password reset');
        }

        return Password::reset($validatedResetPassword, function ($user, $password) {
            $user->forceFill(
                ['password' => $password]
            )->setRememberToken(Str::random(60));

            $user->tokens()->delete();

            $user->save();
        });
    }
}

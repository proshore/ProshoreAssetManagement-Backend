<?php

namespace App\Services;

use Illuminate\Support\Str;
use Firebase\JWT\{JWT, Key};
use App\Models\{User, Invite};
use App\Constants\Invite as InviteConstant;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\Facades\{Auth, Hash, Password};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    public function decodeToken($token, $resource): array
    {
        $decodedToken = JWT::decode(
            $token,
            $resource
        );

        return (array) $decodedToken;
    }

    public function storeUser(array $validatedStoreUser): User
    {
        $decodedTokenArray = $this->decodeToken(
            $validatedStoreUser['token'],
            new Key(config('jwt.secret_key'), config('jwt.algorithm'))
        );

        if ($decodedTokenArray['email'] !== $validatedStoreUser['email'] || $decodedTokenArray['name'] !== $validatedStoreUser['name']) {
            return throw new BadRequestException("The given user details aren't valid");
        }

        $invitedUser = Invite::where(
            [
                ['email', $decodedTokenArray['email']],
                ['name', $decodedTokenArray['name']]
            ]
        )->first();

        $createdUser = User::create($validatedStoreUser);

        $createdUser->roles()->attach($invitedUser->role);

        $invitedUser->status = InviteConstant::EXPIRED;
        $invitedUser->save();

        return $createdUser;
    }

    public function passwordResetLink(array $validatedForgetPassword): string
    {
        return Password::sendResetLink($validatedForgetPassword);
    }

    public function updatePassword(array $validatedResetPassword): mixed
    {
        $user = User::where('email', $validatedResetPassword['email'])->first();

        if (!$user) {
            throw new NotFoundHttpException("User with this email cannot be found");
        }

        if (Hash::check($validatedResetPassword['password'], $user->password)) {
            return throw new BadRequestException('Cannot use old password as a password reset');
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

<?php

namespace App\Services;

use Firebase\JWT\JWT;
use App\Models\Invite;
use App\Mail\InviteMail;
use Illuminate\Support\Facades\Mail;
use App\Constants\Invite as InviteConstant;
use App\Http\Resources\UserResource;
use App\Http\Resources\InviteResource;

class InviteService
{
    public function generateToken(array $user): string
    {
        $issuedAt = time();

        $expirationTime = time() + InviteConstant::DEFAULT_EXIPIRE_AT;

        $payload = array(
            'name' => $user['name'],
            'email' => $user['email'],
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        );

        $token = JWT::encode(
            $payload,
            config('jwt.secret_key'),
            config('jwt.algorithm')
        );

        return $token;
    }

    public function processInvite(array $validatedInviteUser)
    {
        $token = $this->generateToken([
            'name' => $validatedInviteUser['name'],
            'email' => $validatedInviteUser['email']
        ]);

        $url = config('frontend.url') . '/register/' . $token . '?email=' . $validatedInviteUser['email'] . '&name=' . urlencode($validatedInviteUser['name']);

        $user = Invite::create([
            'name' => $validatedInviteUser['name'],
            'email' => $validatedInviteUser['email'],
            'role_id' => $validatedInviteUser['role_id'],
        ]);

        Mail::to($validatedInviteUser['email'])
            ->send(
                new InviteMail(
                    $url,
                    $validatedInviteUser['name']
                )
            );

        return [
            'user' => InviteResource::make($user),
            'token' => $token
        ];
    }
}

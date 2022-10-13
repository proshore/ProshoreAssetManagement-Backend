<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use App\Models\Invite;
use App\Mail\InviteMail;
use Illuminate\Support\Facades\Mail;
use App\Constants\Invite as InviteConstant;
use App\Models\Role;

class InviteService
{
    public function generateUrl($token, array $user): String
    {
        return config('frontend.url') . '/register/' . $token . '?email=' . $user['email'] . '&name=' . urlencode($user['name']);
    }

    public function generateToken(array $user): string
    {
        $issuedAt = time();

        $expirationTime = $issuedAt . InviteConstant::DEFAULT_EXIPIRE_AT;

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

        $url = $this->generateUrl(
            $token,
            $validatedInviteUser
        );

        $user = Invite::create($validatedInviteUser);

        Mail::to($validatedInviteUser['email'])
            ->send(
                new InviteMail(
                    $url,
                    $validatedInviteUser['name']
                )
            );

        return [
            'user' => $user,
            'token' => $token
        ];
    }


}

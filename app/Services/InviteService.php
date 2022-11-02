<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use App\Models\Invite;
use App\Mail\InviteMail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Constants\Invite as InviteConstant;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class InviteService
{
    public function generateUrl($token, array $user): String
    {
        return config('frontend.url') . '/register/' . $token . '?email=' . $user['email'] . '&name=' . urlencode($user['name']);
    }

    public function generateToken(array $user): string
    {
        $issuedAt = time();

        $expirationTime = $issuedAt + InviteConstant::DEFAULT_EXIPIRE_AT;

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

    public function sendMail($email, $mailable): void
    {
        Mail::to($email)
            ->send($mailable);
    }

    private function checkUserAlreadyCreated($email): bool
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        return true;
    }

    private function getInvitedUser($email, $includeTrash = false): mixed
    {
        if ($includeTrash) {
            $user = Invite::withTrashed()->where('email', $email)->first();
        } else {
            $user = Invite::where('email', $email)->first();
        }

        return $user;
    }

    public function processInvite(array $validatedInviteUser): array
    {
        $token = $this->generateToken($validatedInviteUser);

        $url = $this->generateUrl(
            $token,
            $validatedInviteUser
        );

        $previousInvitedUser = $this->getInvitedUser($validatedInviteUser['email'], true);

        $registeredUser = $this->checkUserAlreadyCreated($validatedInviteUser['email']);

        if ($registeredUser) {
            throw new BadRequestException('User already created');
        }

        $validatedInviteUserWithStatus = [
            ...$validatedInviteUser,
            'status' => InviteConstant::PENDING
        ];

        if ($previousInvitedUser) {
            if ($previousInvitedUser->status === InviteConstant::PENDING) {
                throw new  BadRequestException('User already invited');
            }

            $restoreInviteUser = $previousInvitedUser->restore();

            $restoreInviteUser = $this->getInvitedUser($validatedInviteUser['email']);

            $restoreInviteUser->status = InviteConstant::PENDING;
            $restoreInviteUser->save();
        } else {
            $createInviteUser = Invite::create($validatedInviteUserWithStatus);
        }

        $this->sendMail(
        $validatedInviteUser['email'],
        new InviteMail(
                $url,
                $validatedInviteUser['name']
            )
        );

        return [
            'user' => $previousInvitedUser ? $restoreInviteUser : $createInviteUser,
            'token' => $token
        ];
    }

    public function reInvite($id): array
    {
        $user = Invite::where('id', $id)->first();

        if (!$user) {
            throw new NotFoundHttpException("User with id '{$id}' cannot be found");
        }

        $userData = [
            'name' => $user->name,
            'email' => $user->email
        ];

        $token = $this->generateToken($userData);

        $url = $this->generateUrl($token, $userData);

        $this->sendMail(
            $userData['email'],
            new InviteMail(
                $url,
                $userData['name']
            )
        );

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function invitedUsers(): Collection
    {
        return Invite::latest()->get();
    }

    public function revokeInvite($id): void
    {
        $user = Invite::where('id', $id);

        if (!$user->first()) {
            throw new NotFoundHttpException("User with id '{$id}' cannot be found");
        }

        if ($user->where('status', InviteConstant::EXPIRED)->first()) {
            throw new BadRequestException("User cannot be revoked");
        }

        $retriveUser = $user->first();

        $retriveUser->status = InviteConstant::EXPIRED;
        $retriveUser->save();

        $retriveUser->delete();
    }
}

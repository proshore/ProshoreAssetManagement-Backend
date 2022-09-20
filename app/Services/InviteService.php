<?php

namespace App\Services;

use App\Models\Invite;
use App\Mail\InviteMail;
use Illuminate\Support\Facades\Mail;

class InviteService
{
    public function generateToken(): string
    {
        return bcrypt(now()->addDays(5)->toDateTimeLocalString());
    }

    public function processInvite(array $validatedInviteUser): mixed
    {
        $token = $this->generateToken();

        $url = config('frontend.url') . '/register/' . $token . '?email=' . $validatedInviteUser['email'] . '&name=' . urlencode($validatedInviteUser['name']);

        $user = Invite::create([
            'name' => $validatedInviteUser['name'],
            'email' => $validatedInviteUser['email'],
            'role_id' => $validatedInviteUser['role_id'],
            'token' => $token
        ]);

        Mail::to($validatedInviteUser['email'])->send(new InviteMail($url, $validatedInviteUser['name']));

        return $user;
    }
}

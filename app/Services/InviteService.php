<?php

namespace App\Services;

use Exception;
use App\Models\Invite;
use App\Mail\InviteMail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class InviteService
{
    public function generateToken(): string
    {
        return bcrypt(now()->addDays(5)->toDateTimeLocalString());
    }

    public function processInvite(array $validated): bool
    {
        $token = $this->generateToken();

        $url = config('frontend.url') . '/register/' . $token . '?email=' . $validated['email'] . '&name=' . urlencode($validated['name']);

        if (Invite::where('email', $validated['email'])->first()) {
            throw new Exception('Email already taken.', Response::HTTP_BAD_REQUEST);
        }

        Invite::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'token' => $token
        ]);

        Mail::to($validated['email'])->send(new InviteMail($url, $validated['name']));
        return true;
    }
}

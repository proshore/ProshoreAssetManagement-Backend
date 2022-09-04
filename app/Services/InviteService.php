<?php

namespace App\Services;

use App\Mail\InviteCreated;
use App\Mail\Reinvite;
use App\Models\InviteToken;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class InviteService
{
public function generateToken():string{
    $random=Str::random(60);
    $time=Carbon::now();
    return $random.$time->toDateTimeLocalString();
}
    public function invite($name, $email, $role_id, $vendor_employee_id): bool
    {
        $token = $this->generateToken();
        $url=config('=.url') . '/register' .$token  . '?email=' .$email .'?name=' .$name . '?role_id=' .$role_id;

        $employeeVendor = InviteToken::create([
            'name' => $name,
            'email' => $email,
            'role_id' => $role_id,
            'token' => $token,
            'tokenExpires' => Carbon::now()->addDays(5),
            'invitedUserId' => $vendor_employee_id
        ]);
        if (!$employeeVendor) return false;
        // send an email notifying that you are invited
        Mail::to($email)->send(new InviteCreated($url));
        return true;
    }

    //show the list of invited User
    public static function invitedList(): Collection
    {
        return InviteToken::all();
    }

    //Resending the email
    public function resendInvite($email): bool
    {
        $employeeVendor = InviteToken::where('email', $email)->first();
        if (!$employeeVendor) return false;

        //if user exists then generate new token and email
        $token = $this->generateToken();
        $url = config('frontend.url') . '/register/' . $token . '?email=' . $email;
        $employeeVendor->forceFill([
            'token' => $token
        ]);
        $employeeVendor->save();
        Mail::to($email)->send(new Reinvite($url));

        return true;
    }


    public static function revokeInvite($request): bool
    {
        $employeeVendor = InviteToken::where('id', $request->id)->firstOrFail();
        if (!$employeeVendor) return false;
        //if users exists then delete their invite
        $employeeVendor->delete();
        return true;
    }
}

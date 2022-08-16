<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'email',
        'address',
        'social_media_link',
        'phone_number',
        'role',
        'status',
    ];
}

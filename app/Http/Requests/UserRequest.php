<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {  
        if ($request->is('api/users/register')) {
            return [
                    'name'=> ['required', 'string', 'max:255'],
                    'email'=> ['required', 'string', 'email', 'unique:users', 'max:255'],
                    'password' => ['required', Password::min(8)
                                                ->mixedCase()
                                                ->numbers()
                    ]
            ];
        }

        return [
                'email'=> ['required', 'string', 'email', 'unique:users', 'max:255'],
                'password' => ['required', Password::min(8)
                                            ->mixedCase()
                                            ->numbers()
                ]
        ];
    }
}

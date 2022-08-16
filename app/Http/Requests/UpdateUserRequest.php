<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'full_name'=>'nullable|string|max:255',
            'email'=>'nullable|string|email|max:255|unique:register_users',
            'phone_number'=>'nullable|min:10|string',
            'role'=>'required',
            'status'=>'required',
        ];
    }
}

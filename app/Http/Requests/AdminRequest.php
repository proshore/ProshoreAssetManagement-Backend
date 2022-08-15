<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

class AdminRequest extends FormRequest
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
        if($request->is('api/login-admin')){
            $rules =[
                'email'=>'required|email',
                'password'=>'required|min:6|regex:[@]'
            ];
        }
        else{
            $rules=[
                'name'=>'required|string|max:255',
                'email'=>'required|string|email|max:255|unique:admins',
                'password'=>'required|min:6|regex:[@]'

            ];
        }
        return $rules;
    }
}

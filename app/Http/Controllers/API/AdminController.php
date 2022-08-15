<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    public function registerAdmin(AdminRequest $request)
    {
        //        dd($request->all());
        $admin_data=$request->validated();
//        dd($admin_data);
        $adminDetails=Admin::create($admin_data);
        return response()->json([
            'message'=>'admin registred successfully',
            'admin'=> new AdminResource($adminDetails)
        ],Response::HTTP_CREATED);
    }
    public function loginAdmin(AdminRequest $request){
        $adminDetails=Admin::where('email',$request->email)->first();
//        dd($adminDetails);
        if (!$adminDetails){
            return response()->json([
                'message'=>'admin Not Found',
            ],Response::HTTP_NOT_FOUND);
        }

       if(!password_verify($request->password,$adminDetails->password))
        {
           return response()->json([
               'message'=>'Invalid Password',
           ],Response::HTTP_UNAUTHORIZED);
        }
       $token=$adminDetails->createToken('auth_token');
       return response()->json([

           'admins'=> new AdminResource($adminDetails),
           'access_token'=>$token->plainTextToken,
           'token_type'=>'Bearer',
       ],Response::HTTP_OK);

//       $token=$adminDetails->createToken('Laravel password Grant Client')->accessToken;
    }
}

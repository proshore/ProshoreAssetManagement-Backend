<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserRegistrationResource;
use App\Models\RegisterUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserRegistrationResource::collection(RegisterUser::all());
        return response()->json([
            'message'=>'User Data Retrived Successfully',
        ],Response::HTTP_OK); //data retrived

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterUserRequest $request)
    {
        $user_data= $request->validated();
              $user=RegisterUser::create($user_data);
        return response()->json([
            'message'=>'User Registred successfully',
            'register_users'=> new UserRegistrationResource($user)
        ],Response::HTTP_CREATED); //data stored in database
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request,$id)
    {
        $user=RegisterUser::find($id);
        return response()->json([
            'status'=>true,
            'message'=> 'respective user data retrived successfully',
            'register_users'=>$user,
        ],Response::HTTP_OK); //individual data stored through their respective id
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user_data= $request->validated();
        $user=RegisterUser::find($id);
        if ($user){
            $user->update($user_data);
            return response()->json([
                'status'=>true,
                'message'=> 'User Data Updated successfully',
                'register_users'=>$user,
            ],Response::HTTP_OK);
        }
        else{
            return response()->json([
                'status'=>false,
                'message'=> ' user data not found',
                'register_users'=>null,
            ],Response::HTTP_NOT_FOUND);

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
//        dd('babita');
        $user=RegisterUser::find($id);
        if ($user){
            $user->delete();
            return response()->json([
                'status'=>true,
                'message'=> 'User Data Deleted successfully',
                'register_users'=>null,
            ],Response::HTTP_OK);
        }
        else{
            return response()->json([
                'status'=>false,
                'message'=> ' user data not found',
                'register_users'=>null,
            ],Response::HTTP_NOT_FOUND);

        }
    }
}

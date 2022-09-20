<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Response;
use App\Services\InviteService;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteUserRequest;

class InviteController extends Controller
{
    public function __construct(protected InviteService $inviteService)
    {

    }

    public function sendInvite(InviteUserRequest $request)
    {
        $validatedInviteUser = $request->validated();

        $inviteUserData = $this->inviteService->processInvite($validatedInviteUser);

        if(!$inviteUserData) {
            return $this->errorResponse('User couldnot be invited', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($inviteUserData, 'User invited successfully', Response::HTTP_OK);
    }
}

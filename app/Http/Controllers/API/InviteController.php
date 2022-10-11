<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Response;
use App\Services\InviteService;
use App\Http\Controllers\Controller;
use App\Http\Resources\InviteResource;
use App\Http\Requests\InviteUserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class InviteController extends Controller
{
    public function __construct(protected InviteService $inviteService)
    {

    }

    public function sendInvite(InviteUserRequest $request): JsonResponse
    {
        $validatedInviteUser = $request->safe()->only(['name', 'email', 'role_id']);

        $inviteUserData = $this->inviteService->processInvite($validatedInviteUser);

        if(!$inviteUserData) {
            return $this->errorResponse(
                'User couldnot be invited',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->successResponse(
            [
                'user' => InviteResource::make($inviteUserData['user']),
                'token' => $inviteUserData['token']
            ],
            'User invited successfully',
            Response::HTTP_OK
        );
    }
}

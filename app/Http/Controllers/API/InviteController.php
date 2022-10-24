<?php

namespace App\Http\Controllers\API;

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

        return $this->successResponse(
            [
                'user' => InviteResource::make($inviteUserData['user']),
                'token' => $inviteUserData['token']
            ],
            'User invited successfully',
        );
    }

    public function sendReInvite($id): JsonResponse
    {
        $reInviteUser = $this->inviteService->reInvite($id);

        return $this->successResponse(
            [
                'user' => InviteResource::make($reInviteUser['user']),
                'token' => $reInviteUser['token']
            ],
            'User re-invited successfully',
        );
    }

    public function listInvited(): JsonResponse
    {
        $invitedUsers = $this->inviteService->invitedUsers();

        return $this->successResponse(
            [
                'total' => count($invitedUsers),
                'invited_users' => InviteResource::collection($invitedUsers)
            ],
            null,
        );
    }

    public function revoke($id): JsonResponse
    {
        $this->inviteService->revokeInvite($id);

        return $this->successResponse(
            null,
            'User invite revoked successfully',
        );
    }
}

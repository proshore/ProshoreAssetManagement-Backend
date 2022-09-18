<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Response;
use App\Services\InviteService;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteRequest;

class InviteController extends Controller
{
    public function __construct(protected InviteService $inviteService)
    {
        $this->inviteService = $inviteService;
    }

    public function sendInvite(InviteRequest $request)
    {
        $validated = $request->validated();

        $data = $this->inviteService->processInvite($validated);

        if(!$data) {
            return $this->errorResponse('User couldnot be invited', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($data, 'User invited successfully', Response::HTTP_OK);
    }
}

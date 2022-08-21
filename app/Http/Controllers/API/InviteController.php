<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckEmailRequest;
use App\Services\InviteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class InviteController extends Controller
{
    public function listOfInvitedUsers(): JsonResponse
    {
        $employeeVendor=InviteService::invitedList();
        return response()->json([
            'total' => count($employeeVendor),
            'message'=>'List Of Invited User',
            'invitedUser'=>$employeeVendor


        ],200);
    }
    public function reInvite(CheckEmailRequest $request, InviteService $inviteService): JsonResponse
    {
        $validated = $request->safe()->only(['email']);
        $status = $inviteService->resendInvite($validated['email']);

        if (!$status) {
            return response()->json([
                'message' => 'User does not exist in our database'
            ], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'User re-invited successfully'
        ],\Illuminate\Http\Response::HTTP_OK);

    }
    public function revoke(Request $request):JsonResponse
    {
//        dd('babita');
        $status = InviteService::revokeInvite($request);

        if (!$status) {
            return response()->json([
                'message' => 'Cannot revoke invite. User does not exist in our database'
            ], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'message' => 'User  revoked successfully'
        ],\Illuminate\Http\Response::HTTP_OK);
    }
}

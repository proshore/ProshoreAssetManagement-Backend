<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberInviteRequest;
use App\Models\UserRole;
use App\Models\VendorEmployee;
use App\Services\AdminService;
use App\Services\InviteService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\Exception;

class EmployeeVendorController extends Controller
{
    public function deleteUser(Request $request){
        $employeeVendor=VendorEmployee::where('id',$request->id)->first();
        if (!$employeeVendor) {
            return response()->json([
                'message' => 'User does not exist with given id'
            ],Response::HTTP_NOT_FOUND);
        }
        AdminService::deleteRoles($request->id);
        $employeeVendor->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }
    public function viewAllUsers()
    {
        $employeeVendor = VendorEmployee::all();
        return response()->json([
            'total' => count($employeeVendor),
            'users' => $employeeVendor
        ], Response::HTTP_OK);

    }


    public function viewUserRole(Request $request)
    {
        $role = VendorEmployee::find($request->id)->roles;
        return response()->json([
            'total' => count($role),
            'users' => $role
        ], Response::HTTP_OK);

    }
    public function InviteOther(MemberInviteRequest $request, InviteService $inviteService)
    {
        $validated = $request->safe()->only([ 'vendor_employee_id', 'name','email','role_id']);
        $status = $inviteService->invite($validated['name'], $validated['email'], $validated['role_id'], $validated['vendor_employee_id']);


        if (!$status) {
            return response()->json([
                'message' => 'User could not be invited'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'User invited successfully'
        ], Response::HTTP_OK);
    }


}

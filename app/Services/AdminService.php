<?php

namespace App\Services;

use App\Models\UserRole;

class AdminService
{
    public static function deleteRoles($id)
    {
        $roles = UserRole::all()->where('vendor_employee_id', $id);
        foreach ($roles as $role) {
            $role->delete();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\UserRole;
use App\Models\VendorEmployee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'role'=>'Employee'
        ]);
        Role::create([
            'role'=>'Vendor'
        ]);
        Role::create([
            'role'=>'Employee'
        ]);
        Role::create([
            'role'=>'Vendor'
        ]);
        VendorEmployee::create([
            'name'=>'austin mariaaa',
            'email'=>'austinaa@gmail.com'
        ]);
        VendorEmployee::create([
            'name'=>'cathrinea mcbroom',
            'email'=>'cathrineaa@gmail.com'
        ]);
        VendorEmployee::create([
            'name'=>'steell mcbroom',
            'email'=>'steelaa@gmail.com'
        ]);
        UserRole::create([
            'vendor_employee_id'=>1,
            'vendor_employee_id'=>2,
            'vendor_employee_id'=>3,
            'vendor_employee_id'=>4,
        ]);
    }
}

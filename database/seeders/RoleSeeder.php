<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserManagement\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 1,
            'role_name' => 'Super Admin',
            'role_name_bn' => 'সুপার অ্যাডমিন',
            'org_id' => 1,
            'designation_id' => 1,
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserManagement\RoleUser;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoleUser::create([
            'user_id' => 1,
            'role_id' => 1
        ]);
        RoleUser::create([
            'user_id' => 2,
            'role_id' => 1
        ]);
        RoleUser::create([
            'user_id' => 3,
            'role_id' => 1
        ]);
        RoleUser::create([
            'user_id' => 4,
            'role_id' => 1
        ]);
        RoleUser::create([
            'user_id' => 5,
            'role_id' => 1
        ]);
        RoleUser::create([
            'user_id' => 6,
            'role_id' => 1
        ]);
        RoleUser::create([
            'user_id' => 7,
            'role_id' => 1
        ]);
    }
}

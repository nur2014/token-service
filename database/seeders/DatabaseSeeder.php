<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\ExternalUserType::create([
        //     'user_type_id' => 1,
        //     'user_type_name' => "Farmer"
        // ]);
        // \App\Models\ExternalUserType::create([
        //     'user_type_id' => 2,
        //     'user_type_name' => "Warehouse Farmer"
        // ]);
        $this->call(DynamicUserSeeder::class);
        // $this->call(UserDetailSeeder::class);
        // $this->call(RoleSeeder::class);
        // $this->call(UserRoleSeeder::class);
    }
}

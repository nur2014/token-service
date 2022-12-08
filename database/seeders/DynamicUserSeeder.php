<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DynamicUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 27; $i <= 32; $i++) {
            User::create([
                'name' => 'farmer'.$i,
                'name_bn' => 'farmer'.$i,
                'username' => '017333222'.$i,
                'email' => 'farmer'.$i.'@gmail.com',
                'mobile_no' => '017333222'.$i,
                'user_type_id' => 1,
                'status' => 0,
                'password' => '$2y$12$fr0yA2A/3bNsUCZY26iwdOwnL0.rDeeVi9mWQIBNhXoeb/Tr8jck2'
            ]);
        }
    }
}

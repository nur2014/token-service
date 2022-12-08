<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => 'Admin',
            'name_bn' => 'অ্যাডমিন',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'mobile_no' => '01723019023',
            'password' => '$2y$12$fr0yA2A/3bNsUCZY26iwdOwnL0.rDeeVi9mWQIBNhXoeb/Tr8jck2'
        ]);
        User::create([
            'id' => 2,
            'name' => 'Admin2',
            'name_bn' => 'অ্যাডমিন',
            'username' => 'admin2',
            'email' => 'admin2@gmail.com',
            'mobile_no' => '01723019022',
            'password' => '$2y$12$fr0yA2A/3bNsUCZY26iwdOwnL0.rDeeVi9mWQIBNhXoeb/Tr8jck2'
        ]);
        User::create([
            'id' => 3,
            'name' => 'Admin3',
            'name_bn' => 'অ্যাডমিন',
            'username' => 'admin3',
            'email' => 'admin3@gmail.com',
            'mobile_no' => '01723019021',
            'password' => '$2y$12$fr0yA2A/3bNsUCZY26iwdOwnL0.rDeeVi9mWQIBNhXoeb/Tr8jck2'
        ]);
        User::create([
            'id' => 4,
            'name' => 'Admin4',
            'name_bn' => 'অ্যাডমিন',
            'username' => 'admin4',
            'email' => 'admin4@gmail.com',
            'mobile_no' => '01723019024',
            'password' => '$2y$12$fr0yA2A/3bNsUCZY26iwdOwnL0.rDeeVi9mWQIBNhXoeb/Tr8jck2'
        ]);
        User::create([
            'id' => 5,
            'name' => 'Admin5',
            'name_bn' => 'অ্যাডমিন',
            'username' => 'admin5',
            'email' => 'admin5@gmail.com',
            'mobile_no' => '01723019025',
            'password' => '$2y$12$fr0yA2A/3bNsUCZY26iwdOwnL0.rDeeVi9mWQIBNhXoeb/Tr8jck2'
        ]);
        User::create([
            'id' => 6,
            'name' => 'Admin6',
            'name_bn' => 'অ্যাডমিন',
            'username' => 'admin6',
            'email' => 'admin6@gmail.com',
            'mobile_no' => '01723019026',
            'password' => '$2y$12$fr0yA2A/3bNsUCZY26iwdOwnL0.rDeeVi9mWQIBNhXoeb/Tr8jck2'
        ]);
        User::create([
            'id' => 7,
            'name' => 'Admin7',
            'name_bn' => 'অ্যাডমিন',
            'username' => 'admin7',
            'email' => 'admin7@gmail.com',
            'mobile_no' => '01723019027',
            'password' => '$2y$12$fr0yA2A/3bNsUCZY26iwdOwnL0.rDeeVi9mWQIBNhXoeb/Tr8jck2'
        ]);
    }
}

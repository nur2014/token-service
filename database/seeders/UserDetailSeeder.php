<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserManagement\UserDetail;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserDetail::create([
            'user_id' => 1,
            'name' => 'Admin',
            'name_bn' => 'অ্যাডমিন',
            'email' => 'admin@gmail.com',
            'org_id' => 1,
            'office_id' => 1,
            'office_type_id' => 1,
            'phone_no' => '01723019011',
            'role_id' => 1,
            'designation_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        UserDetail::create([
            'user_id' => 2,
            'name' => 'Admin2',
            'name_bn' => 'অ্যাডমিন',
            'email' => 'admin2@gmail.com',
            'org_id' => 1,
            'office_id' => 1,
            'office_type_id' => 1,
            'phone_no' => '01723019012',
            'role_id' => 1,
            'designation_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        UserDetail::create([
            'user_id' => 3,
            'name' => 'Admin3',
            'name_bn' => 'অ্যাডমিন',
            'email' => 'admin3@gmail.com',
            'org_id' => 1,
            'office_id' => 1,
            'office_type_id' => 1,
            'phone_no' => '01723019013',
            'role_id' => 1,
            'designation_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        UserDetail::create([
            'user_id' => 4,
            'name' => 'Admin4',
            'name_bn' => 'অ্যাডমিন',
            'email' => 'admin4@gmail.com',
            'org_id' => 1,
            'office_id' => 1,
            'office_type_id' => 1,
            'phone_no' => '01723019014',
            'role_id' => 1,
            'designation_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        UserDetail::create([
            'user_id' => 5,
            'name' => 'Admin5',
            'name_bn' => 'অ্যাডমিন',
            'email' => 'admin5@gmail.com',
            'org_id' => 1,
            'office_id' => 1,
            'office_type_id' => 1,
            'phone_no' => '01723019015',
            'role_id' => 1,
            'designation_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        UserDetail::create([
            'user_id' => 6,
            'name' => 'Admin6',
            'name_bn' => 'অ্যাডমিন',
            'email' => 'admin6@gmail.com',
            'org_id' => 1,
            'office_id' => 1,
            'office_type_id' => 1,
            'phone_no' => '01723019016',
            'role_id' => 1,
            'designation_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        UserDetail::create([
            'user_id' => 7,
            'name' => 'Admin7',
            'name_bn' => 'অ্যাডমিন',
            'email' => 'admin7@gmail.com',
            'org_id' => 1,
            'office_id' => 1,
            'office_type_id' => 1,
            'phone_no' => '01723019017',
            'role_id' => 1,
            'designation_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}

<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $table = "user_details";

    protected $casts = [
		'sso' => 'array'
	];
}

<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table ="roles";

    protected $fillable = [
        'role_name','role_name_bn','org_id','designation_id','status' 
    ];
    public static function activeRole() {
        return Role::Select(['id', 'role_name', 'role_name','role_name_bn','org_id'])->where('status', 0)->where('id', '!=', 1);
    }
}

<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use App\Models\UserManagement\Role;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'name_bn', 'email', 'password', 'username', 'user_type_id', 'otp', 'is_active','mobile_no','nid', 'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

   
    public function userDetail()
    {
        return $this->hasOne(\App\Models\UserManagement\UserDetail::class, 'user_id', 'id');
    }

    public function userDetailFarmer()
    {
        return $this->hasOne(\App\Models\UserManagement\UserDetailFarmer::class);
    }
    public function role_user()
    {
        return $this->hasMany(\App\Models\UserManagement\RoleUser::class, 'user_id');
    }

    /**
     * Defining relationship with roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

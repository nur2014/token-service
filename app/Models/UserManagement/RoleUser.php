<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table ="role_user";
    public $timestamps = false;
    
    protected $fillable = [
        'role_id','user_id'
    ];
    public function role()
    {
        return $this->belongsTo('App\Models\UserManagement\Role', 'role_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id', 'id');
    }
}

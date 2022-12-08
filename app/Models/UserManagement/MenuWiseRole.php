<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class MenuWiseRole extends Model
{
    protected $table ="menu_wise_role";

    protected $fillable = [
        'role_id','master_menu_id','component_id','module_id','service_id','created_by','updated_by'
    ];
    public function role()
    {
        return $this->belongsTo('App\Models\UserManagement\Role', 'role_id');
    }
    public static function defaultField() {
        return MenuWiseRole::Select(['role_id', 'master_menu_id', 'component_id','module_id','service_id']);
    }
}

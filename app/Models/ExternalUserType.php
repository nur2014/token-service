<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalUserType extends Model
{
    protected $table = "external_user_types";
    protected $fillable = ['user_type_id', 'user_type_name'];
}

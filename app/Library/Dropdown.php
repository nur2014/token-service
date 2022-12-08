<?php
namespace App\Library;

use DB;

class Dropdown
{
    public static function userTypeList()
    {
        return DB::table('external_user_types')->select('user_type_id as value', 'user_type_name as text')->get();
    }

    
   
}

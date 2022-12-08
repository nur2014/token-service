<?php
namespace app\Http\Validations\UserManagement;

use Validator, DB;

class RoleValidation
{
    /**
     * User Role Valiation
     */
    public static function validate($request, $id = 0)
    {
        $validator = Validator::make($request->all(), [
            'role_name'       => 'required',
            'role_name_bn'    => 'required',
        ]);

        $validator->after(function ($validator) use ($request, $id) {
            if (self::isNotUniqueRole($request, 'role_name', $request->role_name, $id)) {
                $validator->errors()->add(
                    'role_name', 'This role already exists'
                );
            }
        });

        $validator->after(function ($validator) use ($request, $id) {
            if (self::isNotUniqueRole($request, 'role_name_bn', $request->role_name_bn, $id)) {
                $validator->errors()->add(
                    'role_name_bn', 'This role already exists id=' . $request->org_id . ' '.$id . $request->role_name_bn
                );
            }
        });
        
        if ($validator->fails()) {
            return([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }
        return ['success'=>true];

    }

    private static function isNotUniqueRole($request, $tableFieldName, $value, $roleId)
    {
        $query = DB::table('roles')->where('org_id', $request->org_id);
        if ($tableFieldName === 'role_name') {
            $query = $query->where('role_name', $value);
        } elseif ($tableFieldName === 'role_name_bn') {
            $query = $query->where('role_name_bn', $value);
        }

        if ($roleId) {
            $query = $query->where('id', '!=', $roleId);
        }

        return $query->exists();
    }
}
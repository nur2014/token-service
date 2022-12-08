<?php
namespace app\Http\Validations\UserManagement;

use Validator;

class UserValidation
{
    /**
     * user account validation
     */
    public static function validate($request, $id=0)
    {
        $validator = Validator::make($request->all(), [
            'org_id'                    => 'required',
            'office_id'                 => 'required',
            'office_type_id'            => 'required',
            'designation_id'            => 'required',
            'name'                      => 'required',
            'name_bn'                   => 'required',
            'username'                  => 'required|unique:users,username,'.$id,
            'email'                     => 'required|unique:users,email,'.$id,
            // 'nothi_user_id'             => 'nullable|unique:users,nothi_user_id,'.$id,
            'password'                  => 'required|min:6',
            'password_confirmation'     => 'required|min:6|same:password',
            'mobile_no'                 => 'required|unique:users,mobile_no,'.$id,
        ]);

        if ($validator->fails()) {
            return ([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        return ['success'=> 'true'];
    }

    public static function operatorStorevalidate($data, $id=0)
    {
        $validator = Validator::make($data, [
            'mobile_no'  => 'required|unique:users,mobile_no,'.$id,
        ]);

        if ($validator->fails()) {
            return ([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        return ['success'=> 'true'];
    }
    public static function ginnerGrowervalidate($data, $id=0)
    {
        $validator = Validator::make($data, [
            'name'  => 'required',
            'name_bn'  => 'required',
            'password'  => 'required',
            'type'  => 'required|between:1,2',
            'mobile_no'  => 'required|unique:users,username,'.$id,
        ]);

        if ($validator->fails()) {
            return ([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        return ['success'=> 'true'];
    }
}

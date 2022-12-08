<?php
namespace app\Http\Validations;

use Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserValidation
{
    /**
     * user account validation
     */
    public static function validate($request, $id =0)
    {
        $uniqueOnUpdate = $id ? ",{$id}" : '';

        $userNameList = User::pluck('username')->all();
        $userMobileList = User::pluck('mobile_no')->all();

        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'name_bn'     => 'required',
            // 'mobile_no' => [
            //     'required',
            //     Rule::unique('users', 'username')
            //             ->ignore($uniqueOnUpdate)
            //             ->whereNotIn('username', $userNameList),
            //     Rule::unique('users', 'mobile_no')
            //             ->ignore($uniqueOnUpdate)
            //             ->whereNotIn('mobile_no', $userMobileList)
            // ],
            'mobile_no'     => 'required|unique:users,username'.$uniqueOnUpdate,
            'mobile_no'     => 'required|unique:users,mobile_no'.$uniqueOnUpdate,
            'email'     => 'nullable|unique:users,email'.$uniqueOnUpdate,
            'password'  => 'required|min:6',
            'password_confirmation'  => 'required|min:6|same:password',
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

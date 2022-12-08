<?php

namespace App\Library;

use Illuminate\Support\Facades\Cache;
use DB;

class CommonInfo
{
    public static function farmerUserDetail () {
        try {
            $data = \App\Models\User::with(['userDetail', 'userDetailFarmer'])->find(user_id());
        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'data' => null,
                'username' => null
            ]);
        }

        return response([
            'success' => true,
            'data' => $data,
            'username' => username()
        ]);
    }


    public static function getAuthUserDetail($request)
    {
        $officeDetail = null;

        if ($request->user()->user_type_id === 0) {
            $officeDetail = self::getOrgAdminUserOfficeDetail($request->user()->userDetail->office_id);
            $officeDetail = json_decode($officeDetail);
            $officeDetail = (isset($officeDetail->success) && $officeDetail->success) ? $officeDetail->data : null;
        }
        return response([
            'success' => true,
            'data' => $request->user()->user_type_id ? $request->user() : $request->user()->userDetail,
            'username' => $request->user()->username,
            'user_id' => $request->user()->id,
            'user_type' => $request->user()->user_type_id,
            'is_org_admin' => $request->user()->is_org_admin,
            'dashboard_user' => $request->user()->dashboard_user,
            'office_detail' => $officeDetail
        ]);
    }

    /**
     * Auth user detail
     */
    public static function authUserDetail($user)
    {
        $officeDetail = null;

        if ($user->user_type_id === 0) {
            $officeDetail = self::getOrgAdminUserOfficeDetail($user->userDetail->office_id);
            $officeDetail = json_decode($officeDetail);
            $officeDetail = (isset($officeDetail->success) && $officeDetail->success) ? $officeDetail->data : null;
        }
        return [
            'success' => true,
            'data' => $user->user_type_id ? $user : $user->userDetail,
            'username' => $user->username,
            'nid' => $user->nid,
            'user_id' => $user->id,
            'user_type' => $user->user_type_id,
            'last_panel' => $user->last_panel,
            'panels' => $user->panels,
            'is_org_admin' => $user->is_org_admin,
            'dashboard_user' => $user->dashboard_user,
            'office_detail' => $officeDetail
        ];
    }

    public static function getOrgAdminUserOfficeDetail($officeId)
    {
        $baseUrl = config('app.base_url.common_service');
        $uri = "/auth-user-office-detail/{$officeId}";
        $param = [];
        return \App\Library\RestService::getData($baseUrl, $uri, $param);
    }

    /**
     * All Components are cached for 24 hours. Which are retrieved from 
     */
    public static function getAllComponents()
    {
        $baseUrl = config('app.base_url.common_service');
        $uri = "/common/component-list";
        $param = [];
        return \App\Library\RestService::getData($baseUrl, $uri, $param);
         
        return $value;
    }

    /** External user common profile data save  */
    public static function externalUserCommonProfileStore($model)
    {
        try {
            /** Common profile of auth server*/
            $formData = [
                'user_id' => $model->user_id,
                'email' => $model->email,
                'mobile_no' => $model->mobile_no,
                'name' => $model->name,
                'name_bn' => $model->name_bn,
                'father_name' => $model->father_name,
                'father_name_bn' => $model->father_name_bn,
                'nid' => $model->nid,
                'panel_name' => 'warehouse_farmers'
            ];
    
            $baseUrl = config('app.base_url.auth_service');
            $uri = '/external-user/common-profile';
            \App\Library\RestService::postData($baseUrl, $uri, $formData); 
        } catch (\Exception $ex) {

        }
    }

    public static function getUserDetailByUserIds ($userIds) {
        return DB::table('users')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->whereIn('users.id', $userIds)
            ->select('users.id as value','user_details.designation_id',
             'users.name as text_en',
             'users.name_bn as text_bn',
             'users.username', 'user_details.office_id'
             )
            ->get();
    }

}

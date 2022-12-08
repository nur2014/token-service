<?php
/**
 * This class only deals with external user common profile
 */
namespace App\Library;

use App\Models\User;
use DB;

class CommonProfile
{
    public static function save($data, $userId, $panelName)
    {
        try {
            if (self::updateUsersPanel($userId, $panelName) === 2) {
                return;
            }

            $formatedData = self::setData($data);
            if (count($formatedData) === 0) {
                return [
                    'success' => false,
                    'message' => 'No form data provided'
                ];
            }
    
            DB::table('external_user_common_profiles')->updateOrInsert(
                ['user_id' => $userId],
                $formatedData
            );
        } catch (\Exception $ex) {
            \Log::info("Failed to save common profile data. Panel name: {$panelName}, Error: {$ex->getMessage()}");
        }
    }

    private static function updateUsersPanel($userId, $panelName)
    {
        \Log::info("updateUsersPanel method called user id: . {$userId} panel: {$panelName}");

        if (empty($panelName)) {
            \Log::info("updateUsersPanel method. Panel name not updated.");
            return 1;
        }

        $user = User::find($userId);

        if (!$user) {
            \Log::info('updateUsersPanel method. User ID not found. Provided user ID: ' . $userId);
            return 2;
        }

        $panels = array_filter(explode(',', $user->panels));
        array_push($panels, $panelName);
        $panels = implode(',', array_unique($panels));

        $user->last_panel = $panelName;
        $user->panels = $panels;

        $user->save();
        
        return 0;
    }

    private static function setData($rawData)
    {
        $data = [];
    
        if (!empty($rawData['name'])) {
            $data['name'] = $rawData['name'];
        }
        
        if (!empty($rawData['name_bn'])) {
            $data['name_bn'] = $rawData['name_bn'];
        }

        if (!empty($rawData['mobile_no'])) {
            $data['mobile_no'] = $rawData['mobile_no'];
        }

        if (!empty($rawData['email'])) {
            $data['email'] = $rawData['email'];
        }

        if (!empty($rawData['father_name'])) {
            $data['father_name'] = $rawData['father_name'];
        }

        if (!empty($rawData['father_name_bn'])) {
            $data['father_name_bn'] = $rawData['father_name_bn'];
        }

        if (!empty($rawData['mother_name'])) {
            $data['mother_name'] = $rawData['mother_name'];
        }

        if (!empty($rawData['mother_name_bn'])) {
            $data['mother_name_bn'] = $rawData['mother_name_bn'];
        }

        if (!empty($rawData['gender'])) {
            $data['gender'] = $rawData['gender'];
        }

        if (!empty($rawData['region_id'])) {
            $data['region_id'] = $rawData['region_id'];
        }

        if (!empty($rawData['division_id'])) {
            $data['division_id'] = $rawData['division_id'];
        }

        if (!empty($rawData['district_id'])) {
            $data['district_id'] = $rawData['district_id'];
        }

        if (!empty($rawData['upazila_id'])) {
            $data['upazila_id'] = $rawData['upazila_id'];
        }

        if (!empty($rawData['city_corporation_id'])) {
            $data['city_corporation_id'] = $rawData['city_corporation_id'];
        }

        if (!empty($rawData['union_id'])) {
            $data['union_id'] = $rawData['union_id'];
        }

        if (!empty($rawData['ward_id'])) {
            $data['ward_id'] = $rawData['ward_id'];
        }

        if (!empty($rawData['nid'])) {
            $data['nid'] = $rawData['nid'];
        }

        if (!empty($rawData['present_address'])) {
            $data['present_address'] = $rawData['present_address'];
        }

        if (!empty($rawData['permanent_address'])) {
            $data['permanent_address'] = $rawData['permanent_address'];
        }

        if (!empty($rawData['image'])) {
            $data['image'] = $rawData['image'];
        }

        return $data;
    }

    public static function ginnerGrowerProfile($request, $user)
    {
        $formData = [
            'email' => $user->email,
            'mobile_no' => $user->mobile_no,
            'name' => $user->name,
            'name_bn' => $user->name_bn,
            'father_name' => $request->father_name,
            'father_name_bn' => $request->father_name_bn,
            'nid' => $request->nid,
            'district_id' => $request->district_id,
            'upazila_id' => $request->upazilla_id
        ];

        $panelName = $request->type == 1 ? 'ginner' : 'grower';
        self::save($formData, $user->id, $panelName);
    }

    /**
     * This method used when profile is created from admin panel and
     * User request request comes from form
     */
    public static function setCommonProfileData($request, $user, $panelName)
    {
        $formData = [
            'email' => $user->email,
            'mobile_no' => $user->mobile_no,
            'name' => $user->name,
            'name_bn' => $user->name_bn,
            'gender' => $request->gender,
            'father_name' => $request->father_name,
            'father_name_bn' => $request->father_name_bn,
            'mother_name' => $request->mother_name,
            'mother_name_bn' => $request->mother_name_bn,
            'nid' => $request->nid,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'upazila_id' => $request->upazilla_id,
            'union_id' => $request->union_id,
        ];

        self::save($formData, $user->id, $panelName);
    }
}
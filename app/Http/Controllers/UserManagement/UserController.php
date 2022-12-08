<?php

namespace App\Http\Controllers\UserManagement;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\UserManagement\UserDetail;
use App\Models\UserManagement\UserDetailFarmer;
use App\Models\UserManagement\RoleUser;
use App\Models\UserManagement\ResetPasswordCode;
use App\Models\UserManagement\SecretQuestionAnswer;
use App\Http\Validations\UserManagement\UserValidation;
use App\Http\Validations\UserManagement\UserWarehouseValidation;
use App\Library\RestService;

class UserController extends Controller
{
    /**
     * get all user by designation
     */
    public function getDesignationsWisePeople(Request $request){
        $collection = UserDetail::select(['designation_id'])->groupBy('designation_id')
            ->selectRaw('count(*) as total, designation_id')
            ->get();
        return $collection;
    }

    public function listByDesignation(Request $request)
    {
        $query = DB::table('users')
                    ->join('user_details', 'users.id', 'user_details.user_id')
                    // ->select('user_details.*')
                    ->select(
                        'users.id',
                        'users.username',
                        'users.mobile_no',
                        'users.email',
                        'users.nothi_user_id',
                        'users.user_type_id',
                        'users.is_org_admin',
                        'user_details.user_id',
                        'user_details.name',
                        'user_details.name_bn',
                        'user_details.org_id',
                        'user_details.phone_no',
                        'user_details.office_type_id',
                        'user_details.office_id',
                        'user_details.photo',
                        'user_details.designation_id',
                        'user_details.role_id'
                    )
                    ->where('users.user_type_id', 0);

        if (!empty($request->org_id)) {
            $query = $query->where('user_details.org_id',$request->org_id);
        }
        if (!empty($request->designation_id)) {
            $query = $query->where('user_details.designation_id',$request->designation_id);
        }

        if (!empty($request->office_type_id)) {
            $query = $query->where('user_details.office_type_id',$request->office_type_id);
        }

        if (!empty($request->office_id)) {
            $query = $query->where('user_details.office_id',$request->office_id);
        }

        $data =  $query->get();

        return response([
            'success' => true,
            'message' => 'User account list',
            'data' => $data
        ]);
    }

    /**
     * get all user
     */
    public function index(Request $request)
    {
        $query = DB::table('users')
                    ->join('user_details','users.id', '=', 'user_details.user_id')
                    ->leftjoin('secret_question_answers','users.id', '=', 'secret_question_answers.user_id')
                    ->leftjoin('secret_questions','secret_question_answers.secret_question_id', '=', 'secret_questions.id')
                    ->select('users.id',
					'users.username as user_id',
					'users.username',
					'users.mobile_no',
					'users.email',
					'users.nothi_user_id',
					'users.user_type_id',
					'users.is_org_admin',
					'user_details.name',
					'user_details.name_bn',
					'user_details.org_id',
					'user_details.phone_no',
					'user_details.status',
					'users.status as userStatus',
					'user_details.office_type_id',
					'user_details.office_id',
					'user_details.photo',
					'user_details.designation_id',
					'user_details.role_id',
					'secret_question_answers.secret_question_id',
					'secret_question_answers.answer',
					'secret_questions.question_name',
					'secret_questions.question_name_bn')
                    // ->where('users.user_type_id', 0)
                    ->orderBy('users.name', 'ASC')
                    ->orderBy('user_details.name', 'ASC');


        // if ($request->has('warehouse_id')) {
        //     if ($request->warehouse_id == -1) {
        //         $query->where('user_details.warehouse_id', '>', 0);
        //     } else {
        //         $query->where('user_details.warehouse_id', $request->warehouse_id);
        //     }
        // } else {
        //     $query->whereNull('user_details.warehouse_id');
        // }

        if ($request->org_id) {
            $query->where('user_details.org_id', $request->org_id);
        }

        if ($request->user_id) {
            $query->where('user_details.user_id', $request->user_id);
        }

        if ($request->designation_id) {
            $query->where('user_details.designation_id', $request->designation_id);
        }

        if ($request->search_key) {
            $query->where(function ($q) use ($request) {
                            $q->orWhere('user_details.name', 'like', "{$request->search_key}%")
                            ->orWhere('user_details.name_bn', 'like', "{$request->search_key}%")
                            ->orWhere('users.username', 'like', "{$request->search_key}%")
                            ->orWhere('user_details.email', 'like', "%{$request->search_key}%");
                        });
        }

        $list = $query->paginate(request('per_page', config('app.per_page')));

        return response([
            'success' => true,
            'message' => 'User account list',
            'data' => $list
        ]);
    }

    public function getUserList(Request $request)
    {
        $query = DB::table('users')
                    ->join('user_details','users.id', '=', 'user_details.user_id')
                    ->select(
                        'users.id',
                        'users.id as value',
                        'users.username as text',
                        'users.email as email',
                        'users.mobile_no as mobile',
                        'users.dashboard_user',
                        'user_details.name as text_en',
                        'user_details.name_bn as text_bn',
                        'user_details.org_id',
                        'user_details.office_type_id',
                        'user_details.designation_id',
                        'user_details.office_id',
                    )
                    ->where('username', '!=' ,"")
                    ->orderBy('username', 'asc');

        if(!empty($request->org_id)) {
            $query = $query->where('user_details.org_id', $request->org_id);
        }
        if(!empty($request->office_type_id)) {
            $query = $query->where('user_details.office_type_id', $request->office_type_id);
        }
        if(!empty($request->office_id)) {
            $query = $query->where('user_details.office_id', $request->office_id);
        }
        if(!empty($request->designation_id)) {
            $query = $query->where('user_details.designation_id', $request->designation_id);
        }

        if($request->user_type_id == 0) {
            $query = $query->where('users.user_type_id', 0);
        }

        if(!empty($request->user_ids) && count($request->user_ids) > 0 ) {
            $query = $query->whereIn('users.id', $request->user_ids);
        }

        $list = $query->get();

        return response([
            'success' => true,
            'message' => 'User list',
            'data' => $list
        ]);
    }

    /**
     * get all user
     */
    public function getAllUser()
    {
        $query = DB::table('user_details')->where('designation_id', '!=', null)->orderBy('name', 'ASC')->get();

        return response([
            'success' => true,
            'message' => 'User account list',
            'data' => $query
        ]);
    }

    /**
     * get userListRoleOnly
     */
    public function userListRoleOnly()
    {
        return UserDetail::Select(['id', 'user_id', 'name', 'name_bn','org_id', 'designation_id', 'office_id'])
        // ->where('role_id', '!=', 0)
        ->get();
    }

    /**
     * get userListByRoleId
     */
    public function userListByRoleId($roleId)
    {
        return UserDetail::Select(['id', 'user_id', 'name', 'name_bn','org_id', 'designation_id', 'office_id'])
        ->where('status', 0)
        ->where('role_id', $roleId)
        ->get();
    }

    /**
     * get getUserByUserId
     */
    public function getUserByUserId($userId)
    {
        return UserDetail::Select(['id', 'name', 'name_bn','org_id', 'designation_id', 'office_id'])
        ->where('status', 0)
        ->where('user_id', $userId)
        ->first();
    }

    /**
     * user store
     */
    public function farmerUpdate(Request $request)
    {
        $data=$request->all();
        $userDetail = UserDetail::where('user_id',$data['id']);
        if($request->photo){
            $photo          = $request->file('photo');
            $photo_name     = time().".".$photo->getClientOriginalExtension();
            $directory      = 'uploads/user-management/users/';
            $photo->move($directory, $photo_name);
            if(empty($userDetail)) {
                $userDetail = new UserDetail();
            }
            $userDetail->photo = $directory.$photo_name;
            $userDetail->save();
        }
    }

    public function store(Request $request)
    {
        $validationResult = UserValidation::validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        DB::beginTransaction();

        try {

            $user = new User();
            $user->name             = $request->name;
            $user->username         = $request->username;
            $user->email            = $request->email;
            $user->mobile_no        = $request->mobile_no;
            $user->nothi_user_id    = $request->nothi_user_id;
            $user->is_org_admin     = $request->is_org_admin == 'false' ? 0 : 1;
            $user->user_type_id     = isset($request->user_type_id) ? $request->user_type_id : 0;
            $user->password         = Hash::make($request->password); 
            $user->save();

            save_log([
                'data_id'    => $user->id,
                'table_name' => 'users'
            ]);

            $warehouseId = null;
            $warehouseOfficeId = null;

            $warehouseOfficeIdJsonObj = $this->getWarehouseOfficeId();

            if ($warehouseOfficeIdJsonObj->success) {
                $warehouseOfficeId = $warehouseOfficeIdJsonObj->data;
            }

            // Get warehouse Id based on office Id.
            if ($request->office_type_id == $warehouseOfficeId && $request->is_org_admin == "false") {
                $warehouseResObj = $this->getWarehouseId($request->office_id);
                $warehouseId =$warehouseResObj->data;
            }

            $user_detail = new UserDetail();
            $user_detail->user_id   = $user->id;
            $user_detail->supervisor_id= $request->supervisor_id;
            $user_detail->name      = $request->name;
            $user_detail->name_bn   = $request->name_bn;
            $user_detail->email     = $request->email;
            $user_detail->org_id    = (int)$request->org_id;
            $user_detail->office_id = (int)$request->office_id;
            $user_detail->phone_no  = $request->mobile_no;
            $user_detail->office_type_id    = (int)$request->office_type_id;
            $user_detail->role_id           = (int)$request->role_id;
            $user_detail->designation_id    = (int)$request->designation_id;
            $user_detail->created_by        = (int)user_id();
            $user_detail->updated_by        = (int)user_id();

            if ($request->photo) {
                $photo          = $request->file('photo');
                $photo_name     = time().".".$photo->getClientOriginalExtension();
                $directory      = 'uploads/user-management/users/';
                $photo->move($directory, $photo_name);
                $user_detail->photo = $directory.$photo_name;
            }

            $user_detail->warehouse_id = $warehouseId;
            $user_detail->save();

            save_log([
                'data_id'    => $user_detail->id,
                'table_name' => 'user_details'
            ]);

            if ($request->secret_question_id && $request->answer) {
                $user_sec_ques_ans = new SecretQuestionAnswer();
                $user_sec_ques_ans->answer  = $request->answer;
                $user_sec_ques_ans->user_id = $user->id;
                $user_sec_ques_ans->secret_question_id = $request->secret_question_id;
                $user_sec_ques_ans->save();
            }

            DB::commit();

            return response([
                'success' => true,
                'message' => 'User Created Successfully!',
                'data'    => $user
            ]);

        } catch (\Exception $ex) {

            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }
        $new_user = DB::table('users')
                    ->join('user_details','users.id', '=', 'user_details.user_id')
                    ->join('secret_question_answers','users.id', '=', 'secret_question_answers.user_id')
                    ->join('secret_questions','secret_question_answers.secret_question_id', '=', 'secret_questions.id')
                    ->select('users.id','users.username as user_id','users.email','user_details.name','user_details.name_bn',
                            'user_details.org_id','user_details.phone_no','user_details.status','user_details.office_type_id',
                            'user_details.office_id','user_details.role_id','user_details.photo','user_details.designation_id',
                            'secret_question_answers.secret_question_id','secret_question_answers.answer',
                            'secret_questions.question_name','secret_questions.question_name_bn'
                    )
                    ->where('users.id', $user->id)
                    ->get();

        return response([
            'success' => true,
            'message' => 'Data saved successfully',
            'data'    => $new_user
        ]);

	}
	public function updateUser(Request $request, $id)
	{
        $validationResult = UserValidation::validate($request, $id);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

		$user = User::find($id);
		$user_detail = UserDetail::where('user_id', $user->id)->first();
		$user_sec_ques_ans = SecretQuestionAnswer::where('user_id', $user->id)->first();

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        if (!$user_detail) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        DB::beginTransaction();

        try {

            $user->name             = $request->name;
            $user->username         = $request->username;
            $user->email            = $request->email;
            $user->mobile_no        = $request->mobile_no;
            $user->nothi_user_id    = $request->nothi_user_id;
            $user->is_org_admin     = $request->is_org_admin == 'false' ? 0 : 1;
            $user->user_type_id     = isset($request->user_type_id) ? $request->user_type_id : 0;
            $user->password         = Hash::make($request->password);
            $user->save();

            save_log([
                'data_id'    => $user->id,
                'table_name' => 'users'
            ]);

            $warehouseId = null;
            $warehouseOfficeId = null;

            $warehouseOfficeIdJsonObj = $this->getWarehouseOfficeId();

            if ($warehouseOfficeIdJsonObj->success) {
                $warehouseOfficeId = $warehouseOfficeIdJsonObj->data;
            }

            // Get warehouse Id based on office Id.
            if ($request->office_type_id == $warehouseOfficeId && !$request->is_org_admin) {
                $warehouseResObj = $this->getWarehouseId($request->office_id);
                $warehouseId =$warehouseResObj->data;
            }

            $user_detail->user_id   = $user->id;
            $user_detail->supervisor_id= $request->supervisor_id;
            $user_detail->name      = $request->name;
            $user_detail->name_bn   = $request->name_bn;
            $user_detail->email     = $request->email;
            $user_detail->org_id    = (int)$request->org_id;
            $user_detail->office_id = (int)$request->office_id;
            $user_detail->phone_no  = $request->mobile_no;
            $user_detail->office_type_id    = (int)$request->office_type_id;
            $user_detail->role_id           = (int)$request->role_id;
            $user_detail->designation_id    = (int)$request->designation_id;
            $user_detail->created_by        = (int)user_id();
            $user_detail->updated_by        = (int)user_id();

            if ($request->photo) {
                $photo          = $request->file('photo');
                $photo_name     = time().".".$photo->getClientOriginalExtension();
                $directory      = 'uploads/user-management/users/';
                $photo->move($directory, $photo_name);
                $user_detail->photo = $directory.$photo_name;
            }

            $user_detail->warehouse_id = $warehouseId;
            $user_detail->save();

            save_log([
                'data_id'    => $user_detail->id,
                'table_name' => 'user_details'
            ]);

            if ($request->secret_question_id != "null" && $request->answer != "null") {
				$user_sec_ques_ans->answer  = $request->answer;
                $user_sec_ques_ans->user_id = $user->id;
                $user_sec_ques_ans->secret_question_id = $request->secret_question_id;
                $user_sec_ques_ans->save();
            }

            DB::commit();

        } catch (\Exception $ex) {

            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Data saved successfully'
        ]);
    }

    /**
     * operator store
     */
    public function operatorStore(Request $request)
    {
        DB::beginTransaction();
        $data['mobile_no'] = $request->mobile_no;
        $validationResult = UserValidation::operatorStorevalidate($data);

        if (!$validationResult['success']) {
            return response($validationResult);
        }
        try {
            $email  = $request->email != null ? $request->email : "fm_".$request->mobile_no."@gmail.com";

            $user   = User::where('email', $email)->first();

            if ($user == null) {

                $user = new User();
                $user->name     = $request->name;
                $user->username = $request->mobile_no;
                $user->email    = $email;
                $user->password = Hash::make(123456);
                $user->user_type_id = 1;
                $user->mobile_no = $request->mobile_no;
                $user->save();

                save_log([
                    'data_id'    => $user->id,
                    'table_name' => 'users'
                ]);

                $user_detail = new UserDetail();
                $user_detail->user_id   = $user->id;
                $user_detail->name      = $request->name;
                $user_detail->name_bn   = $request->name_bn;
                $user_detail->email     = $email;
                $user_detail->org_id    = (int)$request->org_id;
                $user_detail->office_id = (int)$request->office_id;
                $user_detail->phone_no  = (int)$request->mobile_no;
                $user_detail->office_type_id    = (int)$request->office_type_id;
                $user_detail->role_id           = (int)$request->role_id;
                $user_detail->designation_id    = (int)$request->designation_id;
                $user_detail->created_by        = (int)user_id();
                $user_detail->updated_by        = (int)user_id();
                $user_detail->save();

                save_log([
                    'data_id'    => $user_detail->id,
                    'table_name' => 'user_details'
                ]);
            }

            DB::commit();

        } catch (\Exception $ex) {

            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Data save successfully',
            'data'    => $user
        ]);
    }

    /**
     * warehouse user store
     */
    public function storeWarehouse(Request $request)
    {
        $validationResult = UserWarehouseValidation::validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        DB::beginTransaction();

        try {

            $user = new User();
            $user->name     = $request->name;
            $user->username = $request->username;
            $user->mobile_no = $request->phone_no;
            $user->email    = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            save_log([
                'data_id'    => $user->id,
                'table_name' => 'users'
            ]);

            $user_detail = new UserDetail();
            $user_detail->user_id   = $user->id;
            $user_detail->name      = $request->name;
            $user_detail->name_bn   = $request->name_bn;
            $user_detail->email     = $request->email;
            $user_detail->org_id    = (int)$request->org_id;
            $user_detail->office_id = (int)$request->office_id;
            $user_detail->phone_no  = $request->phone_no;
            $user_detail->office_type_id    = (int)$request->office_type_id;
            $user_detail->role_id           = (int)$request->role_id;
            $user_detail->designation_id    = (int)$request->designation_id;
            $user_detail->warehouse_id    = (int)$request->warehouse_id;
            $user_detail->created_by        = (int)user_id();
            $user_detail->updated_by        = (int)user_id();
            if($request->photo){
                $photo          = $request->file('photo');
                $photo_name     = time().".".$photo->getClientOriginalExtension();
                $directory      = 'uploads/user-management/users/';
                $photo->move($directory, $photo_name);
                $user_detail->photo = $directory.$photo_name;
            }
            $user_detail->save();

            save_log([
                'data_id'    => $user_detail->id,
                'table_name' => 'user_details'
            ]);

            $user_sec_ques_ans = new SecretQuestionAnswer();
            $user_sec_ques_ans->answer  = $request->answer;
            $user_sec_ques_ans->user_id = $user->id;
            $user_sec_ques_ans->secret_question_id = $request->secret_question_id;
            $user_sec_ques_ans->save();

            DB::commit();

        } catch (\Exception $ex) {

            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        $new_user = DB::table('users')
                    ->join('user_details','users.id', '=', 'user_details.user_id')
                    ->join('secret_question_answers','users.id', '=', 'secret_question_answers.user_id')
                    ->join('secret_questions','secret_question_answers.secret_question_id', '=', 'secret_questions.id')
                    ->select('users.id','users.username as user_id','users.email','user_details.name','user_details.name_bn',
                            'user_details.org_id','user_details.phone_no','user_details.status','user_details.office_type_id',
                            'user_details.office_id','user_details.role_id','user_details.photo','user_details.designation_id',
                            'secret_question_answers.secret_question_id','secret_question_answers.answer',
                            'secret_questions.question_name','secret_questions.question_name_bn'
                    )
                    ->where('users.id', $user->id)
                    ->get();

        return response([
            'success' => true,
            'message' => 'Data save successfully',
            'data'    => $new_user
        ]);
    }

    /**
     * user destroy
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $secretQuestion = SecretQuestionAnswer::where('user_id', $id);

        if ($secretQuestion) {
            $secretQuestion->delete();
        }

        UserDetail::where('user_id', $id)->delete();

        $user->delete();

        save_log([
            'data_id'       => $id,
            'table_name'    => 'users',
            'execution_type'=> 2
        ]);


        return response([
            'success' => true,
            'message' => 'Data deleted successfully'
        ]);
    }

    /**
     * User change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required',
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'repeat_new_password' => 'required|same:new_password|min:6',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = User::find(user_id())->first();

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        if(!Hash::check($request->old_password, $user->password)){

            return response([
                'success' => false,
                'message' => 'Old password does not match.',
            ]);

        }else{

            $user->update(['password'=> Hash::make($request->new_password)]);

            return response([
                'success' => true,
                'message' => 'Password changed successfully.',
            ]);
        }
    }

    /**
     * User account recovery
     */
    public function accountRecovery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required',
            'secret_question_id'   => 'required',
            'answer'        => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = User::where('name', $request->name)->where('email', $request->email)->first();

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        $question_answer_result = SecretQuestionAnswer::where('user_id', $user->id)
                                    ->where('secret_question_id', $request->secret_question_id)
                                    ->where('answer', 'like', '%' .$request->answer . '%')
                                    ->first();

        if($question_answer_result != null) {

            $reset_password_code = new ResetPasswordCode();
            $reset_password_code->code = sprintf("%06d", mt_rand(1, 999999));
            $reset_password_code->user_id = $user->id;
            $reset_password_code->expiory_time = date('H:i:s');
            $reset_password_code->save();

            $data = [
                'name' => $user->name,
                'code' => $reset_password_code->code,
            ];

            $email = [
                'from'      => 'authserviceprovider@gmail.com',
                'to'        => $user->email,
                'subject'   => 'Account recovery code',
            ];

            Mail::send('mail', $data, function($message) use ($email) {
                $message->to($email['to'])->subject($email['subject']);
                $message->from($email['from']);
            });

            return response([
                'success' => true,
                'message' => 'Check your email for validation code.'
            ]);

        } else {
            return response([
                'success' => false,
                'message' => 'Question or answer not match.'
            ]);
        }
    }

    /**
     * User account validate with code
     */
    public function accountValidate(Request $request)
    {
        $check_code = ResetPasswordCode::select('id','expiory_time')
                                        ->where('code', $request->code)
                                        ->where('user_id', $request->user_id)
                                        ->where('status', 1)
                                        ->orderBy('id', 'DESC')
                                        ->first();

        if (!$check_code) {
            return response([
                'success' => false,
                'message' => 'Invalid validation code'
            ]);
        }

        $time_diff = abs(strtotime($check_code->expiory_time) - strtotime(date("H:i:s"))) / 60;

        if($time_diff <= 3) {

            $reset_account = ResetPasswordCode::find($check_code->id);
            $reset_account->status = 0;
            $reset_account->update();

            return response([
                'success' => true,
                'message' => 'Account validate successfully.',
            ]);

        } else {
            return response([
                'success' => true,
                'message' => 'Time expired, try agian to recovery your account',
            ]);
        }
    }

    /**
     * Show details of a farmer
     *
     * @param $farmer_id|null integer
     * @return \Illuminate\Http\Response
     */
    public function getFarmerDetails(Request $request)
    {
        $query = UserDetailFarmer::join('users', 'user_detail_farmers.user_id', '=', 'users.id');

        if ($request->warehouse_id) {
            $query = $query->where('warehouse_id', $request->warehouse_id);
        }

        $list = $query->get(['user_detail_farmers.*', 'users.username']);

        return response([
            'success' => count($list) > 0 ? true : false,
            'message' => 'User details',
            'data' => $list
        ]);
    }

    /**
     * Get Farmer Profile
     */
    public function getFarmerProfile($farmerId)
    {
        $data = UserDetailFarmer::join('users', 'user_detail_farmers.user_id', '=', 'users.id')
            ->where('users.username', $farmerId)
            ->orWhere('user_detail_farmers.mobile_no', $farmerId)
            ->first();

        return response([
            'success' => $data ? true : false,
            'message' => '',
            'data' => $data
        ]);
    }

    public function getFarmerList(Request $request)
    {
        $query = UserDetailFarmer::join('users', 'user_detail_farmers.user_id', '=', 'users.id');

        if ($request->division_id) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->region_id) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->upazilla_id) {
            $query->where('upazilla_id', $request->upazilla_id);
        }

        if ($request->union_id) {
            $query->where('union_id', $request->union_id);
        }

        if ($request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $list = $query->get(['user_detail_farmers.*']);

        return response([
            'success' => count($list) > 0 ? true : false,
            'message' => 'User details',
            'data' => $list
        ]);
    }

    /**
     * Get farmers with farmer ids
     */
    public function getSelectedFarmers(Request $request)
    {
        $result = [
            'success' => true,
            'data' => []
        ];
        try {
            $farmerIds = $request->farmer_ids;
            $result['data'] = UserDetailFarmer::whereIn('mobile_no', $farmerIds)->get();

        } catch (\Exception $ex) {
            $result['success'] = false;
        }

        return response($result);

    }

    /**
     * Display list of warehouse users
     *
     * @return \Illuminate\Http\Response
     */
    public function getWarehouseUserList()
    {
        return UserDetail::join('users', 'user_details.user_id', '=', 'users.id')
                ->where('users.user_type_id', 0)
                ->where('user_details.warehouse_id', '>', 0)
                ->select('user_details.user_id AS value', 'user_details.name AS text', 'user_details.name_bn AS text_bn', 'user_details.status')->get();
    }

    /**
     * Get farmers application status with farmer ids
     */
    public function getFarmerStatus($farmerId) {
        $data = UserDetailFarmer::where('mobile_no', $farmerId)->select('mobile_no','status', 'save_status', 'warehouse_id')->first();
        return response($data);
    }

    /**
     * Get farmers status for public panel
     */
    public function getWarehouseFarmerStatus($farmerId) {
        $data = UserDetailFarmer::where('mobile_no', $farmerId)->select('mobile_no','status', 'save_status', 'warehouse_id')->first();
        
        return response([
            'success' => $data ? true : false,
            'message' => $data ? 'Farmer data' : "Farmer data not found.",
            'data' => $data
        ]);
    }

    /**
     * Master Commodity Name status update
     */
    public function toggleStatus($id)
    {
        $masterUser = User::find($id);

        if (!$masterUser) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $masterUser->status = $masterUser->status ? 0 : 1;
        $masterUser->update();

        save_log([
            'data_id'       => $masterUser->id,
            'table_name'    => 'users',
            'execution_type'=> 2
        ]);

        return response([
            'success' => true,
            'message' => 'Data updated successfully',
            'data'    => $masterUser
        ]);
    }


    /**
     * Get Current office user
     */
    public function currentOfficeUser($office_id)
    {
        $users = DB::table('users')
                    ->leftJoin('user_details','users.id','user_details.user_id')
                    ->select('users.id','users.name','users.name_bn','user_details.designation_id')
                    ->where('user_details.office_id', $office_id)
                    ->where('users.user_type_id', 0)
                    ->get();

        if ($users->count() == 0) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        return response([
            'success' => true,
            'message' => 'User List',
            'data'    => $users
        ]);
    }

    public function pumpOptUserStore (Request $request) {

        try {

            $user = new User();
            $user->name     = $request->name;
            $user->username = $request->username;
            $user->mobile_no = $request->mobile_no;
            $user->email    = $request->email;
            $user->password = $request->password;
            $user->user_type_id = $request->user_type_id;
            $user->save();
            return $user;

        } catch (\Exception $ex) {


            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }
    }

    /**
     * get supervisor
     */
    public function supervisor($user_id)
    {
        $getSupervisor = UserDetail::select('supervisor_id')->where('user_id', $user_id)->first();

        if ($getSupervisor->supervisor_id != null) {

            $supervisor = UserDetail::select('name','phone_no as phone')->where('user_id', $getSupervisor->supervisor_id)->first();

            return [
                'success' => true,
                'data' => $supervisor
            ];
        }

        return [
            'success' => false,
            'message' => 'Supervisor not found.'
        ];
        
    }

    /**
     * user update
     */
    public function update ($mobile_no)
    {
        DB::beginTransaction();

        try {

            $user_detail = UserDetail::where('phone_no', $mobile_no)->first();
            $user_detail->phone_no = $mobile_no;
            $user_detail->update();

            $user = User::where('mobile_no', $mobile_no)->first();
            $user->mobile_no = $mobile_no;
            $user->update();

            DB::commit();

        } catch (\Exception $ex) {

            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return [
            'success' => true,
            'data'    => $user
        ];
    }

    /**
     * grower/ginner store
     */
    public function growerGinnerStore(Request $request)
    {
        DB::beginTransaction();
        $validationResult = UserValidation::ginnerGrowervalidate($request->all());

        if (!$validationResult['success']) {
            return response($validationResult);
        }
        $prefix = $request->type == 1 ? 'ginner_' : 'grower_';
        try {
            $email  = $request->email != null ? $request->email : $prefix.$request->mobile_no."@gmail.com";

            $user   = User::where('username', $request->mobile_no)->first();

            if ($user == null) {

                $user = new User();
                $user->name     = $request->name;
                $user->name_bn  = $request->name_bn;
                $user->username = $request->mobile_no;
                $user->email    = $email;
                $user->password = $request->password;
                $user->user_type_id = $request->type == 1 ? 3 : 4;
                $user->mobile_no = $request->mobile_no;
                $user->save();

                save_log([
                    'data_id'    => $user->id,
                    'table_name' => 'users'
                ]);
            }
            DB::commit();
            \App\Library\CommonProfile::ginnerGrowerProfile($request, $user);

        } catch (\Exception $ex) {

            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Data save successfully',
            'data'    => $user
        ]);
    }

    /**
     * user destroy by mobile
     */
    public function destroyUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile_no'  => 'required'
        ]);

        if ($validator->fails()) {
            return ([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        $user = User::where('username', $request->mobile_no)->first();

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }
        $userDetails = UserDetail::where('user_id', $user->id)->first();

        if ($userDetails) {
            $userDetails->delete();
        }

        $user->delete();

        save_log([
            'data_id'       => $user->id,
            'table_name'    => 'users',
            'execution_type'=> 2
        ]);


        return response([
            'success' => true,
            'message' => 'Data deleted successfully'
        ]);
    }

    /**
     * Get warehouse id based on office id
     *
     * @param int $office_id
     * @return \Illuminate\Http\Reponse
     */
    private function getWarehouseId($office_id)
    {
        $baseUrl = config('app.base_url.warehouse_service');
        $uri = '/master-warehouse-info/show/' . $office_id;

        $responseJson = RestService::getData($baseUrl, $uri);

        $responseJsonObj = json_decode($responseJson);

        if (!$responseJsonObj->success) {
            return (object) [
                'success' => false,
                'message' => $responseJsonObj->message
            ];
        }

        return (object) [
            'success' => true,
            'data' => $responseJsonObj->data
        ];
    }

    /**
     * Get warehouse office type id
     *
     * @return \Illuminate\Http\Reponse
     */
    private function getWarehouseOfficeId()
    {
        $baseUrl = config('app.base_url.common_service');
        $uri = '/master-office-types/warehouse-office-type-id';

        $responseJson = RestService::getData($baseUrl, $uri);

        $responseJsonObj = json_decode($responseJson);

        if (!$responseJsonObj->success) {
            return (object) [
                'success' => false,
                'message' => $responseJsonObj->message
            ];
        }

        return (object) [
            'success' => true,
            'data' => $responseJsonObj->data
        ];
    }


    /**
     * Create or update external user
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Reponse
     */
    public function dashboardUser (Request $request) {

        $requestAll = $request->all();

        if (!empty($requestAll['details'])) {

            foreach($requestAll['details'] as $key=>$value) {

                $model = User::find($value['id']);
                $model->dashboard_user = $value['dashboard_user'];
                $model->save();
                save_log([
                    'data_id'    => $value['id'],
                    'table_name' => 'users',
                    'execution_type'=> 1
                ]);

            }

        }

        return response([
            'success' => true,
            'message' => 'Data save successfully',
            'data' => $requestAll
        ]);

    }

    
    public function daeFarmerDelete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $user->delete();

        return response([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }

    /**
     * Get total warehouse farmer number
     *
     * @return \Illuminate\Http\Response
     */
    public function getTotalWarehouseFarmerNumber()
    {
        $totalFarmer = DB::table('users AS u')
            ->join('user_detail_farmers AS udf', 'u.id', 'udf.user_id')
            ->where([
                'u.user_type_id' =>  2,
                'udf.status' =>  1,
            ])
            ->count('u.id');

        return response([
            'success' => true,
            'data' => $totalFarmer,
            'message' => 'Data Found!'
        ]);
    }

    /**
     * Get super admin user
     *
     * @return \Illuminate\Http\Response
     */
    public function getSuperAdmin()
    {
        $model = User::whereHas('userDetail', function ($query) {
            $query->where('role_id', 1);
        })->first();

        if (!$model) {
            return response([
                'success' => false,
                'message' => 'Data not found!'
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Data found!',
            'data' => $model
        ]);
    }
}


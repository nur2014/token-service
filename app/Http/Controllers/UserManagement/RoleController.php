<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\RoleUser;
use App\Models\UserManagement\MenuWiseRole;
use App\Models\User;
use App\Http\Validations\UserManagement\RoleValidation;

class RoleController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }  

    /**
     * get all user Role
     */
    public function roleUserList(Request $request)
    {
        $query = User::with([
            'role_user:user_id,role_id','role_user.role:id,role_name,role_name_bn','userDetail:id,user_id,name,email,name_bn,org_id,office_id,office_type_id,designation_id,org_id']);
        if ($request->search_key) {
            $query->where('name','like','%'.$request->search_key.'%');
        }
        if ($request->role_id) {
            $query->wherehas('role_user', function ($query1) use ($request)
            {
                $query = $query1->where('role_id', $request->role_id);
            });
        }
        $query = $query->where('user_type_id', 0);
        $userrole = $query->orderBy('name', 'ASC')->latest()->paginate($request->per_page ?? 1);
        return response([
            'success' => true,
            'message' => 'User Role list',
            'data' =>$userrole
        ]);
    }

    public function assignRole(Request $request)
    {
        $data=$request->all();
        if(!empty($data)){
            RoleUser::where('user_id',$data['user_id'])->delete();
            if(!empty($data['roles'])){
                foreach ($data['roles'] as $key=>$value){
                    if($value['checked']){
                        RoleUser::create($value);
                    }
                }
            }
        }
        return response([
            'success' => true,
            'message' => 'Role assign successfully'
        ]);
    }
    public function roleUser(Request $request){
        $data = $request->all();
        $query= Role::activeRole();

        if (!empty($request->org_id)) {
            $query = $query->where('org_id', $request->org_id);
        }

        $datas= $query->get()->each(function ($role) use ($data) {
            $role->checked = false;
            $role->user_id = $data['user_id'];
            $role->role_id = $role['id'];

            $role_user = RoleUser::where('user_id', $data['user_id'])->where('role_id', $role['id'])->first();

            if (!empty($role_user)){
                $role->checked = true;
            }

            return $role;
        });
        
        return $datas;
    }

    public function index(Request $request)
    {
        $query = Role::select("*");
       
        if ($request->role_name) {
            $query = $query->where('role_name', 'like', "{$request->role_name}%")
                           ->orWhere('role_name_bn', 'like', "{$request->role_name}%");            
        }

        if ($request->org_id) {
            $query = $query->where('org_id', $request->org_id);
        }
        
        if ($request->designation_id) {
            $query = $query->where('designation_id', 'like', $request->designation_id);
        }

        if ($request->status) {
            $query = $query->where('status', $request->status);
        }

        if ($request->role_id) {
            $query = $query->where('id', $request->role_id);
        }
        
        $userrole = $query->orderBy('role_name', 'ASC')
                            ->paginate(request('per_page', config('app.per_page')));

        return response([
            'success' => true,
            'message' => 'User Role list',
            'data' =>$userrole
        ]);
    }

    /**
     * User Role store
     */
    public function roleWiseMenuList(Request $request)
    {
        $query = MenuWiseRole::defaultField()->with(['role:id,role_name,role_name_bn'])->latest();
        if (!empty($request->component_id)) {
            $query = $query->where('component_id', $request->component_id);
        }
        if (!empty($request->module_id)) {
            $query = $query->where('module_id', $request->module_id);
        }
        if (!empty($request->service_id)) {
            $query = $query->where('service_id', $request->service_id);
        }
        if (!empty($request->master_menu_id)) {
            $query = $query->where('master_menu_id', $request->master_menu_id);
        }
        if (!empty($request->role_id)) {
            $query = $query->where('role_id', $request->role_id);
        }
        $role_wise_menus = $query->paginate($request->per_page ?? 1);
        return response([
            'success' => true,
            'message' => 'Role Wise Menu list',
            'data' =>$role_wise_menus
        ]);
    }
    public function assignMenus(Request $request){
        $query = MenuWiseRole::defaultField()->where('role_id',$request->role_id);
        if (!empty($request->component_id)) {
            $query = $query->where('component_id', $request->component_id);
        }
        if (!empty($request->module_id)) {
            $query = $query->where('module_id', $request->module_id);
        }
        $datas= $query->get();
        return  $datas;
    }
    
    /**
     * Get all menus of a role
     */
    public function menusByRole($roleId) {
        \Log::info("role_id @{$roleId}");
        return MenuWiseRole::defaultField()->where('role_id', $roleId)->get();
    }
    public function storeMenu(Request $request,$role_id,$component_id,$module_id)
    {
        $datas=$request->all();
        if(!empty($datas)){
            $query = MenuWiseRole::where('role_id',$role_id);
            if (!empty($component_id)) {
                $query = $query->where('component_id', $component_id);
            }
            if (!empty($module_id)) {
                $query = $query->where('module_id', $module_id);
            }
            $role_menu=$query->delete();
            if(!empty($datas)){
                foreach ($datas as $key=>$value){
                    if(!empty($value['service'])){
                        foreach ($value['service'] as $key1=>$value1){
                            if(!empty($value1['master_menus'])){
                                foreach ($value1['master_menus'] as $key2=>$value2){
                                    if(isset($value2['checked']) && $value2['checked']){
                                        $new_data=[
                                            'role_id'=>$role_id,
                                            'master_menu_id'=>$value2['id'],
                                            'component_id'=>$value2['component_id'],
                                            'module_id'=>$value2['module_id'],
                                            'service_id'=>$value2['service_id'],
                                            'created_by'=>(int)user_id(),
                                            'updated_by'=>(int)user_id()
                                        ];
                                        MenuWiseRole::create($new_data);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return response([
            'success' => true,
            'message' => 'Menu wise  Role save successfully'
        ]);
    }
    public function store(Request $request)
    {
        $validationResult = RoleValidation::validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        try {
            $userrole = new Role();
            $userrole->role_name          = $request->role_name;
            $userrole->role_name_bn       = $request->role_name_bn;
            $userrole->org_id             = $request->org_id;
            $userrole->designation_id     = $request->designation_id;
            $userrole->created_by         =(int)user_id();
            $userrole->updated_by         =(int)user_id();
            $userrole->save();

            save_log([
                'data_id'    => $userrole->id,
                'table_name' => 'roles'
            ]);
          
        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Role data save successfully',
            'data'    => $userrole,
        ]);
    }

    /**
     * user Role  update
     */
    public function update(Request $request, $id)
    {   
        $validationResult = RoleValidation::validate($request, $id);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        $userrole = Role::find($id);

        if (!$userrole) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        try {
            $userrole->role_name        = $request->role_name;
            $userrole->role_name_bn     = $request->role_name_bn;
            $userrole->org_id           = $request->org_id;
            $userrole->designation_id   = $request->designation_id;
            $userrole->updated_by       = (int)user_id();
            $userrole->save();

            save_log([
                'data_id'       => $userrole->id,
                'table_name'    => 'roles',
                'execution_type'=> 1
            ]);

        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => 'Failed to update data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'User Role Data update successfully',
            'data'    => $userrole
        ]);
    }

    /**
     * User Role status update
     */
    public function toggleStatus($id)
    {
        $userrole = Role::find($id);

        if (!$userrole) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $userrole->status = $userrole->status ? 0 : 1;
        $userrole->save();

        save_log([
            'data_id'       => $userrole->id,
            'table_name'    => 'user_roles',
            'execution_type'=> 2
        ]);

        return response([
            'success' => true,
            'message' => 'Role Data status Update  successfully',
            'data'    => $userrole
        ]);
    }

    /**
     * User Role destroy
     */
    public function roleList($org_id){
        $roles = Role::activeRole();
        if(!empty($org_id) && $org_id != 'all'){
            $roles = $roles->where('org_id',$org_id);
        }
        return $roles->oldest('role_name')->get();
    }
    
    public function destroy($id)
    {
        $userrole = Role::find($id);

        if (!$userrole) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $userrole->delete();

        save_log([
            'data_id'       => $id,
            'table_name'    => 'user_roles',
            'execution_type'=> 2
        ]);

        return response([
            'success' => true,
            'message' => 'User Role Data deleted successfully'
        ]);
    }
}

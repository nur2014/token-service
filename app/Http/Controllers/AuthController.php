<?php
/**
 * Handle only Access Permission related task
 * @author Md. Moktar Ali
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserManagement\{MenuWiseRole, UserDetail};
use App\Library\CommonInfo;
use DB, Log, Auth;

class AuthController extends Controller
{
    /** Customizing Login */
    public function login(Request $request)
    {
        $isNothiLogin = (int)$request->nothi_login;

        Log::info("Login start: isNothiLogin = {$isNothiLogin}");

        $response = [
            'success' => false,
            'message' => ''
        ];

        if ($isNothiLogin) {
            $response = $this->loginWithNothi($request);
        } else {
            $response = $this->loginWithCredential($request);
        }

        return response($response);
    }

    /**
     * Login with credential
     */
    public function loginWithCredential($request)
    {
        $username = $request->username;
        $password = $request->password;
        $user = User::where('username', $username)->orWhere('email', $username)->first();
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Wrong username/email.',
                'token' => ''
            ];
        }

        if ($user && Hash::check($password, $user->password)) {
            return $this->setToken($user); 
        }

        return [
            'success' => false,
            'message' => 'Wrong password.',
            'token' => ''
        ];
    }

    /**
     * Login with nothi
     */
    private function loginWithNothi($request)
    {
        /** Decoding the obtained payload from nothi login */
        Log::info("Nothi login started:");

        $data = json_decode(gzuncompress(base64_decode($request->data)), true);
        $user = User::where('nothi_user_id', $data['user_info']['user']['username'])->first();

        Log::info("Nothi User ID: {$data['user_info']['user']['username']}");

        if (!$user) {
            return [
                'success' => false,
                'message' => "This User ID does not exist in the system."
            ];
        }

        $userDetail = $this->updateSsoInfo($user, $data);

        if (!$userDetail) {
            return [
                'success' => false,
                'message' => "User Detail Not Found"
            ];
        }

        return $this->setToken($user);
    }

    protected function updateSsoInfo($user, $data) {
        
        $key = array_search(true, array_column($data['user_info']['office_info'], 'status'));;
        $ssoInfo = [
            'office_id' => $data['user_info']['office_info'][$key]['office_id'],
            'user_id' => $data['user_info']['user']['id'],
            'fdesk' => $data['user_info']['office_info'][$key]['office_unit_organogram_id'],
        ];

        $userDetail = UserDetail::where('user_id', $user->id)->first();

        if (!$userDetail) {
            return false;
        }

        $userDetail->sso = $ssoInfo;
        $userDetail->save();

        return true;
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function setToken($user){
        return [
            'success' => true,
            'access_token' => $user->createToken('Personal Access Token')->accessToken,
            'token_type' => 'bearer',
            'expires_in' => 0,
            'user' => \App\Library\CommonInfo::authUserDetail($user)
        ];
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $token = $request->bearerToken();
        $decodedToken = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))), true);
        DB::table('oauth_access_tokens')->where('id', $decodedToken['jti'])->delete();

        return response([
            'success' => true
        ]);
    }
    /**
     * Get all roles of a user
     */
    public function userRoles(Request $request, $userId)
    {
        $userRoles = User::find($userId)->roles()->get();

        return response([
            'success' => true,
            'message' => 'User roles',
            'data' => $userRoles
        ]);
    }

    /**
     * Retrieves all component ids of all menus which are assigned to a role.
     */
    public function componentsByRole($roleId)
    {
        try {
            $query = MenuWiseRole::select('component_id');
    
            if ($roleId != 1) {
                $query = $query->where('role_id', $roleId);
            }
    
            $componentIds = $query->groupBy('component_id')->pluck('component_id')->all();
            
            if (count($componentIds) === 0) {
                return response([
                    'success' => false,
                    'message' => 'This role has not been assigned any menu',
                    'message_i18n_code' => 'authentication.noPrivilege'
                ]);   
            }
    
            $components = CommonInfo::getAllComponents();
            $components = json_decode($components, true);
    
            if (!isset($components['success'])) {
                return response([
                    'success' => false,
                    'message' => 'Failed get components.',
                    'message_i18n_code' => 'authentication.noComponent'
                ]);
            }
            
            $selectedComponents = [];
    
            foreach ($components['data'] as $component) {
                if (in_array($component['id'], $componentIds)) {
                    $selectedComponents[] = $component;
                }
            }
        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => 'Faield to get components',
                'message_i18n_code' => 'authentication.serverError'
            ]);
        }

        return response([
            'success' => true,
            'data' => $selectedComponents
        ]);
    }
}

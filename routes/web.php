<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use Illuminate\Support\Facades\Route;
use App\Library\CommonInfo;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    $routes = $router->getRoutes();
    // dd($routes);
    return $router->app->version();
});

$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});

/** Password Reset */
$router->post('/forgot-password/otp-sending','PasswordResetController@sendOtp');
$router->post('/forgot-password/otp-verification','PasswordResetController@verifyOtp');
$router->post('/forgot-password/change-password', 'PasswordResetController@changePassword');

/** Sending email */
$router->get('/email-send-testing', 'ExampleController@emailSend');

// Inter Service communication api testing
$router->get('/testing-api', function(\Illuminate\Http\Request $request) {
    Log::info('testing-api: started');
    Log::info('testing-api: '. ' authorization=' . app('request')->header('authorization'));
    Log::info('testing-api: '. ' accessUserId=' . app('request')->header('accessUserId'));
    Log::info('testing-api: '. ' test_param1=' . implode(',', $request->test_param1));
    Log::info('testing-api: '. ' test_param2=' . $request->test_param2);
    Log::info('testing-api: end');

    return response([
        'authorization' => app('request')->header('authorization'),
        'req' => app('request')
    ]);
});

$router->post('/testing-api', function(\Illuminate\Http\Request $request) {
    Log::info('testing-api: started: method: post');
    Log::info('testing-api: '. ' authorization=' . app('request')->header('authorization'));
    Log::info('testing-api: '. ' accessUserId=' . app('request')->header('accessUserId'));
    Log::info('form data: ');
    Log::info('test_input1:' . implode(', ', $request->test_input1)) ;
    Log::info('test_input2:'. $request->test_input2);
    Log::info('testing-api: end');

    return response([
        'authorization' => app('request')->header('authorization'),
        'req' => app('request')
    ]);
});

$router->put('/testing-api', function(\Illuminate\Http\Request $request) {
    Log::info('testing-api: started: method: put');
    Log::info('testing-api: '. ' authorization=' . app('request')->header('authorization'));
    Log::info('testing-api: '. ' accessUserId=' . app('request')->header('accessUserId'));
    Log::info('form data: ');
    Log::info('test_input1:' . implode(', ', $request->test_input1)) ;
    Log::info('test_input2:'. $request->test_input2);
    Log::info('testing-api: end');

    return response([
        'sucess' => true,
        'data' => [
            'test_input1' => implode(', ', $request->test_input1),
            'test_input2' => $request->test_input2
        ]
    ]);
});

$router->delete('/testing-api/{id}', function(\Illuminate\Http\Request $request, $id) {
    Log::info('testing-api: started: method: delete');
    Log::info('testing-api: '. ' authorization=' . app('request')->header('authorization'));
    Log::info('testing-api: '. ' accessUserId=' . app('request')->header('accessUserId'));
    Log::info('form data: id=' . $id);
    Log::info('test_input1:' . implode(', ', $request->test_input1)) ;
    Log::info('test_input2:'. $request->test_input2);
    Log::info('testing-api: end');

    return response([
        'sucess' => true,
        'data' => [
            'test_input1' => implode(', ', $request->test_input1),
            'test_input2' => $request->test_input2
        ]
    ]);
});
// End of api testing

$router->get('/external/user/user-type-list', function () {
    return response([
        'success' => true,
        'data' => \App\Library\Dropdown::userTypeList()
    ]);
});

/******************** Data Archive Module *********************/
Route::group(['prefix'=>'/data-archive'], function() {
    Route::get('/database-backup', 'DataArchiveController@dumpDB');
    //download file path from storage
    Route::get('download-backup-db', 'DataArchiveController@downloadBackupDb');
    Route::get('db-backup-files', 'DataArchiveController@getDbBackupFiles');
    Route::delete('db-backup-delete', 'DataArchiveController@deleteDbBackupFile');
});


$router->post('/external/user/sign-up','UserController@register');
$router->post('/external/user/otp-verify','UserController@otpVerify');
$router->post('/external/user/otp-resend','UserController@otpResend');
$router->post('/external/user/mobile-change','UserController@otpChangeSend');
$router->post('/external/user/mobile/update','UserController@changePhone');


$router->get('/external/user/check-username','UserController@checkUsername');



Route::group(['prefix' => '/notification'], function () {
    Route::get('/menu-wise-user-list','NotificationUserController@menuWiseUserList');
});

$router->get('/external/user/check-user-existence','UserController@checkUserExistence');
$router->post('/external/user/user-update','UserController@userUpdate');
$router->get('secret-question-dropdown', 'UserManagement\SecretQuestionController@secretQuestionDropdown');

$router->post('/register-farmer-from-irrigation', 'UserManagement\UserController@pumpOptUserStore');

//start testing
// Route::get('/user/detail', function (\Illuminate\Http\Request $request)
//     {
//         // return \App\Library\CommonInfo::getAuthUserDetail($request);
//         return response([
//             'success' => true,
//             'data' => 'test data'
//         ]);
//     });
//end testing

Route::group(['middleware'  =>  'auth:api'], function () {
    Route::get('token-verification', function () {
        Log::info('Token Verification route executed');
        return response([
            'success' => true
        ]);
    });
    Route::get('/user/detail', function (\Illuminate\Http\Request $request)
    {
        return \App\Library\CommonInfo::getAuthUserDetail($request);
        // return response([
        //     'success' => true,
        //     'data' => 'test data'
        // ]);
    });
    Route::get('/external/user', ['as' => 'external.user', function (\Illuminate\Http\Request $request)
    {
        return response([
            'success' => true,
            'data' => $request->user(),
            'username' => $request->user()->username
        ]);
    }]);
    Route::get('/auth-user', function () {
        return \App\Library\CommonInfo::farmerUserDetail();
    });

    // User Role Crud operation routes
    Route::group(['prefix'=>'/role'], function(){
        Route::get('/role-list-select/{org_id}', 'UserManagement\RoleController@roleList');

        Route::post('/assign-role', 'UserManagement\RoleController@assignRole');
        Route::get('/user-role-data', 'UserManagement\RoleController@roleUser');
        Route::get('/role-user-list', 'UserManagement\RoleController@roleUserList');

        Route::get('/role-wise-menu-list', 'UserManagement\RoleController@roleWiseMenuList');
        Route::get('/get-allready-assign-menus', 'UserManagement\RoleController@assignMenus');
        // The following route is created for replacement of the above route if the above route is only for the following purpose it should be deleted.
        Route::get('/role-menus/{roleId}', 'UserManagement\RoleController@menusByRole');
        Route::post('/menu-wise-role-store/{role_id}/{component_id}/{module_id}', 'UserManagement\RoleController@storeMenu');

        Route::get('/list', 'UserManagement\RoleController@index');
        Route::post('/store', 'UserManagement\RoleController@store');
        Route::put('/update/{id}', 'UserManagement\RoleController@update');
        Route::delete('/toggle-status/{id}', 'UserManagement\RoleController@toggleStatus');
        Route::delete('/destroy/{id}', 'UserManagement\RoleController@destroy');
    });

    Route::group(['prefix'=>'/log-report'], function(){
        Route::get('/list', 'LogReport\LogReportController@index');
    });
    //Task-Assign-User-Type
    Route::group(['namespace'=>'UserTaskManagement'], function() {
        Route::get('/get-task-assign-user-type-list/{id}/{userTypeId}/{officeId}/{upazillaId}', 'TaskManagementController@getUserList');
    });
});



// User Account operation routes
Route::group(['prefix'=>'/user'], function(){
    Route::post('/dealer-user-create', 'UserManagement\UserController@dealerUserCreate');
    Route::get('/list-by-designation', 'UserManagement\UserController@listByDesignation');
    Route::get('/getAllUser', 'UserManagement\UserController@getAllUser');
    Route::get('/list', 'UserManagement\UserController@index');
    Route::get('/userListRoleOnly', 'UserManagement\UserController@userListRoleOnly');
    Route::get('/userListByRoleId/{roleId}', 'UserManagement\UserController@userListByRoleId');
    Route::get('/getUserByUserId/{userId}', 'UserManagement\UserController@getUserByUserId');
    Route::get('/selected-farmers', 'UserManagement\UserController@getSelectedFarmers');
    Route::post('/store', 'UserManagement\UserController@store');
    Route::put('/update-user/{id}', 'UserManagement\UserController@updateUser');
    Route::post('/operator-store', 'UserManagement\UserController@operatorStore');
    Route::post('/ginner-grower-store', 'UserManagement\UserController@growerGinnerStore');
    Route::post('/store-warehouse', 'UserManagement\UserController@storeWarehouse');
    Route::delete('/destroy/{id}', 'UserManagement\UserController@destroy');
    Route::post('/destroy-user', 'UserManagement\UserController@destroyUser');
    Route::delete('/toggle-status/{id}', 'UserManagement\UserController@toggleStatus');
    Route::post('/change-password', 'UserManagement\UserController@changePassword');
    Route::post('/account-recovery', 'UserManagement\UserController@accountRecovery');
    Route::get('/account-validate', 'UserManagement\UserController@accountValidate');
    Route::get('/farmer-profile/{farmerId}', 'UserManagement\UserController@getFarmerProfile');
    Route::get('/farmer-details', 'UserManagement\UserController@getFarmerDetails');
    Route::get('/farmer-list', 'UserManagement\UserController@getFarmerList');
    Route::get('/user-list', 'UserManagement\UserController@getUserList');
    Route::post('/dashboard-user', 'UserManagement\UserController@dashboardUser');
    Route::get('/get-designations-wise-people', 'UserManagement\UserController@getDesignationsWisePeople');
    Route::get('/warehouse-user-list', 'UserManagement\UserController@getWarehouseUserList');
    Route::get('/farmerStatus/{farmerId}', 'UserManagement\UserController@getFarmerStatus');
    Route::get('/warehouse-farmer-status/{farmerId}', 'UserManagement\UserController@getWarehouseFarmerStatus');
    Route::post('/farmer-update', 'UserManagement\UserController@farmerUpdate');
    Route::get('/current-office-user/{office_id}', 'UserManagement\UserController@currentOfficeUser');
    Route::get('/supervisor/{user_id}', 'UserManagement\UserController@supervisor');
    Route::put('/update/{mobile_no}', 'UserManagement\UserController@update');
    Route::post('/training-user-create-update', 'UserManagement\UserExternalController@trainingUserCreateOrUpdate');
    Route::post('/external-user-create-update', 'UserManagement\UserExternalController@externalUserCreateOrUpdate');
    Route::delete('/dae-farmer-delete/{id}', 'UserManagement\UserController@daeFarmerDelete');
    Route::get('/total-warehouse-farmer', 'UserManagement\UserController@getTotalWarehouseFarmerNumber');
    Route::get('/super-admin', 'UserManagement\UserController@getSuperAdmin');

});



// WareService User Registration Approve routes
Route::group(['prefix'=>'/registration-approve'], function(){
    Route::get('/list', 'UserManagement\RegistrationApproveController@regRecommendationData');
    Route::put('/approveStatus/{id}', 'UserManagement\RegistrationApproveController@approveStatus');
    Route::put('/RejectStatus/{id}', 'UserManagement\RegistrationApproveController@rejectStatus');
});

// Farmer profile
Route::group(['prefix'=>'/warehouse-farmer'], function() {
    Route::get('/profile', 'UserManagement\FarmerProfileController');
    Route::put('/profile', 'UserManagement\FarmerProfileController');
});

// common profile
Route::group(['prefix'=>'/auth/common-profile-info'], function() {
    Route::post('/store', 'CommonProfile\CommonProfileController@store');
    Route::get('/list', 'CommonProfile\CommonProfileController@index');
});

Route::get('/user-detail-by-user-ids', function (\Illuminate\Http\Request $request)
{
    return \App\Library\CommonInfo::getUserDetailByUserIds($request->user_ids);
});

/** user for notification */
Route::get('/user/notification-user', 'UserController@notificationUser');

include "auth.php";
include "portal.php";
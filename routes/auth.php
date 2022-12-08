<?php
/**
 * Handle only auth user access
 * 
 * @author Md. Moktar Ali
 */
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::group(['middleware'  =>  'auth:api'], function () {
    Route::group(['prefix'=>'/auth'], function() {
        Route::get('/user-roles/{userId}', 'AuthController@userRoles');
        Route::get('/components-by-role/{roleId}', 'AuthController@componentsByRole');
        Route::post('logout', "AuthController@logout");
    });
});

/** External user common profile data */
Route::group(['prefix'=>'/external-user'], function() {
    Route::post('/common-profile', 'CommonProfile\CommonProfileController@storeCommonProfile');
    Route::get('/common-profile', 'CommonProfile\CommonProfileController@getCommonProfile');
});

/** Customizing lument passport */

Route::group(['prefix' => '/v1/oauth'], function () {
    Route::post('/token', 'AuthController@login');
});


<?php
/**
 * Handle only auth user access
 * 
 * @author Md. Md Mamunur Rashid
 */
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'/auth/portal', 'namespace'=>'Portal'], function() {
    Route::get('/get-register-users', 'FrontendController@TotalRegister');
    Route::get('/get-users', 'FrontendController@getUsers');
});

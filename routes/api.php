<?php

Route::group(['prefix' => 'v1'], function () {

    Route::get('/', function () {
        return response(['message'=> 'welcome to Emrad api version 1.0 :) ']);
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'RolesController@getRoles');
        Route::post('/', 'RolesController@createRole');
        Route::put('/{role}', 'RolesController@updateRole');
        Route::post('/{role}', 'RolesController@attachPermissions');
        Route::get('/{role}', 'RolesController@getActivePermissions');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', 'PermissionsController@getPermissions');
        Route::post('/', 'PermissionsController@createPermission');
        Route::put('/{permission}', 'PermissionsController@updatePermission');
    });

    Route::group(['prefix' => 'company'], function () {
        Route::get('/', 'CompaniesController@getCompanies');
        Route::post('/', 'CompaniesController@createCompany');
        Route::put('/{company}', 'CompaniesController@updateCompany');
        Route::get('/{company}', 'CompaniesController@getSingleCompany');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UsersController@getUser')->middleware(['auth:api']);
        Route::get('/details', 'UsersController@getUserDetails')->middleware(['auth:api']);
        Route::get('/profile', 'UsersController@getUser')->middleware(['auth:api', 'verified']);
        Route::any('/update/profile', 'UsersController@updateProfile');
        Route::get('/social-profile', 'UsersController@getSocialProfile')->middleware('auth:api');
        Route::put('/update/social-profile', 'UsersController@updateSocialProfile')->middleware(['auth:api', 'verified']);
        Route::post('/', 'UsersController@createUser')->middleware(['auth:api']);
        Route::delete('/{userId}', 'UsersController@deleteUser')->middleware(['auth:api', 'verified','permission:delete-user']);
    });
});


Route::post('/logout', 'AuthController@logout')->middleware('auth:api');
Route::post('/login', 'AuthController@login')->name('login');
Route::post('/register', 'AuthController@register');
Route::post('/resetpassword', 'AuthController@setPassword')->middleware('auth:api');

Route::post('/forgetpassword', 'ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'ResetPasswordController@reset');

Route::post('email/verify', 'VerificationApiController@verify')->name('verificationapi.verify');
Route::post('email/resend', 'VerificationApiController@resend')->middleware('auth:api')->name('verificationapi.resend');

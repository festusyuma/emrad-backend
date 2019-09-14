<?php

Route::group(['prefix' => 'v1'], function () {

    Route::get('/', function () {
        return response(['message'=> 'welcome to Emrad api version 1.0 :) ']);
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'RolesController@getRoles');
        Route::Post('/', 'RolesController@createRole');
        Route::put('/{role}', 'RolesController@updateRole');
        Route::post('/{role}', 'RolesController@attachPermissions');
        Route::get('/{role}', 'RolesController@getActivePermissions');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', 'PermissionsController@getPermissions');
        Route::Post('/', 'PermissionsController@createPermission');
        Route::put('/{permission}', 'PermissionsController@updatePermission');
    });
});


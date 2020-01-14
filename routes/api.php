<?php

Route::group(['prefix' => 'v1'], function () {

    Route::get('/', function () {
        return response(['message' => 'welcome to Emrad api version 1.0 :) ']);
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'RolesController@getRoles');
        Route::post('/', 'RolesController@createRole');
        Route::put('/{role}', 'RolesController@updateRole');
        Route::post('/{role}', 'RolesController@attachPermissions');
        Route::get('/{role}', 'RolesController@getActivePermissions');
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/', 'CategoriesController@getCategories');
        Route::get('/{category}', 'CategoriesController@getSingleCategory');
        Route::post('/', 'CategoriesController@createCategory');
        Route::put('/{category}', 'CategoriesController@updatecategory');
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

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'ProductsController@getProducts')->name('list-products');
        Route::get('/{product}', 'ProductsController@getSingleProduct')->name('product-detials');
        Route::post('/', 'ProductsController@createProduct')->middleware('auth:api');
        Route::put('/{product}', 'ProductsController@updateProduct')->middleware('auth:api');
    });

    Route::group(['prefix' => 'retail-orders'], function () {
        Route::get('/', 'RetailerOrderController@getAllRetailerOrders');
        Route::get('/{order_id}', 'RetailerOrderController@getSingleRetailerOrder');
        Route::post('/', 'RetailerOrderController@makeRetailerOrder')->middleware('auth:api');
        Route::patch('/confirm/{order_id}', 'RetailerOrderController@confirmRetailerOrder');

    });

    Route::group(['prefix' => 'retail-inventories'], function () {
        Route::get('/', 'RetailerInventoryController@getAllRetailerInventories');
        Route::get('/{inventory_id}', 'RetailerInventoryController@getSingleRetailerInventory');
        Route::patch('/update/{inventory_id}', 'RetailerInventoryController@updateRetailerInventory');
    });

    Route::group(['prefix' => 'retail-sales'], function () {
        Route::get('/', 'RetailerSaleController@getAllRetailerSales')->name('list-sales');
        Route::get('/{sale_id}', 'RetailerSaleController@getSingleRetailerSale')->name('get-sale');
    });

    Route::group(['prefix' => 'offers'], function () {
        Route::get('/', 'OfferController@getOffers')->name("list-offers");
        Route::post('/apply', 'OfferController@applyForOffer')->middleware("auth:api");
        Route::get('/mine', 'OfferController@myOffers')->middleware("auth:api");
        Route::get('/{offer}', 'OfferController@getSingleOffer');
        Route::post('/', 'OfferController@createOffer');
        Route::put('/{offer}', 'OfferController@updateOffer');
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

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

    Route::group(['prefix' => 'distributor', 'middleware' => ['auth:api', 'role:Distributor']], function () {
        Route::group(['prefix' => 'products'], function () {
            Route::post('/', 'Distributor\ProductsController@createProduct')->name('create-product');
            Route::get('/', 'ProductsController@getProducts')->name('list-products');
            Route::get('/{product}', 'ProductsController@getSingleProduct')->name('product-details');
            Route::put('/{product}', 'ProductsController@updateProduct');
        });
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'ProductsController@getProducts')->name('list-products');
        Route::get('/{product}', 'ProductsController@getSingleProduct')->name('product-details');
        Route::post('/', 'ProductsController@createProduct')->middleware(['auth:api', 'role:Distributor']);
        Route::put('/{product}', 'ProductsController@updateProduct')->middleware('auth:api');
    });

    Route::group(['prefix' => 'retail-orders'], function () {
        Route::get('/', 'RetailerOrderController@getAllRetailerOrders')->middleware('auth:api');
        Route::get('/total', 'RetailerOrderController@totalOrderPayment')->middleware('auth:api');
        Route::get('/{order_id}', 'RetailerOrderController@getSingleRetailerOrder')->middleware('auth:api');
        Route::post('/', 'RetailerOrderController@makeRetailerOrder')->middleware('auth:api');
        Route::get('/stock-balance/{product_id}', 'RetailerOrderController@getStockBalance')->middleware('auth:api');
        Route::patch('/confirm/{item_id}', 'RetailerOrderController@confirmRetailerOrder')->middleware('auth:api');

    });

    Route::group(['prefix' => 'retail-inventories'], function () {
        Route::get('', 'RetailerInventoryController@getAllRetailerInventories')->middleware('auth:api');
        Route::get('stock-history','RetailerInventoryController@getStockHistory')->middleware('auth:api');
        Route::get('{inventory_id}', 'RetailerInventoryController@getSingleRetailerInventory')->middleware('auth:api');
    });

    Route::group(['prefix' => 'retail-sales'], function () {
        Route::get('/inventory-list', 'RetailerSaleController@getInventoryList')->middleware('auth:api');
        Route::get('/', 'RetailerSaleController@getAllRetailerSales')->middleware('auth:api');
        Route::get('/{sale_id}', 'RetailerSaleController@getSingleRetailerSale')->middleware('auth:api');
        Route::post('/', 'RetailerSaleController@makeRetailerSale')->middleware('auth:api');

    });

    Route::group(['prefix' => 'offers'], function () {
        Route::get('/', 'OfferController@getOffers')->name("list-offers")->middleware("auth:api");
        Route::post('/apply', 'OfferController@applyForOffer')->middleware("auth:api");
        Route::get('/mine', 'OfferController@myOffers')->middleware("auth:api");
        Route::get('/{offer}', 'OfferController@getSingleOffer')->middleware("auth:api");
        Route::post('/', 'OfferController@createOffer')->middleware("auth:api");
        Route::put('/{offer}', 'OfferController@updateOffer')->middleware("auth:api");
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', 'DashboardController@getDashboardStats')->middleware('auth:api');
    });

    Route::group(['prefix' => 'wallet', 'middleware' => 'auth:api'], function () {
        Route::get('/', 'WalletController@getBalance');
        Route::put('/', 'WalletController@addCard');
        Route::post('/credit', 'WalletController@creditWallet');
        Route::get('/card', 'WalletController@getCards');
        Route::get('/transaction', 'WalletController@getHistory');
        Route::get('/transaction/{id}', 'WalletController@getTransaction');
    });

});


Route::post('/logout', 'AuthController@logout')->middleware('auth:api');
Route::post('/login', 'AuthController@login')->name('login');
Route::post('/register', 'AuthController@register');
Route::post('/resetpassword', 'AuthController@setPassword')->middleware('auth:api');

Route::post('/forgetpassword', 'ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'ResetPasswordController@reset');

Route::post('email/verify/', 'VerificationApiController@verify')->name('verificationapi.verify');
Route::post('email/resend', 'VerificationApiController@resend')->middleware('auth:api')->name('verificationapi.resend');

Route::group(['prefix' => 'webhook'], function () {
    Route::post('/transaction', 'WebhookController@transaction');
});

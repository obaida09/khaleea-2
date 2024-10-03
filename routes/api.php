<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\FrontEnd;
// use App\Http\Controllers\auth\admin;
use App\Http\Controllers\auth\user;
use App\Http\Controllers\auth\shop;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::get('/login', function () {
    return response()->json([
        'message' => 'Unauthenticated. Please log in to access this resource.',
    ], 401);
})->name('login');

Route::post('/user/register', user\RegisterController::class)->name('user.register');
Route::post('/user/login', user\LoginController::class)->name('user.login');
Route::get('/user/checkToken', user\CheckTokenController::class);
Route::post('/user/logout', user\LogoutController::class)->name('user.logout');

Route::post('/shop/register', shop\RegisterController::class)->name('shop.register');
Route::post('/shop/login', shop\LoginController::class)->name('shop.login');

Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{roleId}', [RoleController::class, 'show']);

Route::get('/users/{user}/permissions', [RoleController::class, 'showUserPermissions']);

Route::get('/permissions', [RoleController::class, 'getPermissions']);
Route::post('/roles/{roleId}/assign-permission', [RoleController::class, 'addPermissionToRole']);

Route::post('/roles', [RoleController::class, 'create']);
Route::put('/roles/{roleId}', [RoleController::class, 'update']);
Route::post('/users/{userId}/assign-role', [RoleController::class, 'assignRole']);
Route::post('/users/{userId}/remove-role', [RoleController::class, 'removeRole']);

Route::group(['middleware' => ['auth:api']], function () {

    /*
        ------
        Routes for Admin Panel
        ------
    */
    Route::apiResource('users', Admin\UserController::class);
    Route::apiResource('categories', Admin\CategoryController::class);
    Route::apiResource('tags', Admin\TagController::class);
    Route::apiResource('products', Admin\ProductController::class);
    Route::apiResource('coupons', Admin\CouponController::class);
    Route::apiResource('orders', Admin\OrderController::class);
    Route::apiResource('colors', Admin\ColorsController::class);
    Route::apiResource('sizes', Admin\SizesController::class);
    Route::apiResource('posts', Admin\PostController::class);

    Route::post('/users/{user}/points/add', [Admin\PointController::class, 'addPoints']);

    /*
        ------
        Routes for Front end
        ------
    */
    Route::get('/verticalPage', [FrontEnd\PagesController::class, 'vertical']);
    Route::get('/horizontalPage', [FrontEnd\PagesController::class, 'horizontal']);

    Route::get('/user/profile', [FrontEnd\UserDetailsController::class, 'profile']);
    Route::put('/user/profile', [FrontEnd\UserDetailsController::class, 'updateProfile']);

    Route::get('/user/orders', [FrontEnd\OrderController::class, 'userOrders']);
    Route::get('/user/order/{id}', [FrontEnd\OrderController::class, 'showOrder']);
    Route::post('/user/orders', [FrontEnd\OrderController::class, 'store']);

    Route::get('/product/{slug}', [FrontEnd\ProductController::class, 'show']);

    Route::apiResource('carts', FrontEnd\CartController::class);
});

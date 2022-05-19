<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('social-login',[AuthController::class, 'socialLogin']);

Route::middleware(["auth:api"])->group(function () {
    // profile
    Route::get('logout',[AuthController::class, 'logout']);
    Route::get('logout-all-devices',[AuthController::class, 'logoutAllDevices']);
    Route::post('update-profile',[UserController::class, 'updateProfile']);

    // posts
    Route::get('user-posts',[PostController::class, 'userPosts']);
    Route::apiResource('posts', PostController::class);

    // tags
    Route::apiResource('tags', TagController::class);

    // Category
    Route::apiResource('categories', CategoryController::class);

});



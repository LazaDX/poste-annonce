<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;

Route::get('/users/count', [UserController::class, 'getTotalUsers']);
Route::get('/users/by-month', [UserController::class, 'getUsersByMonth']);
Route::apiResource('users', UserController::class);
Route::apiResource('admins', AdminController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('favorites', FavoriteController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/favorites/posts', [FavoriteController::class, 'getUserFavoritesPosts']);
});
Route::apiResource('images', ImageController::class);
Route::get('/posts/count', [PostController::class, 'getTotalPosts']);
Route::get('/posts/by-month', [PostController::class, 'getPostsByMonth']);
Route::apiResource('posts', PostController::class);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/visits/count', [AuthController::class, 'getVisitCount']);
Route::post('/auth/logout', [AuthController::class, 'logout']);


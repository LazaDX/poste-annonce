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
Route::apiResource('users', UserController::class);
Route::apiResource('admins', AdminController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('favorites', FavoriteController::class);
Route::apiResource('images', ImageController::class);
Route::get('/posts/count', [PostController::class, 'getTotalPosts']);
Route::apiResource('posts', PostController::class);

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);


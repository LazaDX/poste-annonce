<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


Route::post('/api/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/sanctum/csrf-cookie', function() {
    return response()->json(['message' => 'CSRF token set!']);
});

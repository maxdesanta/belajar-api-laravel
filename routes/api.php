<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\UserPostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserPostController::class, 'register']);
Route::post('/login', [UserPostController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [UserPostController::class, 'logout']);
    Route::apiResource('posts', PostController::class); 
});
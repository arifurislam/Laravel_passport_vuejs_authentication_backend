<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function(){
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/signup',[AuthController::class,'signUp']);
    Route::post('/forgot',[ForgotPasswordController::class,'forgotPassword']);
    Route::post('/reset',[ForgotPasswordController::class,'resetPassword']);

    Route::get('/login',function(){
        return response()->json([
            'message' => 'Unauthenticated',
        ],401);
    })->name('login');

    Route::middleware('auth:api')->group(function(){
        Route::post('/logout',[AuthController::class,'logout']);
    });
});
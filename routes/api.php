<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\InvoiceController;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\TesteController;


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('v1')->group(function () {
    Route::get('users', [UserController::class, 'index']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::apiResource('invoices', InvoiceController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::get('/teste', [TesteController::class, 'index']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });


});


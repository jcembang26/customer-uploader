<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

Route::post('/create-token', [TokenController::class, 'createToken']);
// Route::middleware('auth:api')->group(function () {
    Route::get('/customers', [CustomerController::class, 'list']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
// });
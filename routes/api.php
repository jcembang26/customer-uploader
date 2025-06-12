<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

Route::get('/customers', [CustomerController::class, 'list']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
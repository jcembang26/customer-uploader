<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/customers', [CustomerController::class, 'list']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
<?php

use App\Api\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::controller(UsersController::class)->group(function (){
    Route::post('/', 'createCustomer');
    Route::get('/me', 'get')->middleware('auth:sanctum');
});

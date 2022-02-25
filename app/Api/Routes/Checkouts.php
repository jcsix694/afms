<?php

use App\Api\Controllers\CheckoutsController;
use Illuminate\Support\Facades\Route;

Route::controller(CheckoutsController::class)->middleware('auth:sanctum')->group(function (){
    Route::post('/', 'create')->middleware('auth:sanctum');
    Route::get('/', 'get')->middleware('auth:sanctum');
});

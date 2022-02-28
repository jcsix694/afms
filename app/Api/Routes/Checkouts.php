<?php

use App\Api\Controllers\CheckoutsController;
use Illuminate\Support\Facades\Route;

Route::controller(CheckoutsController::class)->middleware('auth:sanctum')->group(function (){
    Route::post('/', 'create');
    Route::get('/', 'getAll');
    Route::get('/{id}', 'getById');
});

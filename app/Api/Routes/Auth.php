<?php

use App\Api\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function (){
    Route::post('/', 'authenticate');
});

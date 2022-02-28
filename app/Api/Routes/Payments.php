<?php

use App\Api\Controllers\CheckoutsController;
use App\Api\Controllers\PaymentsController;
use Illuminate\Support\Facades\Route;

Route::controller(PaymentsController::class)->middleware('auth:sanctum')->group(function (){
    Route::post('/refund', 'refund');
});

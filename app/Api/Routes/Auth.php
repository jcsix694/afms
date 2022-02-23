<?php

use App\Api\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/authenticate', function (Request $request) {
    dd(2324);
});

Route::post('/authenticate', [AuthController::class, 'test']);

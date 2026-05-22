<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/statistics', [App\Http\Controllers\StatisticsController::class, 'dashboard']);

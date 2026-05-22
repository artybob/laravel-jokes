<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JokeController;

Route::get('/jokes', [JokeController::class, 'index']);
Route::get('/jokes/types', [JokeController::class, 'types']);

Route::post('/track', [App\Http\Controllers\StatisticsController::class, 'track']);
Route::get('/stats', [App\Http\Controllers\StatisticsController::class, 'stats']);

<?php
use App\Http\Controllers\JokeController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::get('/jokes', [JokeController::class, 'index']);
Route::get('/jokes/types', [JokeController::class, 'types']);
Route::post('/track', [StatisticsController::class, 'track']);

Route::middleware('basic.auth')->group(function () {
    Route::get('/stats', [StatisticsController::class, 'stats']);
});

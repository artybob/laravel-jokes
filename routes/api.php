<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JokeController;

Route::get('/jokes', [JokeController::class, 'index']);
Route::get('/jokes/types', [JokeController::class, 'types']);

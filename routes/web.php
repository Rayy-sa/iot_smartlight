<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController; // Pastikan ada \Api\ di sini

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk UI Dashboard
Route::get('/dashboard', [SensorController::class, 'index']);

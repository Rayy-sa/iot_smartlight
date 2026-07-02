<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController; // Pastikan ada \Api\ di sini
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

// Rute utama untuk UI Dashboard
Route::get('/dashboard', [SensorController::class, 'index']);

// Rute darurat untuk membersihkan cache Railway
Route::get('/clear-cache', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return 'Semua cache berhasil dibersihkan! Silakan buka /dashboard kembali.';

});

Route::get('/ping', function () {
    return 'PONG! File web.php berhasil di-update di Railway!';
});

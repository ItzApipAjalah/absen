<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('admin.dashboard');
    });

    // Petugas Routes
    Route::middleware(['role:petugas'])->prefix('petugas')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Petugas\DashboardController::class, 'index'])
            ->name('petugas.dashboard');
    });

    Route::post('/jadwal/{jadwal}/check-in', [AbsenController::class, 'checkIn'])->name('absen.check-in');
    Route::post('/jadwal/{jadwal}/check-out', [AbsenController::class, 'checkOut'])->name('absen.check-out');

    // Admin & Petugas Routes
    Route::middleware(['role:admin,petugas'])->group(function () {
        Route::resource('jadwal', JadwalController::class);
        Route::resource('lokasi', LokasiController::class);
        Route::get('/lokasi/{lokasi}/assign', [LokasiController::class, 'assign'])->name('lokasi.assign');
        Route::post('/lokasi/{lokasi}/assign-petugas', [LokasiController::class, 'assignPetugas'])->name('lokasi.assign-petugas');
        Route::resource('users', UserController::class);
    });
});

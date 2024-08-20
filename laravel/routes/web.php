<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\AdminController;
use App\Http\Controllers\NetworkController;

Route::post('/prelogin', [AdminController::class, 'prelogin']);
Route::post('/login', [AdminController::class, 'login']);
Route::post('/logout', [AdminController::class, 'logout']);
Route::post('/stat', [AdminController::class, 'stat']);
Route::post('/users', [AdminController::class, 'users']);
Route::post('/start_dump', [AdminController::class, 'startDump']);
Route::post('/stop_dump', [AdminController::class, 'stopDump']);
Route::post('/drop', [AdminController::class, 'drop']);
Route::post('/kill', [AdminController::class, 'kill']);
Route::get('/', [NetworkController::class, 'showMain'])->name('main');
Route::get('/users', [NetworkController::class, 'showUsers'])->name('users');
Route::get('/users/pppoe', [NetworkController::class, 'showPppoe'])->name('pppoe');
Route::get('/users/ipoe', [NetworkController::class, 'showIpoe'])->name('ipoe');
Route::get('/logs', [NetworkController::class, 'showLogs'])->name('logs');
Route::get('/logout', [NetworkController::class, 'logout'])->name('logout');
require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grupo de rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas relacionadas ao administrador
Route::prefix('admin')->group(function () {
    Route::post('/prelogin', [AdminController::class, 'prelogin'])->name('admin.prelogin');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::post('/stat', [AdminController::class, 'stat'])->name('admin.stat');
    Route::post('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/start_dump', [AdminController::class, 'startDump'])->name('admin.start_dump');
    Route::post('/stop_dump', [AdminController::class, 'stopDump'])->name('admin.stop_dump');
    Route::post('/drop', [AdminController::class, 'drop'])->name('admin.drop');
    Route::post('/kill', [AdminController::class, 'kill'])->name('admin.kill');
});

// Rotas relacionadas à rede
Route::prefix('network')->middleware('auth')->group(function () {
    Route::get('/dashboard/main', [NetworkController::class, 'showMain'])->name('network.main');
    Route::get('/users', [NetworkController::class, 'showUsers'])->name('network.users');
    Route::get('/users/pppoe', [NetworkController::class, 'showPppoe'])->name('network.pppoe');
    Route::get('/users/ipoe', [NetworkController::class, 'showIpoe'])->name('network.ipoe');
    Route::get('/logs', [NetworkController::class, 'showLogs'])->name('network.logs');
    Route::get('/logout', [NetworkController::class, 'logout'])->name('network.logout');
});


Route::get('/routes', function () {
    return response()->json(
        collect(Route::getRoutes())->map(function ($route) {
            return [
                'uri' => $route->uri,
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'method' => implode('|', $route->methods()),
            ];
        })
    );
});
require __DIR__.'/auth.php';

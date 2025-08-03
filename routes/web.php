<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController; // Importa tu controlador
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        //'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('profile.edit');

// Rutas para los reportes
    // Solo 'ingenieros' y 'admins' pueden crear/almacenar reportes
    Route::middleware(['role:admin,ingeniero'])->group(function () {
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    });

    // Ejemplo: Rutas para que los 'medicos' puedan ver sus equipos
    Route::middleware(['role:admin,medico'])->group(function () {
        Route::get('/my-equipment', [ReportController::class, 'myEquipment'])->name('my-equipment'); // Necesitarás crear este método
    });

    // Ejemplo: Rutas para que los 'medicos' puedan solicitar mantenimiento
    Route::middleware(['role:admin,medico'])->group(function () {
        Route::get('/maintenance-requests/create', [ReportController::class, 'createMaintenanceRequest'])->name('maintenance-requests.create');
        Route::post('/maintenance-requests', [ReportController::class, 'storeMaintenanceRequest'])->name('maintenance-requests.store');
    });

    // Ejemplo: Rutas para que los 'ingenieros' y 'admins' puedan ver solicitudes de mantenimiento
    Route::middleware(['role:admin,ingeniero'])->group(function () {
        Route::get('/maintenance-requests', [ReportController::class, 'indexMaintenanceRequests'])->name('maintenance-requests.index');
    });


require __DIR__.'/auth.php';
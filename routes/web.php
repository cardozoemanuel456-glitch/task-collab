<?php

use App\Http\Controllers\DarkModeController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// 1. RUTA RAÍZ PÚBLICA: Muestra la Landing Page de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// 2. PANEL DE CONTROL INTERNO PROTEGIDO (Manejado por PaginaController)
// Mantenemos '/dashboard' (requerido por Breeze) y '/inicio' como alias por si tenés enlaces viejos que lo usen
Route::get('/dashboard', [PaginaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/inicio', [PaginaController::class, 'index'])
    ->middleware(['auth'])
    ->name('inicio');

// 3. MÓDULO DE CONFIGURACIÓN (Dark Mode)
Route::post('/dark-mode/toggle', [DarkModeController::class, 'toggle'])
    ->middleware('auth')
    ->name('dark-mode.toggle');

// 4. MÓDULO DE PERFIL DE USUARIO (Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. CORAZÓN DE TASK-COLLAB: Páginas y Tareas
Route::middleware('auth')->group(function () {

    // --- RUTAS DE PÁGINAS ---
    Route::get('/paginas', [PaginaController::class, 'index'])->name('paginas.index');
    Route::post('/paginas', [PaginaController::class, 'store'])->name('paginas.store');
    Route::get('/paginas/{pagina}', [PaginaController::class, 'show'])->name('paginas.show');
    Route::patch('/paginas/{pagina}', [PaginaController::class, 'update'])->name('paginas.update');
    Route::delete('/paginas/{pagina}', [PaginaController::class, 'destroy'])->name('paginas.destroy');

    // --- RUTAS DE TAREAS ---
    Route::post('/tareas', [TaskController::class, 'store'])->name('tareas.store');
    Route::patch('/tareas/{task}', [TaskController::class, 'update'])->name('tareas.update');
    Route::delete('/tareas/{task}', [TaskController::class, 'destroy'])->name('tareas.destroy');

});

// 6. ENTORNO DE PRUEBAS
Route::get('/prueba', function () {
    return view('prueba');
})->middleware('auth');

// 7. RUTAS DE AUTENTICACIÓN DE BREEZE
require __DIR__.'/auth.php';

// Ruta para aceptar invitación por enlace (público)
Route::get('/invitar/aceptar/{token}', [InvitationController::class, 'acceptByToken'])
    ->name('paginas.invitar.accept');

// Ruta para aceptar invitación por código (solo usuarios logueados)
Route::middleware(['auth'])->group(function () {
    Route::post('/invitar/aceptar-codigo', [InvitationController::class, 'acceptByCode'])
        ->name('paginas.invitar.accept.code');
});

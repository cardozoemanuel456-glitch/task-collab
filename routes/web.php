<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DarkModeController;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// 1. RUTA DE INICIO: Redirige automáticamente al espacio de trabajo principal
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// 2. RUTA GENERAL PROTEGIDA (El Dashboard principal ahora lo maneja PaginaController)
Route::get('/dashboard', [PaginaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. MÓDULO DE CONFIGURACIÓN (Mantenemos tu Dark Mode intacto)
Route::post('/dark-mode/toggle', [DarkModeController::class, 'toggle'])
    ->middleware('auth')
    ->name('dark-mode.toggle');

// 4. MÓDULO DE PERFIL DE USUARIO (Rutas nativas de Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. CORAZÓN DE TASK-COLLAB: Gestión de Páginas (Estilo Notion) y Tareas asociadas
Route::middleware('auth')->group(function () {
    
    // --- RUTAS DE PÁGINAS ---
    // Listar páginas en el sistema
    Route::get('/paginas', [PaginaController::class, 'index'])->name('paginas.index');
    // Crear una nueva página o subpágina
    Route::post('/paginas', [PaginaController::class, 'store'])->name('paginas.store');
    // Ver una página específica con su lista de tareas instalada
    Route::get('/paginas/{pagina}', [PaginaController::class, 'show'])->name('paginas.show');
    // Eliminar una página (borra sus tareas y subpáginas en cascada)
    Route::delete('/paginas/{pagina}', [PaginaController::class, 'destroy'])->name('paginas.destroy');

    // --- RUTAS DE TAREAS ---
    // Crear una tarea dentro de la página activa
    Route::post('/tareas', [TaskController::class, 'store'])->name('tareas.store');
    // Cambiar el estado de la tarea (To-Do <-> Done) usando tu lógica dinámica PATCH
    Route::patch('/tareas/{task}', [TaskController::class, 'update'])->name('tareas.update');
    // Eliminar una tarea de la lista
    Route::delete('/tareas/{task}', [TaskController::class, 'destroy'])->name('tareas.destroy');
    
});

// 6. ENTORNO DE PRUEBAS (Por si la siguen usando para maquetar componentes)
Route::get('/prueba', function () {
    return view('prueba');
})->middleware('auth');
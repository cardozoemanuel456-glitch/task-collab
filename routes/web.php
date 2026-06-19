<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DarkModeController;
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/dark-mode/toggle', [DarkModeController::class, 'toggle'])->name('dark-mode.toggle');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

Route::post('/connect-board', function (Illuminate\Http\Request $request) {
    // Guardamos en la sesión a qué tablero nos queremos conectar
    if ($request->team_id) {
        session(['current_team_id' => $request->team_id]);
    } else {
        session()->forget('current_team_id'); // Vuelve a su propio tablero
    }
    return back();
})->name('board.connect');

    Route::get('/prueba', function () {
    return view('prueba');
    })->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';

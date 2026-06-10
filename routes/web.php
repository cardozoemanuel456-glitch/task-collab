<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('workspaces', [WorkspaceController::class, 'index'])->name('workspaces.index');
Route::get('workspaces/create', [WorkspaceController::class, 'create'])->name('workspaces.create')->middleware('auth');
Route::post('workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store')->middleware('auth');
Route::get('workspaces/{workspace}', [WorkspaceController::class, 'show'])->name('workspaces.show')->middleware('auth');

require __DIR__.'/auth.php';

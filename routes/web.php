<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\KeywordResearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return redirect()->route('login');
});

// User Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::get('/projects/index', [ProjectController::class, 'index'])->name('projects.index');
    Route::delete('/projects', [ProfileController::class, 'destroy'])->name('projects.destroy');
    Route::patch('/projects/{project}/reanalyze', [ProjectController::class, 'reanalyze'])->name('projects.reanalyze');    
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    // Global Keyword Research Tool (project-independent)
    Route::prefix('keywords')->name('keywords.')->group(function () {
        Route::get('/research', [KeywordResearchController::class, 'index'])->name('research');
        Route::post('/search', [KeywordResearchController::class, 'search'])->name('search');
        Route::get('/popular', [KeywordResearchController::class, 'popular'])->name('popular');
        Route::get('/suggestions', [KeywordResearchController::class, 'suggestions'])->name('suggestions');
        Route::post('/export', [KeywordResearchController::class, 'export'])->name('export');
    });
});

// Admin Dashboard
Route::middleware(['auth', 'verified', CheckRole::class.':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management Routes
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Admin User Creation Routes
        Route::get('/users/create/admin', [AdminUserController::class, 'create'])->name('users.create-admin');
        Route::post('/users/create/admin', [AdminUserController::class, 'store'])->name('users.store-admin');
    });

require __DIR__.'/auth.php';

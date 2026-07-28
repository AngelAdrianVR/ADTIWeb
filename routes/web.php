<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing Pages
Route::get('/', [LandingController::class, 'splash'])->name('splash');
Route::get('/inicio', [LandingController::class, 'inicio'])->name('inicio');
Route::get('/servicios', [LandingController::class, 'servicios'])->name('servicios');
Route::get('/proyectos', [LandingController::class, 'proyectos'])->name('proyectos');
Route::get('/nosotros', [LandingController::class, 'nosotros'])->name('nosotros');
Route::get('/contacto', [LandingController::class, 'contacto'])->name('contacto');

// Legal Pages
Route::get('/terminos', [LandingController::class, 'terms'])->name('terms.show');
Route::get('/politica-privacidad', [LandingController::class, 'policy'])->name('policy.show');

// Authenticated Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

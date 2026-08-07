<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ContactoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

// Landing Pages
Route::get('/', [LandingController::class, 'splash'])->name('splash');

Route::middleware('track.visits')->group(function () {
    Route::get('/inicio', [LandingController::class, 'inicio'])->name('inicio');
    Route::get('/servicios', [LandingController::class, 'servicios'])->name('servicios');
    Route::get('/proyectos', [LandingController::class, 'proyectos'])->name('proyectos');
    Route::get('/nosotros', [LandingController::class, 'nosotros'])->name('nosotros');
    Route::get('/contacto', [LandingController::class, 'contacto'])->name('contacto');
});

// Contact form
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

// Legal Pages
Route::middleware('track.visits')->group(function () {
    Route::get('/terminos', [LandingController::class, 'terms'])->name('terms.show');
    Route::get('/politica-privacidad', [LandingController::class, 'policy'])->name('policy.show');
});

// Authenticated Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // ── Admin: Landing Content Management ──
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('servicios', \App\Http\Controllers\Admin\ServicioController::class)
            ->except(['show', 'create', 'edit']);
        Route::resource('proyectos', \App\Http\Controllers\Admin\ProyectoController::class)
            ->except(['show', 'create', 'edit']);
        Route::resource('certificaciones', \App\Http\Controllers\Admin\CertificacionController::class)
            ->except(['show', 'create', 'edit']);
        Route::get('mensajes', [\App\Http\Controllers\Admin\MensajeController::class, 'index'])->name('mensajes.index');
        Route::delete('mensajes/{contacto}', [\App\Http\Controllers\Admin\MensajeController::class, 'destroy'])->name('mensajes.destroy');
        Route::get('notificaciones', [\App\Http\Controllers\Admin\NotificacionController::class, 'index'])->name('notificaciones.index');
        Route::post('notificaciones/read-all', [\App\Http\Controllers\Admin\NotificacionController::class, 'readAll'])->name('notificaciones.read-all');
        Route::post('notificaciones/{notificacion}/read', [\App\Http\Controllers\Admin\NotificacionController::class, 'read'])->name('notificaciones.read');
        Route::delete('notificaciones/{notificacion}', [\App\Http\Controllers\Admin\NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
    });
});

// eliminacion de archivos global
Route::delete('/media/{media}', function (Media $media) {
    try {
        $media->delete(); // Elimina el archivo y su registro

        return response()->json(['message' => 'Archivo eliminado correctamente.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al eliminar el archivo.'], 500);
    }
})->name('media.delete-file');

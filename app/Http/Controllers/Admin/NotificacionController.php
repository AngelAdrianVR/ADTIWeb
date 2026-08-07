<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    public function index()
    {
        return response()->json([
            'count' => Notificacion::unread()->count(),
            'notificaciones' => Notificacion::orderByDesc('created_at')->limit(15)->get()
                ->map(fn($n) => array_merge($n->toArray(), [
                    'is_unread' => $n->read_at === null,
                ])),
        ]);
    }

    public function read(Notificacion $notificacion)
    {
        $notificacion->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function readAll()
    {
        Notificacion::unread()->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function destroy(Notificacion $notificacion)
    {
        $notificacion->delete();

        return response()->json(['ok' => true]);
    }
}

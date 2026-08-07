<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use Inertia\Inertia;

class MensajeController extends Controller
{
    public function index()
    {
        // Marca los mensajes como leídos al abrir la sección
        Contacto::where('is_read', false)->update(['is_read' => true]);
        \App\Models\Notificacion::where('type', 'mensaje')->whereNull('read_at')
            ->update(['read_at' => now()]);

        return Inertia::render('Admin/Mensajes', [
            'mensajes' => Contacto::orderByDesc('created_at')->get(),
        ]);
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Contacto::where('is_read', false)->count(),
        ]);
    }

    public function destroy(Contacto $contacto)
    {
        \App\Models\Notificacion::where('type', 'mensaje')
            ->where('related_id', $contacto->id)->delete();

        $contacto->delete();

        return redirect()->back()->with('success', 'Mensaje eliminado.');
    }
}

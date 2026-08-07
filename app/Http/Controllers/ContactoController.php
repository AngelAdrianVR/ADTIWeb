<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => ['required', 'string', 'max:120'],
            'telefono'    => ['required', 'string', 'max:25', 'regex:/^[+]?[\d\s()-]{7,25}$/'],
            'correo'      => ['required', 'email', 'max:180'],
            'descripcion' => ['required', 'string', 'max:2000'],
        ], [
            'nombre.required'      => 'El nombre es obligatorio.',
            'telefono.required'    => 'El teléfono es obligatorio.',
            'telefono.regex'       => 'Ingresa un número de teléfono válido.',
            'correo.required'      => 'El correo electrónico es obligatorio.',
            'correo.email'         => 'Ingresa un correo electrónico válido.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max'      => 'La descripción no debe exceder los 2000 caracteres.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contacto = Contacto::create($validator->validated());

        \App\Models\Notificacion::create([
            'type' => 'mensaje',
            'title' => 'Nuevo mensaje de contacto',
            'description' => $contacto->nombre . ' (' . $contacto->correo . ')',
            'route_name' => 'admin.mensajes.index',
            'route_params' => null,
            'related_id' => $contacto->id,
        ]);

        return back()->with('contact_success', true);
    }
}

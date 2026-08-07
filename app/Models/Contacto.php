<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'descripcion',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}

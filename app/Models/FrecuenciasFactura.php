<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrecuenciasFactura extends Model
{
    use HasFactory;
    protected $table = "frecuencias_facturas";// <-- El nombre personalizado

    protected $fillable = [
        'dias',
        'descripcion',
        'estado',
    ];
    
}

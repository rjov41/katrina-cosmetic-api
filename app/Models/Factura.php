<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cliente_id',
        'monto',
        'nruc',
        'fecha_vencimiento',
        'iva',
        // 'tcambio',
        // 'monto_cambio',
        'tipo_venta',
        'status_pagado',
        'status',
    ];



    // one to many inversa
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // one to many inversa 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // one to many
    public function factura_detalle()
    {
        return $this->hasMany(Factura_Detalle::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaContado extends Model
{
    use HasFactory;

    protected $fillable = [
        'devolucion_factura_id',
        'monto',
        "estado",
    ];

    public function devolucion_factura()
    {
        return $this->hasMany(DevolucionFactura::class);
    }
}

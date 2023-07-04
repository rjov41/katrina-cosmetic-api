<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TazaCambioFactura extends Model
{
    use HasFactory;
    protected $table = "taza_cambio_facturas";// <-- El nombre personalizado

    protected $fillable = [
        'factura_id',
        'monto',
        'estado',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}

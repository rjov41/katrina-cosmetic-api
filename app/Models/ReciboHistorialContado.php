<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciboHistorialContado extends Model
{
    use HasFactory;
    protected $table = "recibo_historial_contado";// <-- El nombre personalizado
    protected $fillable = [
        'numero',
        'recibo_id',
        'factura_id',
        'rango',
        'estado',
    ];


    // one to many inversa
    public function recibo()
    {
        return $this->belongsTo(Recibo::class);
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }


}

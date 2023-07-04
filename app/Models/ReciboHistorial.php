<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciboHistorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'recibo_id',
        'factura_historial_id',
        'rango',
        'estado',
    ];


    // one to many inversa
    public function recibo()
    {
        return $this->belongsTo(Recibo::class);
    }

    // one to many inversa
    public function factura_historial()
    {
        return $this->belongsTo(FacturaHistorial::class);
    }

    // public function factura_historial()
    // {
    //     return $this->hasMany(FacturaHistorial::class);
    // }
}

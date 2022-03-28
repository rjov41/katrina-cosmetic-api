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
        'estado',
    ];


    // one to many inversa
    public function recibo()
    {
        return $this->belongsTo(Recibo::class);
    }

}

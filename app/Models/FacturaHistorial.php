<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaHistorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'user_id',
        'precio',
        'debitado',
        'estado',
    ];

    // one to many
    // public function factura()
    // {
    //     return $this->belongsTo(Factura::class);
    // }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevolucionFactura extends Model
{
    use HasFactory;
    protected $table = "devolucion_facturas"; // <-- El nombre personalizado

    protected $fillable = [
        'factura_id',
        'user_id',
        "descripcion",
        "estado",
    ];


    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    // one to many inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

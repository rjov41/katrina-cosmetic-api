<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevolucionProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "factura_detalle_id",
        "descripcion",
        "cantidad",
        "user_id",
        "estado",
    ];

    // one to many
    public function factura_detalle()
    {
        return $this->belongsTo(Factura_Detalle::class);
    }

    // one to many inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

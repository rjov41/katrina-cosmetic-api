<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaContadoProducto extends Model
{
    use HasFactory;
    protected $fillable = [
        'devolucion_producto_id',
        'monto',
        "estado",
    ];

    public function devolucion_productos()
    {
        return $this->hasMany(DevolucionProducto::class);
    }

}

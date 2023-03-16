<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura_Detalle extends Model
{
    protected $table = "factura_detalles";// <-- El nombre personalizado
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'factura_id',
        'cantidad',
        'precio',
        'precio_unidad',
        'estado',
        // 'porcentaje',
    ];


    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function devolucion_producto()
    {
        return $this->belongsTo(Factura::class);
    }

    public function regaloFacturado()
    {
        return $this->hasMany(RegalosFacturados::class,"factura_detalle_id");
    }

}

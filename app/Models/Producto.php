<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'marca',
        'modelo',
        'stock',
        'minimo',
        'precio',
        'comision',
        'linea',
        'descripcion',
        'estado',
    ];

    public function factura_detalle()
    {
        return $this->hasMany(Factura_Detalle::class);
    }


}

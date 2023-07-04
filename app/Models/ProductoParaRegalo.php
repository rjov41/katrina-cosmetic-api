<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoParaRegalo extends Model
{
    use HasFactory;

    protected $fillable = [
        "producto_id",
        "cantidad",
        "id_producto_regalo",
        "habilitado",
        "estado",
    ];


    public function regaloFacturado()
    {
        return $this->hasMany(RegalosFacturados::class);
    }
}

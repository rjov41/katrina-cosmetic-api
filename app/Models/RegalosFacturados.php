<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegalosFacturados extends Model
{
    use HasFactory;

    protected $table = "regalos_facturados"; // <-- El nombre personalizado
    
    protected $fillable = [
        "factura_detalle_id",
        "cantidad_regalada",
        "regalo_id",
        "estado",
    ];

    public function regalo()
    {
        return $this->hasOne(ProductoParaRegalo::class,"id","regalo_id");
    }

}

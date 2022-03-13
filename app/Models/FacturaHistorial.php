<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaHistorial extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'factura_id',
        'user_id',
        'precio',
        'estado',
    ];
    
    // one to many
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}

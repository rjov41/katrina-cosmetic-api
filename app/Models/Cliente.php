<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'categoria_id',
        'nombre',
        'celular',
        'telefono',
        'direccion_casa',
        'direccion_negocio',
        'cedula',
        'dias_cobro',
        'estado',
    ];
    
    // one to many
    public function factura()
    {
        return $this->hasMany(Factura::class);
    }

    // one to many
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}

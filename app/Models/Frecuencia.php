<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frecuencia extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'descripcion',
        'dias',
        'estado',
    ];
    
    // one to many
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
    
}

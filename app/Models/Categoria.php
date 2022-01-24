<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tipo',
        'descrripcion',
        'estado',
    ];
    

    public function cliente()
    {
        return $this->hasMany(Cliente::class);
    }
    
}
    

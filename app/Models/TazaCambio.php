<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TazaCambio extends Model
{
    use HasFactory;
    protected $table = "taza_cambios";// <-- El nombre personalizado
    protected $fillable = [
        'monto',
        'user_id',
        'estado',
    ];

}

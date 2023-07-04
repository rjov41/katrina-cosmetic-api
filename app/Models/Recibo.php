<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;

    protected $fillable = [
        'min',
        'max',
        'user_id',
        'recibo_cerrado',
        'estado',
    ];


    // one to many inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recibo_historial()
    {
        return $this->hasMany(ReciboHistorial::class);
    }

    public function recibo_historial_contado()
    {
        return $this->hasMany(ReciboHistorialContado::class);
    }

}

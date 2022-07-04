<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientesReactivados extends Model
{
    use HasFactory;

    protected $fillable = [
        'factura_id',
        'user_id',
        "cliente_id",
        "estado",
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}

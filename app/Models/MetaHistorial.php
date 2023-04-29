<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaHistorial extends Model
{
    use HasFactory;
    protected $table = "meta_historials";// <-- El nombre personalizado

    protected $fillable = [
        "user_id",
        "monto_meta",
        "estado",
        "fecha_asignacion",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

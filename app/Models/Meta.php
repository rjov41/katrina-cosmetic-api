<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "monto",
        "estado",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

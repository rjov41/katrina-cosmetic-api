<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigurationApp extends Model
{
    use HasFactory;
    protected $fillable = [
        'cierre',
    ];
}

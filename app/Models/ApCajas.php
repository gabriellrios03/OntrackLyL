<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApCajas extends Model
{
    use HasFactory;
    protected $table = 'ApCajas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'noeconomico',
        'placas',
        'inspeccionmecanica',
        'ultimaubicacion',
        'usuario',
		'fechamodifica',
		'activo'
    ];

    protected $casts = [
        'fechamodifica' => 'datetime',
        'activo' => 'integer'
    ];

}

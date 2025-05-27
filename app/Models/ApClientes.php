<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApClientes extends Model
{
    use HasFactory;
    protected $table = 'ApClientes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'rfc',
        'status',
        'usuario',
        'fechamodifica',
        'activo'
    ];

    protected $casts = [
        'activo' => 'integer',
        'fechamodifica' => 'datetime'
    ];

}

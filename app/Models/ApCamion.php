<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApCamion extends Model
{
    use HasFactory;
    protected $table = 'ApCamiones';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'noeconomico',
        'placas',
        'nmotor',
        'fechaverifica',
        'ultimokilom',
        'verifhumos',
        'usuario',
        'fechamodifica',
        'activo'
    ];

    protected $casts = [
        'ultimokilom' => 'decimal:2',
        'fechaverifica' => 'datetime',
        'fechamodifica' => 'datetime',
        'activo' => 'integer'
    ];

    // Relación con viajes (opcional)
    public function viajes()
    {
        return $this->hasMany(ApViaje::class, 'Id_camion');
    }
}

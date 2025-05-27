<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApPagosChofer extends Model
{
    use HasFactory;
    protected $table = 'ApPago_chofer';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'viajeID',
        'ExtrasID',
        'choferID',
        'monto',
        'fecha',
        'usuario',
        'fechamodifica',
        'activo',
        'estado'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha' => 'datetime',
        'fechamodifica' => 'datetime',
        'activo' => 'integer'
    ];

    // Relación con chofer
    public function chofer()
    {
        return $this->belongsTo(ApChofer::class, 'choferID');
    }

    // Relación con viaje (si es necesaria)
    public function viaje()
    {
        return $this->belongsTo(ApViaje::class, 'viajeID');
    }

    // Relación con extras (si es necesaria)
    public function extras()
    {
        return $this->belongsTo(ApExtra::class, 'ExtrasID');
    }    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApExtra extends Model
{
    protected $table = 'ApExtras';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'concepto',
        'tipo',
        'choferID',
        'viajeID',
        'previajeID',
        'observaciones',
        'monto',
        'usuario',
        'fechamodifica',
        'activo',
        'pagado'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fechamodifica' => 'datetime',
        'activo' => 'integer',
        'pagado' => 'integer'
    ];

    // Relaciones
    public function chofer()
    {
        return $this->belongsTo(ApChofer::class, 'choferID');
    }

    public function viaje()
    {
        return $this->belongsTo(ApViaje::class, 'viajeID');
    }

    public function previaje()
    {
        return $this->belongsTo(ApPreviaje::class, 'previajeID');
    }
}
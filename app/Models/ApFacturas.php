<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApFacturas extends Model
{
    use HasFactory;
    protected $table = 'ApFacturas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'viajeID',
        'folio',
        'uuid',
        'monto',
        'estadopago',
        'usuario',
        'fechamodifica',
        'activo',
        'MontoXML'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'MontoXML' => 'decimal:2',
        'fechamodifica' => 'datetime',
        'activo' => 'integer'
    ];

    // Relación con viaje
    public function viaje()
    {
        return $this->belongsTo(ApViaje::class, 'viajeID');
    }
}

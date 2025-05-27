<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApViajes extends Model
{
    use HasFactory;
    protected $table = 'ApViajes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Id_ruta',
        'fecha',
        'Id_chofer',
        'Id_camion',
        'estado',
        'cajainicial',
        'cajafinal',
        'selloInicial',
        'selloFinal',
        'evidencia',
        'facturado',
        'finalizado',
        'usuario',
        'fechamodifica',
        'activo',
        'motivo',
        'ClienteID'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'fechamodifica' => 'datetime',
        'cajainicial' => 'decimal:2',
        'cajafinal' => 'decimal:2',
        'facturado' => 'integer',
        'finalizado' => 'integer',
        'activo' => 'integer'
    ];

    // Relaciones
    public function ruta()
    {
        return $this->belongsTo(ApRuta::class, 'Id_ruta');
    }

    public function chofer()
    {
        return $this->belongsTo(ApChofer::class, 'Id_chofer');
    }

    public function camion()
    {
        return $this->belongsTo(ApCamion::class, 'Id_camion');
    }

    public function cliente()
    {
        return $this->belongsTo(ApCliente::class, 'ClienteID');
    }    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApRutas extends Model
{
    use HasFactory;
    protected $table = 'ApRutas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'origen',
        'destino',
        'kilomestima',
        'consumodiesel',
        'tarifacliente',
        'usuario',
        'fechamodifica',
        'activo',
        'ClienteID'
    ];

    protected $casts = [
        'kilomestima' => 'decimal:2',
        'consumodiesel' => 'decimal:2',
        'tarifacliente' => 'decimal:2',
        'fechamodifica' => 'datetime',
        'activo' => 'integer'
    ];

    // Relación con cliente (si existe)
    public function cliente()
    {
        return $this->belongsTo(ApCliente::class, 'ClienteID');
    }    
}

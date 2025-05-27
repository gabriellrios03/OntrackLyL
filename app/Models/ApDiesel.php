<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApDiesel extends Model
{
    use HasFactory;
    protected $table = 'ApDiesel';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Id_camion',
        'km_inicial',
        'km_final',
        'sitio_recarga',
        'costo_litro',
        'total_litros',
        'rendimiento',
        'total_costo',
        'fecha',
        'usuario',
        'fechamodifica',
        'activo',
        'bandera'
    ];

    protected $casts = [
        'km_inicial' => 'decimal:2',
        'km_final' => 'decimal:2',
        'costo_litro' => 'decimal:2',
        'total_litros' => 'decimal:2',
        'total_costo' => 'decimal:2',
        'fecha' => 'datetime',
        'fechamodifica' => 'datetime',
        'activo' => 'integer'
    ];

    /**
     * Calcula el rendimiento y determina la bandera
     *
     * @return array ['rendimiento' => decimal, 'bandera' => string]
     */
    public function calcularRendimiento()
    {
        $rendimiento = ($this->km_inicial - $this->km_final) / $this->total_litros;
        
        if ($rendimiento >= 3.0) {
            $bandera = 'verde';
        } elseif ($rendimiento >= 2.7) {
            $bandera = 'amarillo';
        } else {
            $bandera = 'rojo';
        }

        return [
            'rendimiento' => $rendimiento,
            'bandera' => $bandera
        ];
    }    
}

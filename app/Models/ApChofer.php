<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApChofer extends Model
{
    protected $table = 'ApChoferes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'nolicencialocal',
        'nolicenciafederal',
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

    // Relaciones
    public function viajes()
    {
        return $this->hasMany(ApViaje::class, 'Id_chofer');
    }

    public function extras()
    {
        return $this->hasMany(ApExtra::class, 'choferID');
    }

    public function pagos()
    {
        return $this->hasMany(ApPagoChofer::class, 'choferID');
    }
}

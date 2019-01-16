<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ponto extends Model
{
    protected $table      = 'PONTO';
    protected $primaryKey = 'id';
    public $incrementing  = true;
    public $timestamps    = false;

    protected $fillable = [
        'idFuncionario',
        'idEstadoPonto',        
        'horario',
    ];

    public function estadoPonto() {
        return $this->hasOne('App\Models\EstadoPonto', 'id', 'idEstadoPonto');
    }
}

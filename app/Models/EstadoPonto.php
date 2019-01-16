<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPonto extends Model
{
    protected $table      = 'ESTADO_PONTO';
    protected $primaryKey = 'id';
    //public $incrementing  = true;
    public $timestamps    = false;
}

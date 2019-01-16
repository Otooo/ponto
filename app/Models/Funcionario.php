<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    protected $table      = 'FUNCIONARIO';
    protected $primaryKey = 'id';
    public $incrementing  = true;
    public $timestamps    = false;

    protected $fillable = [
        'nome',
        'idEstadoPontoAtual',
        'jaAlmocou',
        'jaLanchou'
    ];
}

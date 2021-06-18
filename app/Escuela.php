<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{
    protected $connection = "mysql2";
    protected $table = 'actescu';

    public function matricula()
    {        
        return $this->belongsTo(Matricula::class, 'nues', 'nues');
    }

    public function graduandos_expediente()
    {        
        return $this->hasMany(GraduandoExpediente::class, 'nues', 'nues');
    }    
}

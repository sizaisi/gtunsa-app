<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{
    protected $table = 'actescu';

    public function expedientes()
    {        
        return $this->hasMany(Expediente::class, 'nues', 'nues');
    }
}

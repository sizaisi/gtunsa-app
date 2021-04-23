<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    protected $table = 'gt_tramites';

    public function expedientes()
    {        
        return $this->hasMany(Expediente::class, 'idtramite');
    }
}

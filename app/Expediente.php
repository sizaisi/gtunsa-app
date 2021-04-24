<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $table = 'gt_expediente';

    public function tramite()
    {        
        return $this->belongsTo(Tramite::class, 'idtramite');   
    }

    public function escuela()
    {        
        return $this->belongsTo(Escuela::class, 'nues', 'nues');   
    }

    public function graduandos()
    {
        return $this->belongsToMany(User::class, 'gt_graduando_expediente', 'idexpediente', 'idgraduando');
    }
}

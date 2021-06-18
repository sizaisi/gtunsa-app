<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GraduandoExpediente extends Model
{
    protected $table = 'gt_graduando_expediente';

    public function graduando()
    {        
        return $this->belongsTo(Graduando::class);   
    }   

    public function escuela()
    {        
        return $this->belongsTo(Escuela::class, 'nues', 'nues');   
    }   
}

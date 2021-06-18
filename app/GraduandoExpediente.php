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
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $table = 'gt_expediente';

    public function tramite()
    {        
        return $this->belongsTo(Tramite::class);   
    }    

    public function graduandos()
    {
        return $this->belongsToMany(Graduando::class, 
            'gt_graduando_expediente', 'expediente_id', 'graduando_id')
            ->withPivot('nues', 'espe');
    }
    
    public function graduandos_expediente()
    {        
        return $this->hasMany(GraduandoExpediente::class);
    }
}

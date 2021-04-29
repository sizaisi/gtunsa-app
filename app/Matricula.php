<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = 'acdidal';        
    
    public function escuela()
    {        
        return $this->hasOne(Escuela::class, 'nues', 'nues');
    }
}

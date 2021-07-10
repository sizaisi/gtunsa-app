<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    protected $connection = "mysql2";
    protected $table = 'actfacu';

    public function escuelas()
    {        
        return $this->hasMany(Escuela::class, 'facu', 'facu');
    }   
}

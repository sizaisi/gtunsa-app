<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $connection = "mysql2";
    protected $table = 'acdiden';

    public function graduando()
    {
        return $this->belongsTo(Graduando::class, 'cui', 'cui');
    }
}

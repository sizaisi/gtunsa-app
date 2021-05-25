<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $connection = "mysql2";
    protected $table = 'acdiden';


    public function graduando()
    {
        return $this->belongsTo(User::class, 'cui', 'cui');
    }
}

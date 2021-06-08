<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'gt_movimiento';    
    
    protected $fillable = [
        'user_id', 'expediente_id', 'ruta_id'
    ];
}

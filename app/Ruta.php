<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'gt_ruta';

    public function procedimiento()
    {
        return $this->belongsTo(Procedimiento::class, 'procedimiento_origen_id');
    }

    public function accion()
    {
        return $this->belongsTo(Accion::class);
    }
}

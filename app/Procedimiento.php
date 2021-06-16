<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    protected $table = 'gt_procedimiento';

    public function rutas()
    {
        return $this->hasMany(Ruta::class, 'procedimiento_origen_id');
    }
}

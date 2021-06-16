<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    protected $table = 'gt_accion';

    public function rutas()
    {
        return $this->hasMany(Ruta::class);
    }
}

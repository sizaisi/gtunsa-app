<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Graduando extends Model
{
    protected $table = 'gt_graduando';    
    
    protected $fillable = [
        'cui', 'nombres', 'apellidos', 'telefono', 'email_personal',
    ];

    public function alumno()
    {
        return $this->hasOne(Alumno::class, 'cui', 'cui');
    }

    public function expedientes()
    {
        return $this->belongsToMany(Expediente::class, 
            'gt_graduando_expediente', 'graduando_id', 'expediente_id')
            ->withPivot('nues', 'espe');
    }

    public function graduandos_expediente()
    {        
        return $this->hasMany(GraduandoExpediente::class);
    }    

    public function usuario()
    {
        return $this->morphOne(User::class, 'administrado');
    }
}

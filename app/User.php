<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'gt_graduando';    
    
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */   

    protected $fillable = [
         'cui', 'name', 'email', 'password', 'provider', 'provider_id', 'telefono_fijo', 'telefono_movil', 'direccion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'cui', 'cui');
    }

    public function expedientes()
    {
        return $this->belongsToMany(Expediente::class, 'gt_graduando_expediente', 'idgraduando', 'idexpediente');
    }
}

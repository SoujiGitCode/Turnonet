<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','user','level','rol'
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
     * Set password
     * @param type $value
     */
    public function setPasswordAttribute($value)
    {
       //Validamos si no esta vacio el password
        if (!empty($value)){
           
            //Asignamos el nuevo password al atributo
            $this->attributes['password'] = bcrypt($value);
        }
    }
}

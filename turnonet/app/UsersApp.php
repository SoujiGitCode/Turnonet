<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsersApp extends Authenticatable
{
    
    
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'tu_users';
     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'us_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'us_nom', 'us_mail', 'us_esta','us_contra','us_valicode','us_recon','us_esta','us_altfec','status_app','date_new','type','level','rol','pres_id','suc_id','emp_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'us_contra','remember_token'
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
            $this->attributes['us_contra'] = md5($value);
        }
    }
}

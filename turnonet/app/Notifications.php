<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tu_notificaciones';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = ['description', 'url', 'tipo','us_id'];

}

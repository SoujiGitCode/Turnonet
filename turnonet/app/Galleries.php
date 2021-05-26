<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Galleries extends Model {
    
    /**
     * Name of the table
     * @var type 
    */
    protected $table = 'galleries';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['image', 'status','name'];

}

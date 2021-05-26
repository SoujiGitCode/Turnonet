<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{   
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'states';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =['name','country','code'];
}

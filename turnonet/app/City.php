<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
     /**
     * Name of the table
     * @var type 
     */
     protected $table = 'cities';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable =['name','state','latitude','longitude'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visits extends Model {
    
    /**
     * Name of the table
     * @var type 
    */
    protected $table = 'visits';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page','ip','date','hour','device','code','name','month','year','day','name_day'];

}

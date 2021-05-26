<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model {
    
    /**
     * Name of the table
     * @var type 
    */
    protected $table = 'customers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['image', 'status','name'];

}

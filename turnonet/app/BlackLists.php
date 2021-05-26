<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlackLists extends Model {
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'blacklist';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['emp_id','email','status'];

}

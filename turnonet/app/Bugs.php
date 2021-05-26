<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bugs extends Model

{
   /**
     * Name of the table
     * @var type 
     */
	protected $table = 'bugs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable =['message','subject','user'];

	

}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
	 /**
     * Name of the table
     * @var type 
     */
	protected $table = 'activities';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable =['id_user','activity','ip'];
}

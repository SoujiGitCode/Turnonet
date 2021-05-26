<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audits extends Model
{
	 /**
     * Name of the table
     * @var type 
     */
	protected $table = 'audits';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable =['id_user','activity','ip'];
}

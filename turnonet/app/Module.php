<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {
    
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'modules';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'class'];

}

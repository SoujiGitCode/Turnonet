<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Categories extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_rub';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'rub_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rub_cat', 'rub_nom'


    ];

}
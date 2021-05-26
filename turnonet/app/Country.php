<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Country extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_pais';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pa_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pa_nom'
    ];

}
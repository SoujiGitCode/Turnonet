<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Speciality extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_esp';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'es_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'es_nom', 'es_tipo','es_tipo2'

    ];

}
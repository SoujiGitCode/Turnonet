<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_prov';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'prov_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prov_nom', 'pais_id', 'prov_orden'
    ];

}
{

}
<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class AdminUsers extends Model
{
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_admus';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'us_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'us_usua', 'us_nom', 'us_ape', 'us_tipo', 'us_contra', 'us_estado'
    ];
}
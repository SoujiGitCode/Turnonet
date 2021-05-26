<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class MercadoPago_Settings extends Model
{
    /**
     * Name of the table
     * @var type
     */
    protected $table = ' tu_settingsmp';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'client_secret'
    ];

}
{

}
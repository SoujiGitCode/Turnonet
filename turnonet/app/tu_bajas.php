<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class tu_baja extends Model
{
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_bajas';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'baj_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emp_id', 'suc_id', 'pres_id', 'fecha', 'hora', 'savtim'
    ];
}

<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Clients_Added extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emp_cli';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tp_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tp_empid', 'tp_usid'

    ];

}
<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Notes extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_not';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'not_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'not_caduc','not_ani','not_mes','not_empid','not_sucid','not_presid','not_desc','not_estado','us_id'
    ];

}
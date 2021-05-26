<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_fer';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fer_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'fer_date', 'fer_ani', 'fer_mes', 'fer_desc', 'fer_empid', 'fer_sucid','fer_presid','fer_estado'
    ];


}
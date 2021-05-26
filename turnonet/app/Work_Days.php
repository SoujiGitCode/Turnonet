<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Work_Days extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_dlab';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'lab_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lab_dian', 'lab_hin', 'lab_hou', 'lab_hin2', 'lab_hou2', 'lab_sucid',
        'lab_empid', 'lab_presid', 'lab_tipo', 'lab_fid'
    ];

}
<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class tu_adm_do extends Model
{
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_adm_do';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'do_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'do_empid', 'do_sucid', 'udo_presid', 'do_usid', 'do_usadmid', 'do_turid',
        'do_task', 'do_task2', 'do_consult', 'do_date', 'do_time', 'pag'
    ];
}
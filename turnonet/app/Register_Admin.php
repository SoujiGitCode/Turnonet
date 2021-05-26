<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Register_Admin extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_rg';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'rg_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rg_fec', 'rg_hor', 'rg_empid', 'rg_sucid', 'rg_preid', 'rg_turid', 'rg_usid', 'rg_tipo',
         'rg_txt', 'rg_carpor', 'rg_tip', 'rg_estado'

    ];

}
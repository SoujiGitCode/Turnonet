<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class tu_emp_suc_adm extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emp_suc_adm';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tu_suc_adm_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tu_suc_adm_usid', 'tu_suc_adm_empid','tu_suc_adm_sucid', 'tu_suc_adm_presid','tu_suc_adm_admemp',
        'tu_suc_adm_admsuc', 'tu_suc_adm_abm','tu_suc_adm_est'

    ];

}
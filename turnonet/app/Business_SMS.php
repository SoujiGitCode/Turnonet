<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Business_SMS extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emp_sms';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tes_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tes_empid', 'tes_sucid','tes_preid', 'tes_envrec','tes_recopt', 'tes_rechora',
        'tes_envcan','tes_conftur', 'tes_reag','tes_fecin', 'tes_fecfin'

    ];

}
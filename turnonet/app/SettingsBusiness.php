<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class SettingsBusiness extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_con';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cf_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emp_id', 'suc_id', 'pres_id', 'esp_id', 'cf_turt', 'cf_simtu',
        'cf_activ', 'cf_tcan', 'cf_days', 'cf_daysp', 'cf_donde', 'cf_bloqday', 'cf_tipval','cf_daysp_all','cf_days_all','cf_tcan_all'

    ];

}
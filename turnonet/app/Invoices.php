<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices  extends Model {
    
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'tu_turnos';
     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tu_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'tu_code','suc_id','us_id','emp_id','pres_id','tu_fec','tu_hora','tu_horaf','tu_durac','tu_estado','tu_bloqfec','tu_bloqhor','tu_carga','tu_car_id','tu_servid','tu_asist','tu_atencion','tu_st','tu_turx','tu_usadm','tur_canid','tur_ipfrom'
   ];

   
}

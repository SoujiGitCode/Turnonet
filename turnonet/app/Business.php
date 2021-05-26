<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business  extends Model {
    
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'tu_emps';
     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'em_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'em_uscid','em_nomfan','em_cuit','em_rsoc','em_domleg','em_rub','em_rubs','em_pais','em_prov','em_locbar','em_lat','em_lng','em_email','em_emailalt','em_tel','em_telalt','em_cont','em_feccre','em_estado','em_valcod','em_vcs','em_pfot','em_url','em_tipo','em_mb','em_iva','em_url_origen','em_fact','em_ctf','em_presxag','em_sodimac','em_smscontrol','access_token','public_key','total_commission','refresh_token','expired_mp','commission','em_mp','reminder_1','reminder_5','shift_user','ip_user','zoom_act'
   ];

   
}

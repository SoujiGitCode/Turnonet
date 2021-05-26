<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockedShedules extends Model
{
	 /**
     * Name of the table
     * @var type 
     */
	protected $table = 'blocked_schedules';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable =['pres_id','tur_id','tur_time','tur_date','tur_status','us_id','tu_hora','tu_bloqhor'];
}

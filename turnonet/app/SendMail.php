<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendMail extends Model
{
	 /**
     * Name of the table
     * @var type 
     */
	protected $table = 'sendmail';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable =['name','emp_id','email','id_sq','date_reports','content','hour_sendmail','type','category','rep_type'];
}

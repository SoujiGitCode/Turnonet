<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Cancelation_Time extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_smscanceltur';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tcan_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'smsc_turid', 'smsc_empid','smsc_userphone', 'smsc_msg','smsc_fecrec', 'sms_timerec'

    ];

}
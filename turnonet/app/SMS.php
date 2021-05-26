<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class SMS extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_sms';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tusms_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tusms_turid', 'tusms_empid','tusms_sucid', 'tusms_preid','tusms_usuid', 'tusms_pacnom',
        'tusms_celenv','tusms_tipo', 'tusms_para','tusms_msg', 'tusms_priori', 'tusms_pass','tusms_error',
        'last_update_date', 'tusms_envi','tusms_envfec', 'tusms_envtime','tusms_resp'

    ];

}
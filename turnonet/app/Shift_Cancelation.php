<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Shift_Cancelation extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_tucan';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tucan_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tucan_turid', 'tucan_mot', 'tucan_fec', 'tucan_hor', 'tucan_usid', 'tucan_tipo', 'tucan_ip',
        'tucan_url', 'tucan_ref','tucan_cod'

    ];

}
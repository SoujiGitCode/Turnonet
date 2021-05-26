<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class SMS_Cancel extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_tcan';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tcan_valor', 'tcan_nom'

    ];

}
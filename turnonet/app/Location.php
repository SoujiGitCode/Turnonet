<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Location extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_ubic';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ub_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ub_usid', 'ub_tit', 'ub_dir', 'ub_lat', 'ub_lon', 'ub_def'

    ];

}
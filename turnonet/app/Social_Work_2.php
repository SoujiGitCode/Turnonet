<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Social_Work_2 extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_osocial';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'os_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'os_nom', 'os_nomdes', 'os_plan', 'os_desc', 'os_estado'

    ];

}
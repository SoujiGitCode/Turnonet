<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class tu_locbar extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_locbar';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'loc_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loc_nom', 'prov_id'

    ];

}
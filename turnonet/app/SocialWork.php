<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class SocialWork extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_obsoc';
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
        'os_pais', 'os_nomp', 'os_nom', 'os_logo', 'os_estado'

    ];

}
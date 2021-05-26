<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Plan_Items extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_pitems';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pli_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pl_id', 'pli_item', 'pli_ord'
    ];

}
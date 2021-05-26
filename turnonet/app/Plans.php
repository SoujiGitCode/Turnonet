<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Plans extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'plans';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'price', 'item_1', 'item_2', 'item_3', 'item_4','item_5','item_6','item_7','item_8','item_9','item_10','item_11','item_12','item_13','item_14','item_15','item_16','item_16','item_17','item_18','position','price_usd'
    ];

}
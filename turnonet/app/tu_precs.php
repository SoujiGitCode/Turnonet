<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class tu_precs extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_precs';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pr_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pr_pnom', 'pr_tel', 'pr_email', 'pr_web', 'pr_cat', 'pr_usid', 'rec_fec', 'rec_time', 'pr_estado'
    ];

}
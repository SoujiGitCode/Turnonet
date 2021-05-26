<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Basic_Fields extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_obfield';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'obf_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'obf_paid', 'obf_lang', 'obf_nom', 'obf_nom2', 'obf_ord', 'obf_opt'

    ];

}
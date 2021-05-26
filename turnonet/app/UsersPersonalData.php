<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UsersPersonalData extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_usdat';
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
        'ud_emalt', 'ud_pres', 'ud_prov', 'ud_locbar', 'ud_dire', 'ud_cp', 'ud_tel', 'ud_cel', 'ud_pfot', 'ud_fnac','ud_usid'

    ];

}
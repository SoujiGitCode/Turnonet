<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class tu_manag extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_manag';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ma_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ma_empid', 'ma_sucid','ma_presid', 'ma_usuid','ma_estado',
        'ma_valcod', 'ma_perlev','ma_crepor','ma_crefec'

    ];

}
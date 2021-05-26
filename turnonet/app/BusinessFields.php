<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class BusinessFields extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_md';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'mi_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mi_empid', 'mi_sucid', 'mi_presid', 'mi_field', 'mi_ob', 'mi_goup', 'mi_ord',
        'mi_tipo','mi_tipofield','mi_gentxt','field_report'

    ];

}
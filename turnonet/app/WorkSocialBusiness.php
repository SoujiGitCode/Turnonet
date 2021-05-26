<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class WorkSocialBusiness extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_ob';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'eob_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'eob_empid', 'eob_sucid', 'eob_presid', 'eob_obid'

    ];

}
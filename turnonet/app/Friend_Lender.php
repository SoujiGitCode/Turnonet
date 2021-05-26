<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Friend_Lender extends Model
{
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_presami';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tu_pami_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tu_usid', 'tu_emp_id', 'tu_suc_id', 'tu_pres_id'
    ];

}
{

}
<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class ReportsBusiness extends Model
{
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_reps';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'rep_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emp_id', 'suc_id', 'pre_id', 'rep_hora', 'rep_recemp', 'rep_recsuc', 'rep_recpre','rep_type'
    ];

}
{

}
<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Branch extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_suc';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'suc_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'suc_uscid', 'suc_empid', 'suc_nom', 'suc_dom', 'suc_domnum', 'suc_pais', 'suc_prov', 'suc_locbar',
        'suc_domtip', 'suc_domad', 'suc_dompiso', 'suc_dompnum', 'suc_doment', 'suc_domcp', 'suc_hor', 'suc_email',
        'suc_emailalt', 'suc_tel', 'suc_telalt', 'suc_cont', 'suc_feccre', 'suc_estado', 'suc_valcod', 'suc_pfot',
        'suc_lat', 'suc_lng'


    ];

}
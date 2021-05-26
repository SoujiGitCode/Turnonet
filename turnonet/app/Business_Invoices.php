<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Business_Invoices extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_fac';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ef_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ef_empid', 'ef_facnum', 'ef_factip', 'ef_fecfac', 'ef_anio', 'ef_mes', 'ef_mesp',
        'ef_fecpago', 'ef_plan', 'ef_iva', 'ef_impplan', 'ef_impplantot', 'ef_impcob',
        'ef_impcobtot', 'ef_cob', 'ef_paga'
    ];

}
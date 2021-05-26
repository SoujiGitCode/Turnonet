<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class ClientsCustomization extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_ususmd';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'usm_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usm_usid', 'usm_empid', 'usm_turid', 'usm_fecnac', 'usm_tipdoc', 'usm_numdoc', 'usm_obsoc', 'usm_obsocpla',
        'usm_afilnum', 'usm_tel', 'usm_cel', 'usm_fecac', 'usm_gen1', 'usm_gen2', 'usm_gen3', 'usm_gen4', 'usm_usadm','usm_gen5','usm_gen6','usm_gen7','usm_gen8'

    ];

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model {
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'directory';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['emp_id','us_id','name','email','phone','social','status','dni','usm_fecnac', 'usm_tipdoc', 'usm_numdoc', 'usm_obsoc', 'usm_obsocpla',
        'usm_afilnum', 'usm_tel', 'usm_cel', 'usm_fecac', 'usm_gen1', 'usm_gen2', 'usm_gen3', 'usm_gen4', 'usm_usadm'];

}

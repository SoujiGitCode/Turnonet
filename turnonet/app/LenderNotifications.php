<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class LenderNotifications extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_pcon';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pc_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pc_usid', 'pc_empid', 'pc_sucid', 'pc_presid', 'pc_adia', 'pc_mailn', 'pc_mailc', 'pc_msg', 'pc_mailr',
        'pc_emp_msg', 'pc_suc_msg'

    ];

}
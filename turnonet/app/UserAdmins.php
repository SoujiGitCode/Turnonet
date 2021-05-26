<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UserAdmins extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_users_admins';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'us_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'us_nom', 'us_ape', 'us_mail', 'us_contra', 'us_val', 'us_esta', 'us_valicode', 'us_altfec', 'us_altime',
        'us_tipo', 'us_regpor', 'us_recon', 'us_uscid', 'us_empadm', 'us_sucadm', 'us_presadm', 'us_solotur', 'us_estad'


    ];

}
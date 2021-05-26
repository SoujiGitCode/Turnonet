<?php


namespace App\Providers;


use Illuminate\Database\Eloquent\Model;

class AppEmailReg extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emailReg';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'lab_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mail', 'tipo', 'hora', 'fecha', 'emp', 'ip', 'mailid'
    ];

}
<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class MercadoPago extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_mercadopago';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_payment', 'id_turno','id_prestador', 'amount','commission','emp_id'

    ];

}
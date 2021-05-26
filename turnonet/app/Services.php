<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Services extends Model {
    /**
     * Name of the table
     * @var type
     */
    protected $table = 'tu_emps_serv';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'serv_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serv_presid', 'serv_sucid', 'serv_empid', 'serv_nom', 'serv_nom_en', 'serv_nom_po', 'serv_nom_de', 'serv_nom_fr',
        'serv_simtu', 'serv_tudur', 'serv_price', 'serv_curr', 'serv_disc', 'serv_estado', 'serv_tipo', 'serv_turx','url'


    ];

     /**
     * Set url attribute
     * @param type $value
     */
    public function setUrlAttribute($value) {

        $value = strtolower($value);
        $value=mb_strtolower($value,'UTF-8');
        $value=trim($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[\s]/s', '', $value);
        //Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $value = str_replace($find, $repl, $value);
        // Añadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
        $value = str_replace($find, '-', $value);
        // Eliminamos y Reemplazamos otros carácteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $value = preg_replace($find, $repl, $value);
        //Asignamos Valor al atributo  URL
        $this->attributes['url'] = $value;
    }

}
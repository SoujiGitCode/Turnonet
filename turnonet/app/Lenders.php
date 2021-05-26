<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lenders  extends Model {
    
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'tu_tmsp';
     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'tmsp_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'emp_id','suc_id','tmsp_tit','tmsp_pnom','tmsp_pmail','tmsp_pfot','tmsp_esp','us_id','tmsp_tel','tmsp_pcel','tmsp_estado','tmsp_ereg','tmsp_cmail','tmsp_dias','tmsp_fnac','tmsp_fecadd','tmsp_orden','tmsp_pagoA','url','position','activate_zoom'
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

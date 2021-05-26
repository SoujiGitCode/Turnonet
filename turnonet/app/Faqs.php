<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    protected $table = 'faqs';

    protected $fillable =['category','title','content','title_en','content_en','position','url','url_en','video','image'];
    /**
     * Set title attribute
     * @param type $value
     */
    public function setTitleAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['title'] = $value;
    }
    /**
     * Set title attribute
     * @param type $value
     */
    public function setTitleEnAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['title_en'] = $value;
    }


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

    /**
     * Set url en attribute
     * @param type $value
     */
    public function setUrlEnAttribute($value) {

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
        $this->attributes['url_en'] = $value;
    }
    
}

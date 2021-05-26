<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Communications extends Model {
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'communications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','url','content'];
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


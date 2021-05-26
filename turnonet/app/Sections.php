<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model {
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'sections';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','content','title_en','content_en','image','status','position','name_boton','name_boton_en','url_boton','url_boton_en','status','status_content'];
    /**
     * Set title attribute
     * @param type $value
     */
    public function setTitleAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s&]/s', '', $value);
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
     * Set name_boton attribute
     * @param type $value
     */
    public function setNameBotonAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['name_boton'] = $value;
    }
    /**
     * Set name_boton_en attribute
     * @param type $value
     */
    public function setNameBotonEnAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['name_boton_en'] = $value;
    }

    /**
     * Set url_boton attribute
     * @param type $value
     */
    public function setUrlBotonAttribute($value) {

        $value = strtolower($value);
        $value=mb_strtolower($value,'UTF-8');
        $value=preg_replace('/[^a-zA-Z0-9[#&=[\/]-_.:\s]/s', '', $value);
        $value = str_replace('https://', 'http://', $value);
        $value = str_replace('www.', 'http://', $value);
        $value = str_replace('http://http://', 'http://', $value);
        $value = str_replace('http://', 'https://', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  URL
        $this->attributes['url_boton'] = $value;
    }

    /**
     * Set url_boton_en attribute
     * @param type $value
     */
    public function setUrlBotonEnAttribute($value) {

        $value = strtolower($value);
        $value=mb_strtolower($value,'UTF-8');
        $value=preg_replace('/[^a-zA-Z0-9[#&=[\/]-_.:\s]/s', '', $value);
        $value = str_replace('https://', 'http://', $value);
        $value = str_replace('www.', 'http://', $value);
        $value = str_replace('http://http://', 'http://', $value);
        $value = str_replace('http://', 'https://', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  URL
        $this->attributes['url_boton_en'] = $value;
    }
    

}


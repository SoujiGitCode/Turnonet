<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banners extends Model {
    /**
     * Name of the table
     * @var type 
     */
    protected $table = 'banners';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','subtitle','title_en','subtitle_en','image','status','position','video','type','name_boton','name_boton_en','url_boton','url_boton_en'];
    /**
     * Set subtitle attribute
     * @param type $value
     */
    public function setSubtitleAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['subtitle'] = $value;
    }
    /**
     * Set subtitle attribute
     * @param type $value
     */
    public function setSubtitleEnAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['subtitle_en'] = $value;
    }

    /**
     * Set resume attribute
     * @param type $value
     */
    public function setResumeAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['resume'] = $value;
    }
    /**
     * Set resume_en attribute
     * @param type $value
     */
    public function setResumeEnAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[?¿¡!.,\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['resume_en'] = $value;
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
        $value=trim($value);
        //Asignamos Valor al atributo  URL
        $this->attributes['url_boton_en'] = $value;
    }

}


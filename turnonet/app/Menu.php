<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
    
    /**
     * Name of the table
     * @var type 
    */
    protected $table = 'menus';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'url','title_en','url_en','type', 'status','position','description','image'];
    /**
     * Set title attribute
     * @param type $value
     */
    public function setTitleAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[\s]/s', '', $value);
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
        $value=preg_replace('/[^a-zA-Z0-9[#&=[\/]-_.:\s]/s', '', $value);
        $value = str_replace('https://', 'http://', $value);
        $value = str_replace('www.', 'http://', $value);
        $value = str_replace('http://http://', 'http://', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  URL
        $this->attributes['url'] = $value;
    }
    /**
     * Set title_en attribute
     * @param type $value
     */
    public function setTitleEnAttribute($value) {

        $value=strip_tags($value);
        $value=preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[\s]/s', '', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  Title
        $this->attributes['title_en'] = $value;
    }

    /**
     * Set url_en attribute
     * @param type $value
     */
    public function setUrlEnAttribute($value) {

        $value = strtolower($value);
        $value=mb_strtolower($value,'UTF-8');
        $value=preg_replace('/[^a-zA-Z0-9[#&=[\/]-_.:\s]/s', '', $value);
        $value = str_replace('https://', 'http://', $value);
        $value = str_replace('www.', 'http://', $value);
        $value = str_replace('http://http://', 'http://', $value);
        $value=trim($value);
        //Asignamos Valor al atributo  URL
        $this->attributes['url_en'] = $value;
    }

}

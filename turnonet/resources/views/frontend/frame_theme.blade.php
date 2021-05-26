@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Turnonet en mi website</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-7 col-sm-7 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Personalizá la apariencia de tu frame aquí:
            </div>
            <div class="panel-body">
               <div class="row">
                  <form id="form">
                     <input type="hidden" name="id" value="<?php echo $settings->id;?>">
                     <input type="hidden" name="em_id" value="<?php echo $get_business->em_id;?>">
                     <div class="col-md-12">
                        <p>En caso de contar con su propio sitio web puedes personalizar la apariencia de tu frame. En caso de tener alguna consulta, no dude en contacterse con nosotros!
                        <p>
                     </div>

                      <div class="form-group  col-md-12 col-sm-12 col-xs-12btop bdashed">
                        <label class="label-1">IDENTIDAD:</label>
                     </div>

                     <div class="form-group col-md-12">
                        <div class="row">
                           <div class="col-md-12 col-sm-12 col-xs-12">
                              <label>TÍTULO DE LA PESTAÑA DE NAVEGACIÓN:</label>
                              <input type="text" id="title" name="title" class="form-control"
                              placeholder="Ingresá un título" value="<?php echo $settings->title;?>">
                           </div>
                           <div class="col-md-12 col-sm-12 col-xs-12">
                              <br>
                              <label>Alias:</label>
                              <input type="text" id="url" name="url" class="form-control"
                              placeholder="Ingresá una url" value="<?php echo $settings->url;?>">
                              <p class="form-desc"><br>Este es el nombre con el que tus clientes podran acceder a tu frame. <br> Ej. <?php echo url('/').'/'.$settings->url;?></p>
                           </div>
                        </div>
                     </div>


<div class="form-group  col-md-12 col-sm-12 col-xs-12btop bdashed">
                        <label class="label-1">SEO:</label>
                     </div>
     <div class="form-group col-md-12">
                        <div class="row">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                              <label>KEYWORDS:</label>
                        
                  {!! Form::textarea('keywords',$settings->keywords,['id'=>'keywords','placeholder'=>'Ingresá el contenido de la etiqueta keywords','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
                           </div>
                           <div class="col-md-12 col-sm-12 col-xs-12">
                              <br>
                              <label>DESCRIPTION:</label>

                                 {!! Form::textarea('description',$settings->description,['id'=>'description','placeholder'=>'Ingresá el contenido de la etiqueta description','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}

                            
                           </div>
                        </div>
                     </div>


                     <div class="form-group  col-md-12 col-sm-12 col-xs-12btop bdashed">
                        <label class="label-1">Fuentes:</label>
                     </div>

                      <div class="form-group col-md-12">
                        <div class="row">
                     <div class="col-md-4 col-sm-4 col-xs-12">
                        <label>Fuente Principal:</label>
                        <select name="font_1" class="form-control" id="font_1">
                        @foreach($fonts as $rs)
                        <option value="<?php echo $rs->id;?>" @if($rs->id==$settings->font_1) selected="selected" @endif><?php echo $rs->name;?></option>
                        @endforeach
                        </select>
                     </div>
                     <div class="col-md-4 col-sm-4 col-xs-12">
                        <label>Fuente Secundaria:</label>
                        <select name="font_2" class="form-control" id="font_2">
                        @foreach($fonts as $rs)
                        <option value="<?php echo $rs->id;?>" @if($rs->id==$settings->font_2) selected="selected" @endif><?php echo $rs->name;?></option>
                        @endforeach
                        </select>
                     </div>
                     <div class="col-md-4 col-sm-4 col-xs-12">
                        <label>Fuente Terciaria:</label>
                        <select name="font_3" class="form-control" id="font_3">
                        @foreach($fonts as $rs)
                        <option value="<?php echo $rs->id;?>" @if($rs->id==$settings->font_3) selected="selected" @endif><?php echo $rs->name;?></option>
                        @endforeach
                        </select>
                     </div>
                  </div>
               </div>
                     <div class="form-group  col-md-12 col-sm-12 col-xs-12btop bdashed">
                        <label class="label-1">Colores:</label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color-9" name="color_9" value="<?php echo $settings->color_9;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color-9">
                        Background del frame
                        </label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color" name="color_1" value="<?php echo $settings->color_1;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color">
                         Títulos y botones
                        </label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color-2" name="color_2" value="<?php echo $settings->color_2;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color-2">
                        Textos del frame, cabecera del calendario, efecto hover en botones
                        </label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color-8" name="color_8" value="<?php echo $settings->color_8;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color-8">
                        Background de cabecera de los días (calendario)
                        </label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color-7" name="color_7" value="<?php echo $settings->color_7;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color-7">
                        Background del calendario
                        </label>
                     </div>
                     
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color-4" name="color_4" value="<?php echo $settings->color_4;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color-4">
                        Días inactivos
                        </label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color-5" name="color_5" value="<?php echo $settings->color_5;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color-6">
                        Días disponibles
                        </label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color-3" name="color_3" value="<?php echo $settings->color_3;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color-3">
                        Botón y confirmación de registro
                        </label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="input-color-container">
                           <input id="input-color-6" name="color_6" value="<?php echo $settings->color_6;?>" class="input-color" type="color">
                        </div>
                        <label class="input-color-label" for="input-color-6">
                        Botón de mercadopago
                        </label>
                     </div>

                     
                     
                     <div class="form-group  col-md-12 col-sm-12 col-xs-12btop bdashed">
                        <label class="label-1">Header:</label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="demoo1">
                           <input type="checkbox" name="header" id="header" value="1" @if($settings->header=='1') checked="checked" @endif >
                           <label for="header"><span></span>Ocultar Cabecera</label>
                        </div>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="demoo1">
                           <input type="checkbox" name="name" id="name" value="1" @if($settings->name=='1') checked="checked" @endif >
                           <label for="name"><span></span>Ocultar el nombre y foto de la empresa/prestador</label>
                        </div>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="demoo1">
                           <input type="checkbox" name="searchbar" id="searchbar" value="1" @if($settings->searchbar=='1') checked="checked" @endif >
                           <label for="searchbar"><span></span>Ocultar barra de búsqueda</label>
                        </div>
                     </div>
                     

                     <div class="form-group  col-md-12 col-sm-12 col-xs-12btop bdashed">
                        <label class="label-1">Pie de Página:</label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="demoo1">
                           <input type="checkbox" name="footer" id="footer" value="1" @if($settings->footer=='1') checked="checked" @endif >
                           <label for="footer"><span></span>Ocultar pie de página</label>
                        </div>
                     </div>
                     <div class="form-group  col-md-12 col-sm-12 col-xs-12btop bdashed">
                        <label class="label-1">Marca de Agua:</label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="demoo1">
                           <input type="checkbox" name="marca" id="marca" value="1" @if($settings->marca=='1') checked="checked" @endif >
                           <label for="marca"><span></span>Ocultar marca de agua</label>
                        </div>
                     </div>
                     <div class="form-group  col-md-12 col-sm-12 col-xs-12btop bdashed">
                        <label class="label-1">CSS Personalizado:</label>
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        {!! Form::textarea('style',$settings->style,['id'=>'style','class'=>'form-control','cols'=>'5','rows'=>'5','placehoder'=>'Ingresá tu código aquí']) !!}
                     </div>
                     <div class="form-group col-md-12 col-sm-12 col-xs-12">
                         <br>
                     <button type="button" onclick="update_settings()" class="btn btn-success" id="boton-1">Guardar cambios</button>
                     </div>
               </div>
            </div>
            <input type="hidden" id="url_oll"  name="url_oll" value="<?php echo $settings->url;?>">
            </form>
         </div>
      </div>
      <div class="col-md-5 col-sm-5 col-xs-12 hidden-xs">
         <div class="row">
            <div class="col-md-12">
               <ul class="nav nav-pills text-right">
                  <li class="active"><a href="<?php echo url('empresa/frame/'.$get_business->em_id);?>" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver atrás</a></li>
                 
               </ul>
            </div>
         </div>
         <iframe id="framebody" frameborder="no" border="0" scrolling="yes" width="80%" height="580px" src="<?php echo url("/")."/e/esn/".$get_business->em_id."/".substr($get_business->em_valcod,0,4);?>"></iframe> <br> <a href="<?php echo url("/")."/e/esn/".$get_business->em_id."/".substr($get_business->em_valcod,0,4);?>" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> Ver pantalla completa</a>
      </div>
   </div>
</div>
<input type="hidden" name="url_page" id="url_page" value="<?php echo url('/');?>">
<input type="hidden" name="url_frame" id="url_frame" value="<?php echo url("/")."/e/esn/".$get_business->em_id."/".substr($get_business->em_valcod,0,4);?>">
<?php echo Html::script('frontend/js/update_frame.js?v='.time())?>
@stop
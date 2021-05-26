@extends('layouts.template_frontend_inside')
@section('content')
<form  enctype="multipart/form-data" id="form" method="post">
   <input type="hidden" name="em_id" value="<?php echo $get_business->em_id;?>">
    <input type="hidden" name="id" value="<?php echo $get_branch->suc_id;?>">
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Nuevo Prestador</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
               <div class="panel-heading">
                  Actualizá la foto de tu prestador aquí:
               </div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-4 col-sm-4 col-xs-12"></div>
                     <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="form-group text-center">
                     <div class="img-emp" > <img src="<?php echo url('/img/empty.jpg');?>" alt="Turnonet" title="Turnonet" id="avs-2" >
                     </div>
                     <div class="form-group ">
             <input type="file" id="attachment-2" accept="image/x-png,image/gif,image/jpeg" name="tmsp_pfot" class="inputfile inputfile-1">
                        <label for="attachment-2" id="iborrainputfile" title="Subir una imagen desde el equipo">
							<i class="fa fa-picture-o" aria-hidden="true"></i> SELECCIONAR IMAGEN</label>
                     </div>
                     <p class="form-desc text-center">Ingresá una imagen de 200x200 px. </p>
                                    <p class="form-desc text-center">Ingresá una imagen de 200x200 px. Del tipo .JPG, .PNG ó .GIF. </p>
                     </div>
                     <div class="col-md-4 col-sm-4 col-xs-12"></div>
                  </div>
                  </div>
               </div>
            </div>
            <div class="panel">
               <div class="panel-heading">
                  Ingresá la información de tu prestador aquí:
               </div>
              
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Título </label>
                        <select name="tmsp_tit" class="form-control" id="tmsp_tit">
                              <option value="">Seleccioná</option>
<option value="Dr.">Dr.</option>
<option value="Dra.">Dra.</option>
<option value="Lic.">Lic.</option> 
                           </select>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Nombre *</label>
                        <input type="text" id=" tmsp_pnom" name="tmsp_pnom" class="form-control"
                           placeholder="Ingresá el nombre">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Correo electrónico *</label>
                        <input type="text" id="tmsp_pmail" name="tmsp_pmail" class="form-control"
                           placeholder="Ingresá el correo electrónico" >
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Teléfono</label>
                        <input type="text" id="tmsp_tel" name="tmsp_tel" class="form-control"
                           placeholder="Ingresá el teléfono">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Celular</label>
                        <input type="text" id="tmsp_pcel" name="tmsp_pcel" class="form-control"
                           placeholder="Ingresá el celular">
                     </div>
                     
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                    
                     <label>Fecha de Nacimiento</label>
                      <div class="row">
                     <div class="col-md-4 col-sm-4 col-xs-4">
                           <input type="tel" name="day" id="day" class="form-control" placeholder="Día"  value=""/>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                           <input type="tel" name="month" id="month" class="form-control" placeholder="Mes" value=""/>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4"> 
                           <input type="tel" name="year" id="year" class="form-control"  placeholder="Año" value=""/>
                        </div>
                     </div>
                
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Horarios de Atención</label>
                        <input type="text" id="tmsp_dias" name="tmsp_dias" class="form-control"
                           placeholder="Ingresá los horarios de atención" >
                     </div>

                     
                     <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <p>Campos obligatorios (*)</p>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <button type="button" onclick="create_lender()" class="btn btn-success" id="boton-1">Guardar</button>
                     </div>
                  </div>
               </div>
            </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.branch')
      </div>
   </div>
</div>
</form>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" id="select_branch" value="<?php echo $get_branch->suc_id;?>">
<?php echo Html::script('frontend/js/settings_branch.js?v='.time())?>
<script type="text/javascript">
   document.getElementById("day").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890".indexOf(chr) < 0)
        return false;
};
 document.getElementById("month").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890".indexOf(chr) < 0)
        return false;
};
 document.getElementById("year").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890".indexOf(chr) < 0)
        return false;
};
</script>
@stop
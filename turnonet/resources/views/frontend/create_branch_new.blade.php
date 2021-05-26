@extends('layouts.template_frontend_inside')
@section('content')
<form  enctype="multipart/form-data" id="form" method="post">
   <input type="hidden" name="id" value="<?php echo $get_business->em_id;?>">
   <div class="container">
      <div class="row">
         <div class="col-xs-12">
            <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
            <h4 class="title-date-2">NUEVA SUCURSAL</h4>
         </div>
         <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="f1-steps">
               <div class="f1-progress">
                 <div class="f1-progress-line" data-now-value="100" data-number-of-steps="3" style="width: 100%;"></div>
              </div>
              <div class="f1-step active" id="step-1">
               <div class="f1-step-icon">1</div>
               <p>Cargue los datos de su empresa.</p>
            </div>
            <div class="f1-step active" id="step-2">
               <div class="f1-step-icon">2</div>
               <p>Defina días y horarios para sus turnos.</p>
            </div>
            <div class="f1-step active" id="step-3">
               <div class="f1-step-icon">3</div>
               <p>Cargue los datos de su sucursal.</p>
            </div>
         </div>
         <br>
      </div>
         <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="panel">
               <div class="panel-heading">
                  Actualizá el logo de tu sucursal aquí:
               </div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-4 col-sm-4 col-xs-12"></div>
                     <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="form-group text-center">
                     <div class="img-emp" > <img src="<?php echo url('/img/empty.jpg');?>" alt="Turnonet" title="Turnonet" id="avs-2" >
                     </div>
                     <div class="form-group ">
                  <input type="file" id="attachment-2" accept="image/x-png,image/gif,image/jpeg" name="suc_pfot" class="inputfile inputfile-1">
                        <label for="attachment-2" id="iborrainputfile" title="Subir una imagen desde el equipo">
							<i class="fa fa-picture-o" aria-hidden="true"></i> SELECCIONAR IMAGEN</label>
                     </div>
                                    <p class="form-desc text-center">Ingresá una imagen de 200x200 px. Del tipo .JPG, .PNG ó .GIF. </p>
                     </div>
                     <div class="col-md-4 col-sm-4 col-xs-12"></div>
                  </div>
                  </div>
               </div>
            </div>
            <div class="panel">
               <div class="panel-heading">
                  Ingresá la información de tu sucursal aquí:
               </div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Nombre *</label>
                        <input type="text" id="suc_nom" name="suc_nom" class="form-control"
                           placeholder="Ingresá el nombre">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Correo electrónico *</label>
                        <input type="text" id="suc_email" name="suc_email" class="form-control"
                           placeholder="Ingresá el correo electrónico" >
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Teléfono</label>
                        <input type="text" id="suc_tel" name="suc_tel" class="form-control"
                           placeholder="Ingresá el teléfono">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Contacto *</label>
                        <input type="text" id="suc_cont" name="suc_cont" class="form-control"
                           placeholder="Ingresá el contacto">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>País *</label>
                        <select name="suc_pais" class="form-control" id="suc_pais">
                           <option value="">Seleccioná</option>
                           @foreach($countries as $rs)
                           <option value="<?php echo $rs->pa_id;?>">
                              <?php echo $rs->pa_nom;?>
                           </option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Domicilio Legal *</label>
                        <input type="text" id="suc_dom" name="suc_dom" class="form-control"
                           placeholder="Ingresá el domicilio legal" >
                     </div>
                      
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Nro de domicilio </label>
                        <input type="text" id="suc_dompnum" name="suc_dompnum" class="form-control"
                           placeholder="Ingresá el número de domicilio" >
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Piso </label>
                        <input type="text" id="suc_dompiso" name="suc_dompiso" class="form-control"
                           placeholder="Ingresá el piso" >
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Letra/número </label>
                        <input type="text" id="suc_doment" name="suc_doment" class="form-control"
                           placeholder="Ingresá la letra/número" >
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Código Postal </label>
                        <input type="text" id="suc_domcp" name="suc_domcp" class="form-control"
                           placeholder="Ingresá el código postal" >
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Horarios de Atención</label>
                        <input type="text" id="suc_hor" name="suc_hor" class="form-control"
                           placeholder="Ingresá los horarios de atención" >
                     </div>

                     
                     <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <p>Campos obligatorios (*)</p>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <button type="button" onclick="created_data_branch()" class="btn btn-success" id="boton-1">Guardar</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-4 col-xs-12" >
             @include('includes.business')
         </div>
      </div>
   </div>
</form>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
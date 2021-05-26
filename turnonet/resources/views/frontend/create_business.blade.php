@extends('layouts.template_frontend_inside')
@section('content')
<form  enctype="multipart/form-data" id="form" method="post">
   <div class="container">
      <div class="row">
         <div class="col-xs-12">
            <h2 class="title-section-2">MIS EMPRESA</h2>
            <h4 class="title-date-2">NUEVA EMPRESA</h4>
         </div>
         <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="f1-steps">
               <div class="f1-progress">
                 <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
              </div>
              <div class="f1-step active" id="step-1">
               <div class="f1-step-icon">1</div>
               <p>Cargue los datos de su empresa.</p>
            </div>
            <div class="f1-step" id="step-2">
               <div class="f1-step-icon">2</div>
               <p>Defina días y horarios para sus turnos.</p>
            </div>
            <div class="f1-step" id="step-3">
               <div class="f1-step-icon">3</div>
               <p>Cargue los datos de su sucursal.</p>
            </div>
         </div>
         <br>
      </div>
         <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="panel">
               <div class="panel-heading">
                  Ingresá la información de tu empresa aquí:
               </div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Nombre *</label>
                        <input type="text" id="em_nomfan" name="em_nomfan" class="form-control"
                           placeholder="Ingresá el nombre">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Correo electrónico *</label>
                        <input type="text" id="em_email" name="em_email" class="form-control"
                           placeholder="Ingresá el correo electrónico" >
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Teléfono</label>
                        <input type="text" id="em_tel" name="em_tel" class="form-control"
                           placeholder="Ingresá el teléfono">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Contacto *</label>
                        <input type="text" id="em_cont" name="em_cont" class="form-control"
                           placeholder="Ingresá el contacto">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>País *</label>
                        <select name="em_pais" class="form-control" id="em_pais">
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
                        <input type="text" id="em_domleg" name="em_domleg" class="form-control"
                           placeholder="Ingresá el domicilio legal" >
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Rubro *</label>
                        <select name="em_rub" class="form-control" id="em_rub">
                           <option value="">Seleccioná</option>
                           @foreach($em_rub as $rs)
                           <option value="<?php echo $rs->rub_cat;?>">
                              <?php echo $rs->rub_cat;?>
                           </option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Sub Rubro</label>
                        <select name="em_rubs" class="form-control" id="em_rubs">
                           <option value="">Seleccioná</option>
                        </select>
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <p>Campos obligatorios (*)</p>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <button type="button" onclick="created_data_business()" class="btn btn-success" id="boton-1">Guardar</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-4 col-xs-12" >
            <div class="panel">
               <div class="panel-heading">
                  Actualizá el logo de tu empresa aquí:
               </div>
               <div class="panel-body">
                  <div class="form-group text-center">
                     <div class="img-emp" > <img src="<?php echo url('/img/empty.jpg');?>" alt="Turnonet" title="Turnonet" id="avs" >
                     </div>
                     <div class="form-group ">
                      <input type="file" id="attachment" accept="image/x-png,image/gif,image/jpeg" name="em_pfot" class="inputfile inputfile-1">
                        <label for="attachment" id="iborrainputfile" title="Subir una imagen desde el equipo">
							<i class="fa fa-picture-o" aria-hidden="true"></i> SELECCIONAR IMAGEN</label>
                     </div>
                                    <p class="form-desc text-center">Ingresá una imagen de 200x200 px. Del tipo .JPG, .PNG ó .GIF. </p>
					  <br>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
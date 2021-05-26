@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-8 col-sm-8 col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Actualizar cliente</h4>
         <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px">
            <div class="panel">
               <div class="panel-heading">
                  Actualizá la información de tu cliente aquí:
               </div>
               <div class="panel-body">
                  <form  method="POST" id="form">
                     <input type="hidden" id="id" name="id" value="<?php echo $user->id;?>">
                     <div class="row">
                        <div class="col-md-12 col-xs-12">
                           <div class="form-group">
                              <label>Nombre *</label>
                              <input type="text" id="name" name="name" class="form-control"
                                 placeholder="Ingresá el nombre" value="<?php echo $user->name;?>">
                           </div>
                           <?php 
                              //confinguracion de la empresa
                              $gmt = DB::table('tu_emps_con')->where('emp_id', $get_business->em_id)->where('pres_id', '0')->first();
                              ?>
                           @if(isset($gmt)!=0 && ($gmt->cf_tipval=='5' || $gmt->cf_tipval=='10'))
                           <div class="form-group">
                              <label>DNI</label>
                              <input type="text" name="dni" id="dni" class="form-control"  placeholder="Ingresá el DNI" value="<?php echo $user->dni;?>">
                           </div>
                           @endif
                           <div class="form-group">
                              <label>Correo electrónico</label>
                              <input type="text" id="email" name="email" class="form-control"
                                 placeholder="Ingresá el correo electrónico" value="<?php echo $user->email;?>">
                           </div>
                           <div class="form-group">
                              <label>Teléfono</label>
                              <input type="text" id="phone" name="phone" class="form-control"
                                 placeholder="Ingresá el teléfono" value="<?php echo $user->phone;?>">
                           </div>
                           <div class="form-group">
                              <p>Campos obligatorios (*)</p>
                           </div>
                           <div class="form-group">
                              <button type="button" onclick="update_data_cliente()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                           </div>
                  </form>
                  </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12 n-ppding">
         @include('includes.business')
      </div>
   </div>
</div>
<input type="hidden" id="redirect" value="/directorio/empresa/<?php echo $get_business->em_id;?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
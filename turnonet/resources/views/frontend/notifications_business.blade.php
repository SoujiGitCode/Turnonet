@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Notificaciones</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Configure las notificaciones de tu empresa aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" name="id" value="<?php echo $settings->pc_id;?>">
                  <div class="form-group">
                     <label>Recibir un mail por cada turno nuevo</label>
                  </div>
                  <div class="form-group">
                     <div class="demo-radio">
                        <input type="radio" name="pc_mailn" id="pc_mailn" value="1" @if($settings->pc_mailn=='1') checked="checked" @endif>
                        <label for="pc_mailn"><span></span>Si</label>
                     </div>
                     <div class="demo-radio">
                        <input type="radio" name="pc_mailn" id="pc_mailn-1" value="0" @if($settings->pc_mailn !='1') checked="checked" @endif>
                        <label for="pc_mailn-1"><span></span>No (Recomendado)</label>
                     </div>
                  </div>
                  <div class="form-group" style="clear: both;">
                     <br>
                     <label>Recibir un mail por cada cancelación de turno</label>
                  </div>
                  <div class="form-group">
                     <div class="demo-radio">
                        <input type="radio" name="pc_mailc" id="pc_mailc" value="1" @if($settings->pc_mailc=='1') checked="checked" @endif>
                        <label for="pc_mailc"><span></span>Si</label>
                     </div>
                     <div class="demo-radio">
                        <input type="radio" name="pc_mailc" id="pc_mailc-1" value="0" @if($settings->pc_mailc !='1') checked="checked" @endif>
                        <label for="pc_mailc-1"><span></span>No</label>
                     </div>
                  </div>
                  <div class="form-group" style="clear: both;">
                     <br>
                     <label>Mensaje Informativo</label>
                     {!! Form::textarea('pc_msg',$settings->pc_msg,['id'=>'message','placeholder'=>'Ingresar mensaje','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
                     <p class="form-desc">Mensaje general que recibirán sus clientes o pacientes. Ejemplo: "Traer estudios anteriores" o "No se aceptan tarjetas de crédito"</p>
                  </div>
                  <div class="form-group" style="display: none;">
                     <label>Asignar mail de respuesta (por defecto toma el de la empresa)</label>
                     <input type="text" name="pc_mailr" id="email" placeholder="Ingresá un correo electrónico" class="form-control" value="<?php echo $settings->pc_mailr;?>">
                  </div>
                   <div class="form-group">
                     <br>
                   <button type="button" onclick="update_notifications()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                </div>
               </form>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.business')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
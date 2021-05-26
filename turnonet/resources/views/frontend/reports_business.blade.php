@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Reportes</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Configurá los reportes de tu empresa aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" name="id" value="<?php echo $settings->rep_id;?>">
                  <p>Los reportes son el correo electrónico que llega con la información de turnos del día siguiente actualizado al momento de envío. Solo se enviará cuando tengas turnos agendados.</p>
                  <div class="form-group btop bdashed">
                     <label class="label-1">¿En que momento desea recibir los reportes?</label>
                  </div>
                  <div class="form-group">
                     <div class="demo-radio">
                        <input type="radio" name="rep_hora" id="rep_hora" value="17:00:00" @if($settings->rep_hora=='17:00:00') checked="checked" @endif>
                        <label for="rep_hora"><span></span>A las 17:00 PM del día anterior?</label>
                     </div>
                     <div class="demo-radio" style="clear: both;">
                        <input type="radio" name="rep_hora" id="rep_hora-1" value="20:00:00" @if($settings->rep_hora =='20:00:00') checked="checked" @endif>
                        <label for="rep_hora-1"><span></span>A las 20:00 PM del día anterior?</label>
                     </div>
                     <div class="demo-radio" style="clear: both;">
                        <input type="radio" name="rep_hora" id="rep_hora-2" value="23:00:00" @if($settings->rep_hora =='23:00:00') checked="checked" @endif>
                        <label for="rep_hora-2"><span></span>A las 23:00 PM del día anterior?</label>
                     </div>
                     <div class="demo-radio" style="clear: both;">
                        <input type="radio" name="rep_hora" id="rep_hora-3" value="06:00:00" @if($settings->rep_hora =='06:00:00') checked="checked" @endif>
                        <label for="rep_hora-3"><span></span>A las 06:00 AM del mismo dia?</label>
                     </div>
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">¿Quien desea que reciba los reportes?</label>
                     <div class="demoo1">
                        <input type="checkbox" name="rep_recemp" id="rep_recemp" value="1" @if($settings->rep_recemp=='1') checked="checked" @endif>
                        <label for="rep_recemp"><span></span>La empresa recibe todos los turnos de la misma.</label>
                     </div>
                     <div class="demoo1">
                        <input type="checkbox" name="rep_recsuc" id="rep_recsuc" value="1" @if($settings->rep_recsuc=='1') checked="checked" @endif>
                        <label for="rep_recsuc"><span></span>Cada sucursal recibe los turnos de la misma.</label>
                     </div>
                     <div class="demoo1">
                        <input type="checkbox" name="rep_recpre" id="rep_recpre" value="1" @if($settings->rep_recpre=='1') checked="checked" @endif>
                        <label for="rep_recpre"><span></span>Cada prestador recibe sus turnos.</label>
                     </div>
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">¿Como desea recibir los reportes?</label>
                     <div class="demoo1">
                        <input type="radio" name="rep_type" id="rep_type-1" value="0" @if($settings->rep_type=='0') checked="checked" @endif>
                        <label for="rep_type-1"><span></span>Reporte Estándar (Recomendado)</label>
                     </div>
                     <div class="demoo1">
                        <input type="radio" name="rep_type" id="rep_type-2" value="1" @if($settings->rep_type=='1') checked="checked" @endif>
                        <label for="rep_type-2"><span></span>Reporte con Datos Adicionales</label>
                     </div>
                     
                  </div>
                  <div class="form-group">
                     <br>
                     <button type="button" onclick="update_reports()" class="btn btn-success" id="boton-1">Actualizar datos</button>
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
@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Eliminar Empresa</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Da de baja a tu empresa aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" id="id" name="id" value="<?php echo $get_business->em_id;?>">
                  <p>Recuerda que si eliminas esta empresa, perderás las sucursales, turnos y prestadores registrados en ésta. En caso de duda o consulta comuniquese con nuestro sector de soporte.</p>
                  @if($shift!=0)
                  <div class="form-group btop" >
                     <label class="label-1">Próximos Turnos</label>
                  </div>
                  <div class="form-group">
                     <p>Tienes <?php echo $shift;?> turnos solicitados</p>
                  </div>
                  <div class="form-group">
                     <div class="demoo1">
                        <input type="checkbox" id="turnos" name="turnos" class="interest" value="1" onchange="SetTurnos()" >
                        <label for="turnos"><span></span>Cancelar los próximos turnos de la empresa.</label>
                     </div>
                  </div>
                  <div class="form-group btop" id="message-can" style="display: none" >
                     <label class="label-1">Motivo de cancelación:</label>
                     {!! Form::textarea('message',null,['id'=>'message','placeholder'=>'Ingresá un comentario','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
                  </div>
                  @endif
                  <div class="form-group">
                     <br>
                     <button type="button" onclick="baja_business()" class="btn btn-success" id="boton-1">DAR DE BAJA</button>
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
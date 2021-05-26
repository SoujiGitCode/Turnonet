@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Notificaciones</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Configure las notificaciones de tu sucursal aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" name="id" value="<?php echo $get_branch->suc_id;?>">
                  
                  <div class="form-group" style="clear: both;">
                     <p>Esta notificación debería ser distinta a la de la empresa, dando algun detalle especifico de la sede en donde se pide el turno.</p>
                     <br>
                     <label>Mensaje Informativo</label>
                     {!! Form::textarea('pc_msg',$settings->pc_msg,['id'=>'message','placeholder'=>'Ingresar mensaje','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
                     <p class="form-desc">Mensaje general que recibirán sus clientes o pacientes. Ejemplo: "Traer estudios anteriores" o "No se aceptan tarjetas de crédito"</p>
                  </div>
                  <div class="form-group">
                     <div class="demoo1">
                        <input type="checkbox" name="pc_emp_msg" id="pc_emp_msg" value="1" @if($settings->pc_emp_msg=='1') checked="checked" @endif>
                        <label for="pc_emp_msg"><span></span>Incluir mensaje de la empresa</label>
                     </div>
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
         @include('includes.branch')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" id="select_branch" value="<?php echo $get_branch->suc_id;?>">
<?php echo Html::script('frontend/js/settings_branch.js?v='.time())?>
@stop
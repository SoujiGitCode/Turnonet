@extends('layouts.template_frontend_inside')
@section('content')
<?php 
   function nameMonth($month) {
    switch ($month) {
     case 1:
     $month =  'Enero';
     break;
     case 2:
     $month =  'Febrero';
     break;
     case 3:
     $month =  'Marzo';
     break;
     case 4:
     $month =  'Abril';
     break;
     case 5:
     $month =  'Mayo';
     break;
     case 6:
     $month =  'Junio';
     break;
     case 7:
     $month =  'Julio';
     break;
     case 8:
     $month =  'Agosto';
     break;
     case 9:
     $month =  'Septiembre';
     break;
     case 10:
     $month =  'Octubre';
     break;
     case 11:
     $month =  'Noviembre';
     break;
     case 12:
     $month =  'Diciembre';
     break;
   }
   return $month;
   }
   
   function nameDay($day) {
   
    switch ($day) {
      case 0:
      $day = 'Domingo';
      break;
      case 1:
      $day ='Lunes';
      break;
      case 2:
      $day =  'Martes';
      break;
      case 3:
      $day = 'Miércoles';
      break;
      case 4:
      $day = 'Jueves';
      break;
      case 5:
      $day ='Viernes';
      break;
      case 6:
      $day = 'Sábado';
      break;
   }
   return $day;
   }
   ?>
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?> > <?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Enviar Reportes</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Envia tus reportes de turnos aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                <input type="hidden" name="name" id="name" value="<?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?>">
                <input type="hidden" name="pres_id" id="pres_id" value="<?php echo $get_lender->tmsp_id;?>">
                <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $get_business->em_id;?>">

                  <p>Los reportes son el correo electrónico que llega con la información de los turnos actualizado al momento de envío. Solo se enviará cuando tengas turnos agendados.</p>
                  <?php 
                     $week_start = date("Y-m-d", strtotime('last Monday', time()));
                     $week_end = date("Y-m-d", strtotime('next Sunday', time()));
                     $week_end = date("Y-m-d", strtotime($week_end."+ 1 days"));
                     
                     
                     $shift = DB::table('tu_turnos')
                     ->where('pres_id', $get_lender->tmsp_id)
                     ->when(!empty($week_start), function ($query) use($week_start) {
                      return $query->where('tu_fec', '>=', date("Y-m-d", strtotime($week_start)));
                     })
                     ->when(!empty($week_end), function ($query) use($week_end) {
                      return $query->where('tu_fec', '<=', date("Y-m-d", strtotime($week_end)));
                     })
                     ->count();
                     
                     
                     
                     ?>
                  @if($shift==0)
                  <div class="form-group">
                     <div class="col-md-12" style="text-align: center;">
                        <img src="<?php echo url('/');?>/uploads/icons/noresult.png" alt="No hay resultados" class="img-not-res">
                        <p>No hay turnos solicitados para esta semana</p>
                     </div>
                  </div>
                  @else
                  <div class="form-group btop bdashed">
                     <label class="label-1">¿Con cual día desea generar el reporte?</label>
                  </div>
                  <div class="form-group">
                     <?php  $e=0; ?>
                     @for($i=strtotime($week_start); $i<=strtotime($week_end); $i+=86400)
                      <?php  $e=$e+1; ?>
                     @if(DB::table('tu_turnos')->where('pres_id',$get_lender->tmsp_id)->where('tu_fec',date("Y-m-d",$i))->count()!=0)
                     <div class="demo-radio" style="clear: both;">
                        <input type="radio" name="day" id="dd-<?php echo $e;?>" value="<?php echo date("Y-m-d",$i);?>" @if($e==1) checked="checked" @endif>
                        <label for="dd-<?php echo $e;?>"><span></span><?php echo nameDay(date("w",$i));?> <?php echo date("d",$i);?> de <?php echo nameMonth(date("m",$i));?>, del <?php echo date("Y",$i);?></label>
                     </div>
                     @endif
                     @endfor
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">¿Quien desea que reciba el reporte?</label>
                     <input type="text" id="tmsp_pmail" name="tmsp_pmail" class="form-control"
                        placeholder="Ingresá el correo electrónico" value="<?php echo $get_lender->tmsp_pmail;?>">
                     <p class="form-desc">Ingrese sus direcciones de correo separadas por coma (,) </p>
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">¿Como desea recibir el reporte?</label>
                     <div class="demoo1">
                        <input type="radio" name="rep_type" id="rep_type-1" value="0" checked="checked">
                        <label for="rep_type-1"><span></span>Reporte Estándar (Recomendado)</label>
                     </div>
                     <div class="demoo1">
                        <input type="radio" name="rep_type" id="rep_type-2" value="1" >
                        <label for="rep_type-2"><span></span>Reporte con Datos Adicionales</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <br>
                     <button type="button" onclick="send_reports()" class="btn btn-success" id="boton-1">Enviar Reporte</button>
                  </div>
                  @endif
               </form>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.lenders')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" id="select_branch" value="<?php echo $get_branch->suc_id;?>">
<input type="hidden" id="select_lender" value="<?php echo $get_lender->tmsp_id;?>">
<?php echo Html::script('frontend/js/settings_lenders.js?v='.time())?>
@stop
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
         <h4 class="title-date-2">Generar Reportes</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Genera tus reportes de turnos aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                <input type="hidden" name="name" id="name" value="<?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?>">
                <input type="hidden" name="pres_id" id="pres_id" value="<?php echo $get_lender->tmsp_id;?>">
                <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $get_business->em_id;?>">

                  <p>Los reportes son el correo electrónico que llega con la información de los turnos actualizado al momento de envío. Solo se enviará cuando tengas turnos agendados.</p>
                  <div class="form-group btop bdashed">
                     <label class="label-1">¿Con cual día desea generar el reporte?</label>
                  </div>
                  <div class="form-group">
                      <input type='text' id='day' name="day" class="form-control" value="<?php echo date('d-m-Y');?>" placeholder="Selecciona un día" />
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">¿Como desea recibir el reporte?</label>
                     <div class="demoo1">
                        <input type="radio" name="rep_type" id="rep_type-1" value="0" @if($settings->rep_type=='0') checked="checked" @endif>
                        <label for="rep_type-1"><span></span>Reporte Estándar (Recomendado)</label>
                     </div>
                     <div class="demoo1">
                        <input type="radio" name="rep_type" id="rep_type-2" value="1" @if($settings->rep_type=='1') checked="checked" @endif >
                        <label for="rep_type-2"><span></span>Reporte con Datos Adicionales</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <br>
                     <button type="button" onclick="send_reports_lender()" class="btn btn-success" id="boton-1">Enviar Por Correo</button>
                     <button class="btn btn-success" type="button" onclick="download_report_lender()" style="background:#FF5722!important;border:1px solid #FF5722!important;"><i class="fa fa-download" aria-hidden="true"></i> Descargar PDF en el navegador</button>
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
<script type="text/javascript">
  $(function() {
    $('#day').datetimepicker({
        format: 'DD-MM-YYYY'
    });
});
</script>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" id="select_branch" value="<?php echo $get_branch->suc_id;?>">
<input type="hidden" id="select_lender" value="<?php echo $get_lender->tmsp_id;?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
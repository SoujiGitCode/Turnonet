<?php 
$get_country=DB::table('tu_pais')->where('pa_id', $get_business->em_pais)->first();
date_default_timezone_set($get_country->time_zone);
?>


@extends('layouts.template_frontend_inside')
@section('content')
<?php 
   function nameDay($day) {
   
           switch ($day) {
               case 0:
               $day = 'Dom';
               break;
               case 1:
               $day = 'Lun';
               break;
               case 2:
               $day = 'Mar';
               break;
               case 3:
               $day = 'Mie';
               break;
               case 4:
               $day = 'Jue';
               break;
               case 5:
               $day = 'Vie';
               break;
               case 6:
               $day = 'Sáb';
               break;
           }
           return $day;
    }
   ?>
<?php echo Html::style('frontend/css/morris.css')?>
<?php echo Html::script('frontend/js/raphael-min.js')?>
 <?php echo Html::script('frontend/js/morris.min.js')?>
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Estadisticas</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12" style="clear: both;"> 
               <input type='text' id='fecha_inicial' class="form-control" value="<?php echo date('d-m-Y',strtotime($init));?>" placeholder="Desde" />
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
               <input type='text' id='fecha_final' class="form-control"  value="<?php echo date('d-m-Y',strtotime($end));?>" placeholder="Hasta" />
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
               <button  class="btn btn-success btn-block btn-excel" onclick="buscar_fecha()" style="background: #FF5722!important; border: 1px solid #FF5722!important"><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
            </div>
            @if($shifts==0)
            <div class="col-md-12" style="text-align: center;">
               <img src="<?php echo url('/');?>/uploads/icons/noresult.png" alt="No hay resultados" class="img-not-res">
               <p>No hay datos para la el rango de fecha seleccionado</p>
            </div>
            @else
            <div class="col-md-7 col-sm-7 col-xs-12">
               <div class="panel">
                  <div class="panel-heading">
                     Top 5 de Prestadores
                  </div>
                  <div class="panel-body">
                     <div class="table-responsive">
                        <table width="100%" class="table" id="sample-table-1" >
                           <thead>
                              <tr>
                                 <th style="width: 10%">Foto</th>
                                 <th style="width: 50%">Nombre</th>
                                 <th style="width: 20%; text-align: center;">Turnos</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($lenders as $rs)
                              <?php  $lender=DB::table('tu_tmsp')->where('tmsp_id',$rs->pres_id)->first();  ?>
                              <tr >
                                 <td style="vertical-align: middle; text-align: center;">
                                    <?php 
                                       $image = url('/') . '/img/empty.jpg';
                                                                      if ($lender->tmsp_pfot != "") {
                                                          $image = "https://www.turnonet.com/fotos/prestadores/" . $lender->tmsp_pfot;
                                                      }
                                       
                                       
                                       
                                                                      ?>
                                    <img src="<?php echo $image;?>" class="img-pres-table">
                                 </td>
                                 <td style="vertical-align: middle;">
                                    <div style="text-transform: capitalize;"><?php echo $lender->tmsp_tit;?> <?php echo $lender->tmsp_pnom;?> </div>
                                    <div></div>
                                 </td>
                                 <td style="vertical-align: middle; text-align: center;"><?php echo $rs->total;?></td>
                              </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-12">
              <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-30 text-center">
                  <div class="card-body">
                     <i class="fa fa-calendar-o" aria-hidden="true"></i>
                     <div class="content">
                        <p class="text-muted mt-2 mb-0">Agendados</p>
                        <p class="text-primary text-24 line-height-1 mb-2"><?php echo $shifts;?></p>
                     </div>
                  </div>
               </div>
               <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-30 text-center">
                  <div class="card-body">
                     <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                     <div class="content">
                        <p class="text-muted mt-2 mb-0">Atendidos</p>
                        <p class="text-primary text-24 line-height-1 mb-2"><?php echo $actives;?></p>
                     </div>
                  </div>
               </div>
               <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-30 text-center">
                  <div class="card-body">
                     <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
                     <div class="content">
                        <p class="text-muted mt-2 mb-0">Cancelados</p>
                        <p class="text-primary text-24 line-height-1 mb-2"><?php echo $cancels;?></p>
                     </div>
                  </div>
               </div>
               <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-30 text-center">
                  <div class="card-body">
                     <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                     <div class="content">
                        <p class="text-muted mt-2 mb-0">Sobreturnos</p>
                        <p class="text-primary text-24 line-height-1 mb-2"><?php echo $overturn;?></p>
                     </div>
                  </div>
               </div>
               
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="panel">
                  <div class="panel-heading">
                     Registro de turnos
                  </div>
                  <div class="panel-body">
                     <div id="hero-graph"></div>
                  </div>
               </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
               <div class="panel">
                  <div class="panel-heading">
                     Estado de Tunos
                  </div>
                  <div class="panel-body">
                     <div id="donut"></div>
                  </div>
               </div>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
               <div class="panel">
                  <div class="panel-heading">
                     Turnos por hora del día
                  </div>
                  <div class="panel-body">
                     <div id="hero-graph-2"></div>
                  </div>
               </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
               <hr>
               <br>
               <button  class="btn btn-success btn-excel" onclick="downloadPDF()" title="Descargar estadisticas en PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Descargá  PDF</button>
            </div>
            @endif
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.business')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
<script type="text/javascript">
   $(function() {
    $('#fecha_inicial').datetimepicker({
      format: 'DD-MM-YYYY'
    });
    $('#fecha_final').datetimepicker({
      format: 'DD-MM-YYYY'
    });
  });
   <?php 
      $Objdays=array();
      foreach($days as $rs): 
        $Objdays[]=array(
            'y'=>nameDay(date("w",strtotime($rs->tu_fec))).' '.date("d",strtotime($rs->tu_fec)),
           'item1'=>$rs->total
        );
      endforeach;
      ?> 
   
   
   
     var line = new Morris.Line({
        element: 'hero-graph',
        resize: true,
         parseTime: false,
        data: <?php echo json_encode($Objdays);?>,
        xkey: 'y',
        ykeys: ['item1'],
        labels: ['Turnos'],
        lineColors: ['#ff8d68'],
        hideHover: 'auto'
      });
   
   
   <?php 
      $Objhours=array();
      foreach($hours as $rs): 
        $Objhours[]=array(
            'y'=>date("H:i",strtotime($rs->tu_hora)),
           'item1'=>$rs->total
        );
      endforeach;
      ?> 
   
   
   
     var line_2 = new Morris.Line({
        element: 'hero-graph-2',
        resize: true,
         parseTime: false,
        data: <?php echo json_encode($Objhours);?>,
        xkey: 'y',
        ykeys: ['item1'],
        labels: ['Turnos'],
        lineColors: ['#808080'],
        hideHover: 'auto'
      });
   
   
   
   
   
    <?php 
        $total=$asistencia+$ausencia+$parcial+$no_defined; 
       
      ?>
   

   
     var donut = new Morris.Donut({
       element: 'donut',
       resize: true,
       colors: ["#2cc507", "#ff5722", "#7970ff","#808080"],
       data: [
       {label: "Asistencia", value: <?php echo $asistencia;?>},
       {label: "Ausencia", value: <?php echo $ausencia;?>},
       {label: "Asis. Parcial", value: <?php echo $parcial;?>},
       {label: "Sin definir", value: <?php echo $no_defined;?>}
       ],
       hideHover: 'auto'
     });
     
     
     function buscar_fecha(){
     if ($('#fecha_inicial').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_inicial').focus();
            return (false);
        }
        if ($('#fecha_final').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_final').focus();
            return (false);
        }else{
          var fecha_inicial =document.getElementById("fecha_inicial").value;
          var fecha_final =document.getElementById("fecha_final").value;
         window.location="<?php echo url('/');?>/empresa/estadisticas/filtrar/<?php echo $get_business->em_id;?>/"+fecha_inicial+"/"+fecha_final;
        }
      

   }

   function downloadPDF(){
   window.open('<?php echo url("pdf/estadisticas/".$get_business->em_id."/".date('d-m-Y',strtotime($init))."/".date('d-m-Y',strtotime($end)));?>');
 }
     
</script>
@stop
@extends('layouts.template_frontend_inside')
@section('content')
<?php
   function nameDay($day) {
      
              switch ($day) {
                  case 0:
                  $day = 'Domingo';
                  break;
                  case 1:
                  $day = 'Lunes';
                  break;
                  case 2:
                  $day = 'Martes';
                  break;
                  case 3:
                  $day = 'Miércoles';
                  break;
                  case 4:
                  $day = 'Jueves';
                  break;
                  case 5:
                  $day = 'Viernes';
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
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?> > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Días y horarios de atención</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
          <div class="panel hidden-lg hidden-md hidden-sm">
            <div class="panel-heading">
               Configurá los días y horarios de atención de tu empresa aquí:
            </div>
            <div class="panel-body">
               <br>
               <div class="alert alert-warning" role="alert">
                  Iniciá sesión desde un computador para administrar esta sección.
               </div>
               <br>
            </div>
         </div>
         <div class="panel hidden-xs">
            <div class="panel-heading">
               Configurá los días y horarios de atención de tu empresa aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" name="em_id" value="<?php echo $get_business->em_id;?>">
                  <input type="hidden" name="id" value="<?php echo $get_branch->suc_id;?>">
                  <p>Seleccione en que días y rangos horarios desea otorgar turnos para esta sucursal. En caso de trabajar de corrido solo usar el horario 1. Ej. Horario corrido: 9 a 18 hs. / Horario cortado: 9 a 14 y 17 a 20 hs. Esta configuración tomara efecto en todos aquellos prestadores que no tengan configurado sus propios días y horarios, y que su Sucursal correspondiente tampoco se encuentre configurado.</p>
                  <div class="form-group btop " >
                     <label class="label-1">Horarios de atención:</label>
                  </div>
                  <?php 
                  $count_shedules=DB::table('tu_dlab')->where('lab_empid',$get_business->em_id)->where('lab_sucid',$get_branch->suc_id)->where('lab_presid',0)->count();
                  ?>
                  @if( $count_shedules==0)
                  <div class="alert alert-danger" role="alert">
                     No tienes configurado ningún dia y horario para esta sucursal, los turnos estan tomando la configuración de la empresa. <a href="<?php echo url('empresa/horarios/'.$get_business->em_id);?>">Ver configuración</a>
                  </div>
                  @endif

                  <div class="form-group">
                     <div class="demoo1">
                        <input type="checkbox" name="check_up" id="check_up" value="1" onchange="setShedules()">
                        <label for="check_up"><span></span>Modificar</label>
                     </div>
                  </div>
                  <div class="form-group bdashed pptop" style="width: 100%; clear: both;">
                     <div class="row mm-bt">
                        <div class="col-md-55"> 
                           <label>Día/Horario</label>
                        </div>
                        <div class="col-md-55 text-center">
                           <label>Desde 1</label>
                        </div>
                        <div class="col-md-55 text-center">
                           <label>Hasta 1</label>
                        </div>
                        <div class="col-md-55 text-center">
                           <label>Desde 2</label>
                        </div>
                        <div class="col-md-55 text-center">
                           <label>Hasta 2</label>
                        </div>
                     </div>
                     @for($i=0;$i<7;$i++)
                     <?php 
                        $shedules=DB::table('tu_dlab')->where('lab_dian',$i)->where('lab_empid',$get_business->em_id)->where('lab_sucid',$get_branch->suc_id)->where('lab_presid',0)->first();
                        
                           ?>
                     @if(isset($shedules)!=0)
                     <div class="row">
                        <div class="col-md-55"> 
                           <label class="label-date"><?php echo nameDay($i);?></label>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_init[]" class="form-control form-date-1" id="reg_init_1<?php echo $i;?>"  disabled="disabled">
                              <option value="">--Desde--</option>
                              <option value="06:00:00" @if($shedules->lab_hin=="06:00:00") selected="selected" @endif>06:00</option>
                              <option value="06:10:00" @if($shedules->lab_hin=="06:10:00") selected="selected" @endif >06:10</option>
                              <option value="06:20:00" @if($shedules->lab_hin=="06:20:00") selected="selected" @endif >06:20</option>
                              <option value="06:30:00" @if($shedules->lab_hin=="06:30:00") selected="selected" @endif >06:30</option>
                              <option value="06:40:00" @if($shedules->lab_hin=="06:40:00") selected="selected" @endif >06:40</option>
                              <option value="06:50:00" @if($shedules->lab_hin=="06:50:00") selected="selected" @endif >06:50</option>
                              <option value="07:00:00" @if($shedules->lab_hin=="07:00:00") selected="selected" @endif >07:00</option>
                              <option value="07:10:00" @if($shedules->lab_hin=="07:10:00") selected="selected" @endif >07:10</option>
                              <option value="07:20:00" @if($shedules->lab_hin=="07:20:00") selected="selected" @endif >07:20</option>
                              <option value="07:30:00" @if($shedules->lab_hin=="07:30:00") selected="selected" @endif >07:30</option>
                              <option value="07:40:00" @if($shedules->lab_hin=="07:40:00") selected="selected" @endif >07:40</option>
                              <option value="07:50:00" @if($shedules->lab_hin=="07:50:00") selected="selected" @endif >07:50</option>
                              <option value="08:00:00" @if($shedules->lab_hin=="08:00:00") selected="selected" @endif >08:00</option>
                              <option value="08:10:00" @if($shedules->lab_hin=="08:10:00") selected="selected" @endif >08:10</option>
                              <option value="08:20:00" @if($shedules->lab_hin=="08:20:00") selected="selected" @endif >08:20</option>
                              <option value="08:30:00" @if($shedules->lab_hin=="08:30:00") selected="selected" @endif >08:30</option>
                              <option value="08:40:00" @if($shedules->lab_hin=="08:40:00") selected="selected" @endif >08:40</option>
                              <option value="08:50:00" @if($shedules->lab_hin=="08:50:00") selected="selected" @endif >08:50</option>
                              <option value="09:00:00" @if($shedules->lab_hin=="09:00:00") selected="selected" @endif >09:00</option>
                              <option value="09:10:00" @if($shedules->lab_hin=="09:10:00") selected="selected" @endif >09:10</option>
                              <option value="09:20:00" @if($shedules->lab_hin=="09:20:00") selected="selected" @endif >09:20</option>
                              <option value="09:30:00" @if($shedules->lab_hin=="09:30:00") selected="selected" @endif >09:30</option>
                              <option value="09:40:00" @if($shedules->lab_hin=="09:40:00") selected="selected" @endif >09:40</option>
                              <option value="09:50:00" @if($shedules->lab_hin=="09:50:00") selected="selected" @endif >09:50</option>
                              <option value="10:00:00" @if($shedules->lab_hin=="10:00:00") selected="selected" @endif >10:00</option>
                              <option value="10:10:00" @if($shedules->lab_hin=="10:10:00") selected="selected" @endif >10:10</option>
                              <option value="10:20:00" @if($shedules->lab_hin=="10:20:00") selected="selected" @endif >10:20</option>
                              <option value="10:30:00" @if($shedules->lab_hin=="10:30:00") selected="selected" @endif >10:30</option>
                              <option value="10:40:00" @if($shedules->lab_hin=="10:40:00") selected="selected" @endif >10:40</option>
                              <option value="10:50:00" @if($shedules->lab_hin=="10:50:00") selected="selected" @endif >10:50</option>
                              <option value="11:00:00" @if($shedules->lab_hin=="11:00:00") selected="selected" @endif >11:00</option>
                              <option value="11:10:00" @if($shedules->lab_hin=="11:10:00") selected="selected" @endif >11:10</option>
                              <option value="11:20:00" @if($shedules->lab_hin=="11:20:00") selected="selected" @endif >11:20</option>
                              <option value="11:30:00" @if($shedules->lab_hin=="11:30:00") selected="selected" @endif >11:30</option>
                              <option value="11:40:00" @if($shedules->lab_hin=="11:40:00") selected="selected" @endif >11:40</option>
                              <option value="11:50:00" @if($shedules->lab_hin=="11:50:00") selected="selected" @endif >11:50</option>
                              <option value="12:00:00" @if($shedules->lab_hin=="12:00:00") selected="selected" @endif >12:00</option>
                              <option value="12:10:00" @if($shedules->lab_hin=="12:10:00") selected="selected" @endif >12:10</option>
                              <option value="12:20:00" @if($shedules->lab_hin=="12:20:00") selected="selected" @endif >12:20</option>
                              <option value="12:30:00" @if($shedules->lab_hin=="12:30:00") selected="selected" @endif >12:30</option>
                              <option value="12:40:00" @if($shedules->lab_hin=="12:40:00") selected="selected" @endif >12:40</option>
                              <option value="12:50:00" @if($shedules->lab_hin=="12:50:00") selected="selected" @endif >12:50</option>
                              <option value="13:00:00" @if($shedules->lab_hin=="13:00:00") selected="selected" @endif >13:00</option>  
                              <option value="13:10:00" @if($shedules->lab_hin=="13:10:00") selected="selected" @endif >13:10</option>
                              <option value="13:20:00" @if($shedules->lab_hin=="13:20:00") selected="selected" @endif >13:20</option>
                              <option value="13:30:00" @if($shedules->lab_hin=="13:30:00") selected="selected" @endif >13:30</option>
                              <option value="13:40:00" @if($shedules->lab_hin=="13:40:00") selected="selected" @endif >13:40</option>
                              <option value="13:50:00" @if($shedules->lab_hin=="13:50:00") selected="selected" @endif >13:50</option>
                              <option value="14:00:00" @if($shedules->lab_hin=="14:00:00") selected="selected" @endif >14:00</option>
                              <option value="14:10:00" @if($shedules->lab_hin=="14:10:00") selected="selected" @endif >14:10</option>
                              <option value="14:20:00" @if($shedules->lab_hin=="14:20:00") selected="selected" @endif >14:20</option>
                              <option value="14:30:00" @if($shedules->lab_hin=="14:30:00") selected="selected" @endif >14:30</option>
                              <option value="14:40:00" @if($shedules->lab_hin=="14:40:00") selected="selected" @endif >14:40</option>
                              <option value="14:50:00" @if($shedules->lab_hin=="14:50:00") selected="selected" @endif >14:50</option>
                              <option value="15:00:00" @if($shedules->lab_hin=="15:00:00") selected="selected" @endif >15:00</option>
                              <option value="15:10:00" @if($shedules->lab_hin=="15:10:00") selected="selected" @endif >15:10</option>
                              <option value="15:20:00" @if($shedules->lab_hin=="15:20:00") selected="selected" @endif >15:20</option>
                              <option value="15:30:00" @if($shedules->lab_hin=="15:30:00") selected="selected" @endif >15:30</option>
                              <option value="15:40:00" @if($shedules->lab_hin=="15:40:00") selected="selected" @endif >15:40</option>
                              <option value="15:50:00" @if($shedules->lab_hin=="15:50:00") selected="selected" @endif >15:50</option>
                              <option value="16:00:00" @if($shedules->lab_hin=="16:00:00") selected="selected" @endif >16:00</option>
                              <option value="16:10:00" @if($shedules->lab_hin=="16:10:00") selected="selected" @endif >16:10</option>
                              <option value="16:20:00" @if($shedules->lab_hin=="16:20:00") selected="selected" @endif >16:20</option>
                              <option value="16:30:00" @if($shedules->lab_hin=="16:30:00") selected="selected" @endif >16:30</option>
                              <option value="16:40:00" @if($shedules->lab_hin=="16:40:00") selected="selected" @endif >16:40</option>
                              <option value="16:50:00" @if($shedules->lab_hin=="16:50:00") selected="selected" @endif >16:50</option>
                              <option value="17:00:00" @if($shedules->lab_hin=="17:00:00") selected="selected" @endif >17:00</option>
                              <option value="17:10:00" @if($shedules->lab_hin=="17:10:00") selected="selected" @endif >17:10</option>
                              <option value="17:20:00" @if($shedules->lab_hin=="17:20:00") selected="selected" @endif >17:20</option>
                              <option value="17:30:00" @if($shedules->lab_hin=="17:30:00") selected="selected" @endif >17:30</option>
                              <option value="17:40:00" @if($shedules->lab_hin=="17:40:00") selected="selected" @endif >17:40</option>
                              <option value="17:50:00" @if($shedules->lab_hin=="17:50:00") selected="selected" @endif >17:50</option>
                              <option value="18:00:00" @if($shedules->lab_hin=="18:00:00") selected="selected" @endif >18:00</option>
                              <option value="18:10:00" @if($shedules->lab_hin=="18:10:00") selected="selected" @endif >18:10</option>
                              <option value="18:20:00" @if($shedules->lab_hin=="18:20:00") selected="selected" @endif >18:20</option>
                              <option value="18:30:00" @if($shedules->lab_hin=="18:30:00") selected="selected" @endif >18:30</option>
                              <option value="18:40:00" @if($shedules->lab_hin=="18:40:00") selected="selected" @endif >18:40</option>
                              <option value="18:50:00" @if($shedules->lab_hin=="18:50:00") selected="selected" @endif >18:50</option>
                              <option value="19:00:00" @if($shedules->lab_hin=="19:00:00") selected="selected" @endif >19:00</option>
                              <option value="19:10:00" @if($shedules->lab_hin=="19:10:00") selected="selected" @endif >19:10</option>
                              <option value="19:20:00" @if($shedules->lab_hin=="19:20:00") selected="selected" @endif >19:20</option>
                              <option value="19:30:00" @if($shedules->lab_hin=="19:30:00") selected="selected" @endif >19:30</option>
                              <option value="19:40:00" @if($shedules->lab_hin=="19:40:00") selected="selected" @endif >19:40</option>
                              <option value="19:50:00" @if($shedules->lab_hin=="19:50:00") selected="selected" @endif >19:50</option>
                              <option value="20:00:00" @if($shedules->lab_hin=="20:00:00") selected="selected" @endif >20:00</option>
                              <option value="20:10:00" @if($shedules->lab_hin=="20:10:00") selected="selected" @endif >20:10</option>
                              <option value="20:20:00" @if($shedules->lab_hin=="20:20:00") selected="selected" @endif >20:20</option>
                              <option value="20:30:00" @if($shedules->lab_hin=="20:30:00") selected="selected" @endif >20:30</option>
                              <option value="20:40:00" @if($shedules->lab_hin=="20:40:00") selected="selected" @endif >20:40</option>
                              <option value="20:50:00" @if($shedules->lab_hin=="20:50:00") selected="selected" @endif >20:50</option>
                              <option value="21:00:00" @if($shedules->lab_hin=="21:00:00") selected="selected" @endif >21:00</option>
                              <option value="21:10:00" @if($shedules->lab_hin=="21:10:00") selected="selected" @endif >21:10</option>
                              <option value="21:20:00" @if($shedules->lab_hin=="21:20:00") selected="selected" @endif >21:20</option>
                              <option value="21:30:00" @if($shedules->lab_hin=="21:30:00") selected="selected" @endif >21:30</option>
                              <option value="21:40:00" @if($shedules->lab_hin=="21:40:00") selected="selected" @endif >21:40</option>
                              <option value="21:50:00" @if($shedules->lab_hin=="21:50:00") selected="selected" @endif >21:50</option>
                              <option value="22:00:00" @if($shedules->lab_hin=="22:00:00") selected="selected" @endif >22:00</option>
                              <option value="22:10:00" @if($shedules->lab_hin=="22:10:00") selected="selected" @endif >22:10</option>
                              <option value="22:20:00" @if($shedules->lab_hin=="22:20:00") selected="selected" @endif >22:20</option>
                              <option value="22:30:00" @if($shedules->lab_hin=="22:30:00") selected="selected" @endif >22:30</option>
                              <option value="22:40:00" @if($shedules->lab_hin=="22:40:00") selected="selected" @endif >22:40</option>
                              <option value="22:50:00" @if($shedules->lab_hin=="22:50:00") selected="selected" @endif >22:50</option>
                              <option value="23:00:00" @if($shedules->lab_hin=="23:00:00") selected="selected" @endif >23:00</option>
                              <option value="23:10:00" @if($shedules->lab_hin=="23:10:00") selected="selected" @endif >23:10</option>
                              <option value="23:20:00" @if($shedules->lab_hin=="23:20:00") selected="selected" @endif >23:20</option>
                              <option value="23:30:00" @if($shedules->lab_hin=="23:30:00") selected="selected" @endif >23:30</option>
                              <option value="23:40:00" @if($shedules->lab_hin=="23:40:00") selected="selected" @endif >23:40</option>
                              <option value="23:50:00" @if($shedules->lab_hin=="23:50:00") selected="selected" @endif >23:50</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_end[]" class="form-control form-date-1" id="reg_end_1<?php echo $i;?>" disabled="disabled">
                              <option value="">--Hasta--</option>
                              <option value="06:00:00" @if($shedules->lab_hou=="06:00:00") selected="selected" @endif>06:00</option>
                              <option value="06:10:00" @if($shedules->lab_hou=="06:10:00") selected="selected" @endif >06:10</option>
                              <option value="06:20:00" @if($shedules->lab_hou=="06:20:00") selected="selected" @endif >06:20</option>
                              <option value="06:30:00" @if($shedules->lab_hou=="06:30:00") selected="selected" @endif >06:30</option>
                              <option value="06:40:00" @if($shedules->lab_hou=="06:40:00") selected="selected" @endif >06:40</option>
                              <option value="06:50:00" @if($shedules->lab_hou=="06:50:00") selected="selected" @endif >06:50</option>
                              <option value="07:00:00" @if($shedules->lab_hou=="07:00:00") selected="selected" @endif >07:00</option>
                              <option value="07:10:00" @if($shedules->lab_hou=="07:10:00") selected="selected" @endif >07:10</option>
                              <option value="07:20:00" @if($shedules->lab_hou=="07:20:00") selected="selected" @endif >07:20</option>
                              <option value="07:30:00" @if($shedules->lab_hou=="07:30:00") selected="selected" @endif >07:30</option>
                              <option value="07:40:00" @if($shedules->lab_hou=="07:40:00") selected="selected" @endif >07:40</option>
                              <option value="07:50:00" @if($shedules->lab_hou=="07:50:00") selected="selected" @endif >07:50</option>
                              <option value="08:00:00" @if($shedules->lab_hou=="08:00:00") selected="selected" @endif >08:00</option>
                              <option value="08:10:00" @if($shedules->lab_hou=="08:10:00") selected="selected" @endif >08:10</option>
                              <option value="08:20:00" @if($shedules->lab_hou=="08:20:00") selected="selected" @endif >08:20</option>
                              <option value="08:30:00" @if($shedules->lab_hou=="08:30:00") selected="selected" @endif >08:30</option>
                              <option value="08:40:00" @if($shedules->lab_hou=="08:40:00") selected="selected" @endif >08:40</option>
                              <option value="08:50:00" @if($shedules->lab_hou=="08:50:00") selected="selected" @endif >08:50</option>
                              <option value="09:00:00" @if($shedules->lab_hou=="09:00:00") selected="selected" @endif >09:00</option>
                              <option value="09:10:00" @if($shedules->lab_hou=="09:10:00") selected="selected" @endif >09:10</option>
                              <option value="09:20:00" @if($shedules->lab_hou=="09:20:00") selected="selected" @endif >09:20</option>
                              <option value="09:30:00" @if($shedules->lab_hou=="09:30:00") selected="selected" @endif >09:30</option>
                              <option value="09:40:00" @if($shedules->lab_hou=="09:40:00") selected="selected" @endif >09:40</option>
                              <option value="09:50:00" @if($shedules->lab_hou=="09:50:00") selected="selected" @endif >09:50</option>
                              <option value="10:00:00" @if($shedules->lab_hou=="10:00:00") selected="selected" @endif >10:00</option>
                              <option value="10:10:00" @if($shedules->lab_hou=="10:10:00") selected="selected" @endif >10:10</option>
                              <option value="10:20:00" @if($shedules->lab_hou=="10:20:00") selected="selected" @endif >10:20</option>
                              <option value="10:30:00" @if($shedules->lab_hou=="10:30:00") selected="selected" @endif >10:30</option>
                              <option value="10:40:00" @if($shedules->lab_hou=="10:40:00") selected="selected" @endif >10:40</option>
                              <option value="10:50:00" @if($shedules->lab_hou=="10:50:00") selected="selected" @endif >10:50</option>
                              <option value="11:00:00" @if($shedules->lab_hou=="11:00:00") selected="selected" @endif >11:00</option>
                              <option value="11:10:00" @if($shedules->lab_hou=="11:10:00") selected="selected" @endif >11:10</option>
                              <option value="11:20:00" @if($shedules->lab_hou=="11:20:00") selected="selected" @endif >11:20</option>
                              <option value="11:30:00" @if($shedules->lab_hou=="11:30:00") selected="selected" @endif >11:30</option>
                              <option value="11:40:00" @if($shedules->lab_hou=="11:40:00") selected="selected" @endif >11:40</option>
                              <option value="11:50:00" @if($shedules->lab_hou=="11:50:00") selected="selected" @endif >11:50</option>
                              <option value="12:00:00" @if($shedules->lab_hou=="12:00:00") selected="selected" @endif >12:00</option>
                              <option value="12:10:00" @if($shedules->lab_hou=="12:10:00") selected="selected" @endif >12:10</option>
                              <option value="12:20:00" @if($shedules->lab_hou=="12:20:00") selected="selected" @endif >12:20</option>
                              <option value="12:30:00" @if($shedules->lab_hou=="12:30:00") selected="selected" @endif >12:30</option>
                              <option value="12:40:00" @if($shedules->lab_hou=="12:40:00") selected="selected" @endif >12:40</option>
                              <option value="12:50:00" @if($shedules->lab_hou=="12:50:00") selected="selected" @endif >12:50</option>
                              <option value="13:00:00" @if($shedules->lab_hou=="13:00:00") selected="selected" @endif >13:00</option>  
                              <option value="13:10:00" @if($shedules->lab_hou=="13:10:00") selected="selected" @endif >13:10</option>
                              <option value="13:20:00" @if($shedules->lab_hou=="13:20:00") selected="selected" @endif >13:20</option>
                              <option value="13:30:00" @if($shedules->lab_hou=="13:30:00") selected="selected" @endif >13:30</option>
                              <option value="13:40:00" @if($shedules->lab_hou=="13:40:00") selected="selected" @endif >13:40</option>
                              <option value="13:50:00" @if($shedules->lab_hou=="13:50:00") selected="selected" @endif >13:50</option>
                              <option value="14:00:00" @if($shedules->lab_hou=="14:00:00") selected="selected" @endif >14:00</option>
                              <option value="14:10:00" @if($shedules->lab_hou=="14:10:00") selected="selected" @endif >14:10</option>
                              <option value="14:20:00" @if($shedules->lab_hou=="14:20:00") selected="selected" @endif >14:20</option>
                              <option value="14:30:00" @if($shedules->lab_hou=="14:30:00") selected="selected" @endif >14:30</option>
                              <option value="14:40:00" @if($shedules->lab_hou=="14:40:00") selected="selected" @endif >14:40</option>
                              <option value="14:50:00" @if($shedules->lab_hou=="14:50:00") selected="selected" @endif >14:50</option>
                              <option value="15:00:00" @if($shedules->lab_hou=="15:00:00") selected="selected" @endif >15:00</option>
                              <option value="15:10:00" @if($shedules->lab_hou=="15:10:00") selected="selected" @endif >15:10</option>
                              <option value="15:20:00" @if($shedules->lab_hou=="15:20:00") selected="selected" @endif >15:20</option>
                              <option value="15:30:00" @if($shedules->lab_hou=="15:30:00") selected="selected" @endif >15:30</option>
                              <option value="15:40:00" @if($shedules->lab_hou=="15:40:00") selected="selected" @endif >15:40</option>
                              <option value="15:50:00" @if($shedules->lab_hou=="15:50:00") selected="selected" @endif >15:50</option>
                              <option value="16:00:00" @if($shedules->lab_hou=="16:00:00") selected="selected" @endif >16:00</option>
                              <option value="16:10:00" @if($shedules->lab_hou=="16:10:00") selected="selected" @endif >16:10</option>
                              <option value="16:20:00" @if($shedules->lab_hou=="16:20:00") selected="selected" @endif >16:20</option>
                              <option value="16:30:00" @if($shedules->lab_hou=="16:30:00") selected="selected" @endif >16:30</option>
                              <option value="16:40:00" @if($shedules->lab_hou=="16:40:00") selected="selected" @endif >16:40</option>
                              <option value="16:50:00" @if($shedules->lab_hou=="16:50:00") selected="selected" @endif >16:50</option>
                              <option value="17:00:00" @if($shedules->lab_hou=="17:00:00") selected="selected" @endif >17:00</option>
                              <option value="17:10:00" @if($shedules->lab_hou=="17:10:00") selected="selected" @endif >17:10</option>
                              <option value="17:20:00" @if($shedules->lab_hou=="17:20:00") selected="selected" @endif >17:20</option>
                              <option value="17:30:00" @if($shedules->lab_hou=="17:30:00") selected="selected" @endif >17:30</option>
                              <option value="17:40:00" @if($shedules->lab_hou=="17:40:00") selected="selected" @endif >17:40</option>
                              <option value="17:50:00" @if($shedules->lab_hou=="17:50:00") selected="selected" @endif >17:50</option>
                              <option value="18:00:00" @if($shedules->lab_hou=="18:00:00") selected="selected" @endif >18:00</option>
                              <option value="18:10:00" @if($shedules->lab_hou=="18:10:00") selected="selected" @endif >18:10</option>
                              <option value="18:20:00" @if($shedules->lab_hou=="18:20:00") selected="selected" @endif >18:20</option>
                              <option value="18:30:00" @if($shedules->lab_hou=="18:30:00") selected="selected" @endif >18:30</option>
                              <option value="18:40:00" @if($shedules->lab_hou=="18:40:00") selected="selected" @endif >18:40</option>
                              <option value="18:50:00" @if($shedules->lab_hou=="18:50:00") selected="selected" @endif >18:50</option>
                              <option value="19:00:00" @if($shedules->lab_hou=="19:00:00") selected="selected" @endif >19:00</option>
                              <option value="19:10:00" @if($shedules->lab_hou=="19:10:00") selected="selected" @endif >19:10</option>
                              <option value="19:20:00" @if($shedules->lab_hou=="19:20:00") selected="selected" @endif >19:20</option>
                              <option value="19:30:00" @if($shedules->lab_hou=="19:30:00") selected="selected" @endif >19:30</option>
                              <option value="19:40:00" @if($shedules->lab_hou=="19:40:00") selected="selected" @endif >19:40</option>
                              <option value="19:50:00" @if($shedules->lab_hou=="19:50:00") selected="selected" @endif >19:50</option>
                              <option value="20:00:00" @if($shedules->lab_hou=="20:00:00") selected="selected" @endif >20:00</option>
                              <option value="20:10:00" @if($shedules->lab_hou=="20:10:00") selected="selected" @endif >20:10</option>
                              <option value="20:20:00" @if($shedules->lab_hou=="20:20:00") selected="selected" @endif >20:20</option>
                              <option value="20:30:00" @if($shedules->lab_hou=="20:30:00") selected="selected" @endif >20:30</option>
                              <option value="20:40:00" @if($shedules->lab_hou=="20:40:00") selected="selected" @endif >20:40</option>
                              <option value="20:50:00" @if($shedules->lab_hou=="20:50:00") selected="selected" @endif >20:50</option>
                              <option value="21:00:00" @if($shedules->lab_hou=="21:00:00") selected="selected" @endif >21:00</option>
                              <option value="21:10:00" @if($shedules->lab_hou=="21:10:00") selected="selected" @endif >21:10</option>
                              <option value="21:20:00" @if($shedules->lab_hou=="21:20:00") selected="selected" @endif >21:20</option>
                              <option value="21:30:00" @if($shedules->lab_hou=="21:30:00") selected="selected" @endif >21:30</option>
                              <option value="21:40:00" @if($shedules->lab_hou=="21:40:00") selected="selected" @endif >21:40</option>
                              <option value="21:50:00" @if($shedules->lab_hou=="21:50:00") selected="selected" @endif >21:50</option>
                              <option value="22:00:00" @if($shedules->lab_hou=="22:00:00") selected="selected" @endif >22:00</option>
                              <option value="22:10:00" @if($shedules->lab_hou=="22:10:00") selected="selected" @endif >22:10</option>
                              <option value="22:20:00" @if($shedules->lab_hou=="22:20:00") selected="selected" @endif >22:20</option>
                              <option value="22:30:00" @if($shedules->lab_hou=="22:30:00") selected="selected" @endif >22:30</option>
                              <option value="22:40:00" @if($shedules->lab_hou=="22:40:00") selected="selected" @endif >22:40</option>
                              <option value="22:50:00" @if($shedules->lab_hou=="22:50:00") selected="selected" @endif >22:50</option>
                              <option value="23:00:00" @if($shedules->lab_hou=="23:00:00") selected="selected" @endif >23:00</option>
                              <option value="23:10:00" @if($shedules->lab_hou=="23:10:00") selected="selected" @endif >23:10</option>
                              <option value="23:20:00" @if($shedules->lab_hou=="23:20:00") selected="selected" @endif >23:20</option>
                              <option value="23:30:00" @if($shedules->lab_hou=="23:30:00") selected="selected" @endif >23:30</option>
                              <option value="23:40:00" @if($shedules->lab_hou=="23:40:00") selected="selected" @endif >23:40</option>
                              <option value="23:50:00" @if($shedules->lab_hou=="23:50:00") selected="selected" @endif >23:50</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_init_2[]" class="form-control form-date-1" id="reg_init_2<?php echo $i;?>" disabled="disabled">
                              <option value="">--Desde--</option>
                              <option value="06:00:00" @if($shedules->lab_hin2=="06:00:00") selected="selected" @endif>06:00</option>
                              <option value="06:10:00" @if($shedules->lab_hin2=="06:10:00") selected="selected" @endif >06:10</option>
                              <option value="06:20:00" @if($shedules->lab_hin2=="06:20:00") selected="selected" @endif >06:20</option>
                              <option value="06:30:00" @if($shedules->lab_hin2=="06:30:00") selected="selected" @endif >06:30</option>
                              <option value="06:40:00" @if($shedules->lab_hin2=="06:40:00") selected="selected" @endif >06:40</option>
                              <option value="06:50:00" @if($shedules->lab_hin2=="06:50:00") selected="selected" @endif >06:50</option>
                              <option value="07:00:00" @if($shedules->lab_hin2=="07:00:00") selected="selected" @endif >07:00</option>
                              <option value="07:10:00" @if($shedules->lab_hin2=="07:10:00") selected="selected" @endif >07:10</option>
                              <option value="07:20:00" @if($shedules->lab_hin2=="07:20:00") selected="selected" @endif >07:20</option>
                              <option value="07:30:00" @if($shedules->lab_hin2=="07:30:00") selected="selected" @endif >07:30</option>
                              <option value="07:40:00" @if($shedules->lab_hin2=="07:40:00") selected="selected" @endif >07:40</option>
                              <option value="07:50:00" @if($shedules->lab_hin2=="07:50:00") selected="selected" @endif >07:50</option>
                              <option value="08:00:00" @if($shedules->lab_hin2=="08:00:00") selected="selected" @endif >08:00</option>
                              <option value="08:10:00" @if($shedules->lab_hin2=="08:10:00") selected="selected" @endif >08:10</option>
                              <option value="08:20:00" @if($shedules->lab_hin2=="08:20:00") selected="selected" @endif >08:20</option>
                              <option value="08:30:00" @if($shedules->lab_hin2=="08:30:00") selected="selected" @endif >08:30</option>
                              <option value="08:40:00" @if($shedules->lab_hin2=="08:40:00") selected="selected" @endif >08:40</option>
                              <option value="08:50:00" @if($shedules->lab_hin2=="08:50:00") selected="selected" @endif >08:50</option>
                              <option value="09:00:00" @if($shedules->lab_hin2=="09:00:00") selected="selected" @endif >09:00</option>
                              <option value="09:10:00" @if($shedules->lab_hin2=="09:10:00") selected="selected" @endif >09:10</option>
                              <option value="09:20:00" @if($shedules->lab_hin2=="09:20:00") selected="selected" @endif >09:20</option>
                              <option value="09:30:00" @if($shedules->lab_hin2=="09:30:00") selected="selected" @endif >09:30</option>
                              <option value="09:40:00" @if($shedules->lab_hin2=="09:40:00") selected="selected" @endif >09:40</option>
                              <option value="09:50:00" @if($shedules->lab_hin2=="09:50:00") selected="selected" @endif >09:50</option>
                              <option value="10:00:00" @if($shedules->lab_hin2=="10:00:00") selected="selected" @endif >10:00</option>
                              <option value="10:10:00" @if($shedules->lab_hin2=="10:10:00") selected="selected" @endif >10:10</option>
                              <option value="10:20:00" @if($shedules->lab_hin2=="10:20:00") selected="selected" @endif >10:20</option>
                              <option value="10:30:00" @if($shedules->lab_hin2=="10:30:00") selected="selected" @endif >10:30</option>
                              <option value="10:40:00" @if($shedules->lab_hin2=="10:40:00") selected="selected" @endif >10:40</option>
                              <option value="10:50:00" @if($shedules->lab_hin2=="10:50:00") selected="selected" @endif >10:50</option>
                              <option value="11:00:00" @if($shedules->lab_hin2=="11:00:00") selected="selected" @endif >11:00</option>
                              <option value="11:10:00" @if($shedules->lab_hin2=="11:10:00") selected="selected" @endif >11:10</option>
                              <option value="11:20:00" @if($shedules->lab_hin2=="11:20:00") selected="selected" @endif >11:20</option>
                              <option value="11:30:00" @if($shedules->lab_hin2=="11:30:00") selected="selected" @endif >11:30</option>
                              <option value="11:40:00" @if($shedules->lab_hin2=="11:40:00") selected="selected" @endif >11:40</option>
                              <option value="11:50:00" @if($shedules->lab_hin2=="11:50:00") selected="selected" @endif >11:50</option>
                              <option value="12:00:00" @if($shedules->lab_hin2=="12:00:00") selected="selected" @endif >12:00</option>
                              <option value="12:10:00" @if($shedules->lab_hin2=="12:10:00") selected="selected" @endif >12:10</option>
                              <option value="12:20:00" @if($shedules->lab_hin2=="12:20:00") selected="selected" @endif >12:20</option>
                              <option value="12:30:00" @if($shedules->lab_hin2=="12:30:00") selected="selected" @endif >12:30</option>
                              <option value="12:40:00" @if($shedules->lab_hin2=="12:40:00") selected="selected" @endif >12:40</option>
                              <option value="12:50:00" @if($shedules->lab_hin2=="12:50:00") selected="selected" @endif >12:50</option>
                              <option value="13:00:00" @if($shedules->lab_hin2=="13:00:00") selected="selected" @endif >13:00</option>  
                              <option value="13:10:00" @if($shedules->lab_hin2=="13:10:00") selected="selected" @endif >13:10</option>
                              <option value="13:20:00" @if($shedules->lab_hin2=="13:20:00") selected="selected" @endif >13:20</option>
                              <option value="13:30:00" @if($shedules->lab_hin2=="13:30:00") selected="selected" @endif >13:30</option>
                              <option value="13:40:00" @if($shedules->lab_hin2=="13:40:00") selected="selected" @endif >13:40</option>
                              <option value="13:50:00" @if($shedules->lab_hin2=="13:50:00") selected="selected" @endif >13:50</option>
                              <option value="14:00:00" @if($shedules->lab_hin2=="14:00:00") selected="selected" @endif >14:00</option>
                              <option value="14:10:00" @if($shedules->lab_hin2=="14:10:00") selected="selected" @endif >14:10</option>
                              <option value="14:20:00" @if($shedules->lab_hin2=="14:20:00") selected="selected" @endif >14:20</option>
                              <option value="14:30:00" @if($shedules->lab_hin2=="14:30:00") selected="selected" @endif >14:30</option>
                              <option value="14:40:00" @if($shedules->lab_hin2=="14:40:00") selected="selected" @endif >14:40</option>
                              <option value="14:50:00" @if($shedules->lab_hin2=="14:50:00") selected="selected" @endif >14:50</option>
                              <option value="15:00:00" @if($shedules->lab_hin2=="15:00:00") selected="selected" @endif >15:00</option>
                              <option value="15:10:00" @if($shedules->lab_hin2=="15:10:00") selected="selected" @endif >15:10</option>
                              <option value="15:20:00" @if($shedules->lab_hin2=="15:20:00") selected="selected" @endif >15:20</option>
                              <option value="15:30:00" @if($shedules->lab_hin2=="15:30:00") selected="selected" @endif >15:30</option>
                              <option value="15:40:00" @if($shedules->lab_hin2=="15:40:00") selected="selected" @endif >15:40</option>
                              <option value="15:50:00" @if($shedules->lab_hin2=="15:50:00") selected="selected" @endif >15:50</option>
                              <option value="16:00:00" @if($shedules->lab_hin2=="16:00:00") selected="selected" @endif >16:00</option>
                              <option value="16:10:00" @if($shedules->lab_hin2=="16:10:00") selected="selected" @endif >16:10</option>
                              <option value="16:20:00" @if($shedules->lab_hin2=="16:20:00") selected="selected" @endif >16:20</option>
                              <option value="16:30:00" @if($shedules->lab_hin2=="16:30:00") selected="selected" @endif >16:30</option>
                              <option value="16:40:00" @if($shedules->lab_hin2=="16:40:00") selected="selected" @endif >16:40</option>
                              <option value="16:50:00" @if($shedules->lab_hin2=="16:50:00") selected="selected" @endif >16:50</option>
                              <option value="17:00:00" @if($shedules->lab_hin2=="17:00:00") selected="selected" @endif >17:00</option>
                              <option value="17:10:00" @if($shedules->lab_hin2=="17:10:00") selected="selected" @endif >17:10</option>
                              <option value="17:20:00" @if($shedules->lab_hin2=="17:20:00") selected="selected" @endif >17:20</option>
                              <option value="17:30:00" @if($shedules->lab_hin2=="17:30:00") selected="selected" @endif >17:30</option>
                              <option value="17:40:00" @if($shedules->lab_hin2=="17:40:00") selected="selected" @endif >17:40</option>
                              <option value="17:50:00" @if($shedules->lab_hin2=="17:50:00") selected="selected" @endif >17:50</option>
                              <option value="18:00:00" @if($shedules->lab_hin2=="18:00:00") selected="selected" @endif >18:00</option>
                              <option value="18:10:00" @if($shedules->lab_hin2=="18:10:00") selected="selected" @endif >18:10</option>
                              <option value="18:20:00" @if($shedules->lab_hin2=="18:20:00") selected="selected" @endif >18:20</option>
                              <option value="18:30:00" @if($shedules->lab_hin2=="18:30:00") selected="selected" @endif >18:30</option>
                              <option value="18:40:00" @if($shedules->lab_hin2=="18:40:00") selected="selected" @endif >18:40</option>
                              <option value="18:50:00" @if($shedules->lab_hin2=="18:50:00") selected="selected" @endif >18:50</option>
                              <option value="19:00:00" @if($shedules->lab_hin2=="19:00:00") selected="selected" @endif >19:00</option>
                              <option value="19:10:00" @if($shedules->lab_hin2=="19:10:00") selected="selected" @endif >19:10</option>
                              <option value="19:20:00" @if($shedules->lab_hin2=="19:20:00") selected="selected" @endif >19:20</option>
                              <option value="19:30:00" @if($shedules->lab_hin2=="19:30:00") selected="selected" @endif >19:30</option>
                              <option value="19:40:00" @if($shedules->lab_hin2=="19:40:00") selected="selected" @endif >19:40</option>
                              <option value="19:50:00" @if($shedules->lab_hin2=="19:50:00") selected="selected" @endif >19:50</option>
                              <option value="20:00:00" @if($shedules->lab_hin2=="20:00:00") selected="selected" @endif >20:00</option>
                              <option value="20:10:00" @if($shedules->lab_hin2=="20:10:00") selected="selected" @endif >20:10</option>
                              <option value="20:20:00" @if($shedules->lab_hin2=="20:20:00") selected="selected" @endif >20:20</option>
                              <option value="20:30:00" @if($shedules->lab_hin2=="20:30:00") selected="selected" @endif >20:30</option>
                              <option value="20:40:00" @if($shedules->lab_hin2=="20:40:00") selected="selected" @endif >20:40</option>
                              <option value="20:50:00" @if($shedules->lab_hin2=="20:50:00") selected="selected" @endif >20:50</option>
                              <option value="21:00:00" @if($shedules->lab_hin2=="21:00:00") selected="selected" @endif >21:00</option>
                              <option value="21:10:00" @if($shedules->lab_hin2=="21:10:00") selected="selected" @endif >21:10</option>
                              <option value="21:20:00" @if($shedules->lab_hin2=="21:20:00") selected="selected" @endif >21:20</option>
                              <option value="21:30:00" @if($shedules->lab_hin2=="21:30:00") selected="selected" @endif >21:30</option>
                              <option value="21:40:00" @if($shedules->lab_hin2=="21:40:00") selected="selected" @endif >21:40</option>
                              <option value="21:50:00" @if($shedules->lab_hin2=="21:50:00") selected="selected" @endif >21:50</option>
                              <option value="22:00:00" @if($shedules->lab_hin2=="22:00:00") selected="selected" @endif >22:00</option>
                              <option value="22:10:00" @if($shedules->lab_hin2=="22:10:00") selected="selected" @endif >22:10</option>
                              <option value="22:20:00" @if($shedules->lab_hin2=="22:20:00") selected="selected" @endif >22:20</option>
                              <option value="22:30:00" @if($shedules->lab_hin2=="22:30:00") selected="selected" @endif >22:30</option>
                              <option value="22:40:00" @if($shedules->lab_hin2=="22:40:00") selected="selected" @endif >22:40</option>
                              <option value="22:50:00" @if($shedules->lab_hin2=="22:50:00") selected="selected" @endif >22:50</option>
                              <option value="23:00:00" @if($shedules->lab_hin2=="23:00:00") selected="selected" @endif >23:00</option>
                              <option value="23:10:00" @if($shedules->lab_hin2=="23:10:00") selected="selected" @endif >23:10</option>
                              <option value="23:20:00" @if($shedules->lab_hin2=="23:20:00") selected="selected" @endif >23:20</option>
                              <option value="23:30:00" @if($shedules->lab_hin2=="23:30:00") selected="selected" @endif >23:30</option>
                              <option value="23:40:00" @if($shedules->lab_hin2=="23:40:00") selected="selected" @endif >23:40</option>
                              <option value="23:50:00" @if($shedules->lab_hin2=="23:50:00") selected="selected" @endif >23:50</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_end_2[]" class="form-control form-date-1" id="reg_end_2<?php echo $i;?>" disabled="disabled">
                              <option value="">--Hasta--</option>
                              <option value="06:00:00" @if($shedules->lab_hou2=="06:00:00") selected="selected" @endif>06:00</option>
                              <option value="06:10:00" @if($shedules->lab_hou2=="06:10:00") selected="selected" @endif >06:10</option>
                              <option value="06:20:00" @if($shedules->lab_hou2=="06:20:00") selected="selected" @endif >06:20</option>
                              <option value="06:30:00" @if($shedules->lab_hou2=="06:30:00") selected="selected" @endif >06:30</option>
                              <option value="06:40:00" @if($shedules->lab_hou2=="06:40:00") selected="selected" @endif >06:40</option>
                              <option value="06:50:00" @if($shedules->lab_hou2=="06:50:00") selected="selected" @endif >06:50</option>
                              <option value="07:00:00" @if($shedules->lab_hou2=="07:00:00") selected="selected" @endif >07:00</option>
                              <option value="07:10:00" @if($shedules->lab_hou2=="07:10:00") selected="selected" @endif >07:10</option>
                              <option value="07:20:00" @if($shedules->lab_hou2=="07:20:00") selected="selected" @endif >07:20</option>
                              <option value="07:30:00" @if($shedules->lab_hou2=="07:30:00") selected="selected" @endif >07:30</option>
                              <option value="07:40:00" @if($shedules->lab_hou2=="07:40:00") selected="selected" @endif >07:40</option>
                              <option value="07:50:00" @if($shedules->lab_hou2=="07:50:00") selected="selected" @endif >07:50</option>
                              <option value="08:00:00" @if($shedules->lab_hou2=="08:00:00") selected="selected" @endif >08:00</option>
                              <option value="08:10:00" @if($shedules->lab_hou2=="08:10:00") selected="selected" @endif >08:10</option>
                              <option value="08:20:00" @if($shedules->lab_hou2=="08:20:00") selected="selected" @endif >08:20</option>
                              <option value="08:30:00" @if($shedules->lab_hou2=="08:30:00") selected="selected" @endif >08:30</option>
                              <option value="08:40:00" @if($shedules->lab_hou2=="08:40:00") selected="selected" @endif >08:40</option>
                              <option value="08:50:00" @if($shedules->lab_hou2=="08:50:00") selected="selected" @endif >08:50</option>
                              <option value="09:00:00" @if($shedules->lab_hou2=="09:00:00") selected="selected" @endif >09:00</option>
                              <option value="09:10:00" @if($shedules->lab_hou2=="09:10:00") selected="selected" @endif >09:10</option>
                              <option value="09:20:00" @if($shedules->lab_hou2=="09:20:00") selected="selected" @endif >09:20</option>
                              <option value="09:30:00" @if($shedules->lab_hou2=="09:30:00") selected="selected" @endif >09:30</option>
                              <option value="09:40:00" @if($shedules->lab_hou2=="09:40:00") selected="selected" @endif >09:40</option>
                              <option value="09:50:00" @if($shedules->lab_hou2=="09:50:00") selected="selected" @endif >09:50</option>
                              <option value="10:00:00" @if($shedules->lab_hou2=="10:00:00") selected="selected" @endif >10:00</option>
                              <option value="10:10:00" @if($shedules->lab_hou2=="10:10:00") selected="selected" @endif >10:10</option>
                              <option value="10:20:00" @if($shedules->lab_hou2=="10:20:00") selected="selected" @endif >10:20</option>
                              <option value="10:30:00" @if($shedules->lab_hou2=="10:30:00") selected="selected" @endif >10:30</option>
                              <option value="10:40:00" @if($shedules->lab_hou2=="10:40:00") selected="selected" @endif >10:40</option>
                              <option value="10:50:00" @if($shedules->lab_hou2=="10:50:00") selected="selected" @endif >10:50</option>
                              <option value="11:00:00" @if($shedules->lab_hou2=="11:00:00") selected="selected" @endif >11:00</option>
                              <option value="11:10:00" @if($shedules->lab_hou2=="11:10:00") selected="selected" @endif >11:10</option>
                              <option value="11:20:00" @if($shedules->lab_hou2=="11:20:00") selected="selected" @endif >11:20</option>
                              <option value="11:30:00" @if($shedules->lab_hou2=="11:30:00") selected="selected" @endif >11:30</option>
                              <option value="11:40:00" @if($shedules->lab_hou2=="11:40:00") selected="selected" @endif >11:40</option>
                              <option value="11:50:00" @if($shedules->lab_hou2=="11:50:00") selected="selected" @endif >11:50</option>
                              <option value="12:00:00" @if($shedules->lab_hou2=="12:00:00") selected="selected" @endif >12:00</option>
                              <option value="12:10:00" @if($shedules->lab_hou2=="12:10:00") selected="selected" @endif >12:10</option>
                              <option value="12:20:00" @if($shedules->lab_hou2=="12:20:00") selected="selected" @endif >12:20</option>
                              <option value="12:30:00" @if($shedules->lab_hou2=="12:30:00") selected="selected" @endif >12:30</option>
                              <option value="12:40:00" @if($shedules->lab_hou2=="12:40:00") selected="selected" @endif >12:40</option>
                              <option value="12:50:00" @if($shedules->lab_hou2=="12:50:00") selected="selected" @endif >12:50</option>
                              <option value="13:00:00" @if($shedules->lab_hou2=="13:00:00") selected="selected" @endif >13:00</option>  
                              <option value="13:10:00" @if($shedules->lab_hou2=="13:10:00") selected="selected" @endif >13:10</option>
                              <option value="13:20:00" @if($shedules->lab_hou2=="13:20:00") selected="selected" @endif >13:20</option>
                              <option value="13:30:00" @if($shedules->lab_hou2=="13:30:00") selected="selected" @endif >13:30</option>
                              <option value="13:40:00" @if($shedules->lab_hou2=="13:40:00") selected="selected" @endif >13:40</option>
                              <option value="13:50:00" @if($shedules->lab_hou2=="13:50:00") selected="selected" @endif >13:50</option>
                              <option value="14:00:00" @if($shedules->lab_hou2=="14:00:00") selected="selected" @endif >14:00</option>
                              <option value="14:10:00" @if($shedules->lab_hou2=="14:10:00") selected="selected" @endif >14:10</option>
                              <option value="14:20:00" @if($shedules->lab_hou2=="14:20:00") selected="selected" @endif >14:20</option>
                              <option value="14:30:00" @if($shedules->lab_hou2=="14:30:00") selected="selected" @endif >14:30</option>
                              <option value="14:40:00" @if($shedules->lab_hou2=="14:40:00") selected="selected" @endif >14:40</option>
                              <option value="14:50:00" @if($shedules->lab_hou2=="14:50:00") selected="selected" @endif >14:50</option>
                              <option value="15:00:00" @if($shedules->lab_hou2=="15:00:00") selected="selected" @endif >15:00</option>
                              <option value="15:10:00" @if($shedules->lab_hou2=="15:10:00") selected="selected" @endif >15:10</option>
                              <option value="15:20:00" @if($shedules->lab_hou2=="15:20:00") selected="selected" @endif >15:20</option>
                              <option value="15:30:00" @if($shedules->lab_hou2=="15:30:00") selected="selected" @endif >15:30</option>
                              <option value="15:40:00" @if($shedules->lab_hou2=="15:40:00") selected="selected" @endif >15:40</option>
                              <option value="15:50:00" @if($shedules->lab_hou2=="15:50:00") selected="selected" @endif >15:50</option>
                              <option value="16:00:00" @if($shedules->lab_hou2=="16:00:00") selected="selected" @endif >16:00</option>
                              <option value="16:10:00" @if($shedules->lab_hou2=="16:10:00") selected="selected" @endif >16:10</option>
                              <option value="16:20:00" @if($shedules->lab_hou2=="16:20:00") selected="selected" @endif >16:20</option>
                              <option value="16:30:00" @if($shedules->lab_hou2=="16:30:00") selected="selected" @endif >16:30</option>
                              <option value="16:40:00" @if($shedules->lab_hou2=="16:40:00") selected="selected" @endif >16:40</option>
                              <option value="16:50:00" @if($shedules->lab_hou2=="16:50:00") selected="selected" @endif >16:50</option>
                              <option value="17:00:00" @if($shedules->lab_hou2=="17:00:00") selected="selected" @endif >17:00</option>
                              <option value="17:10:00" @if($shedules->lab_hou2=="17:10:00") selected="selected" @endif >17:10</option>
                              <option value="17:20:00" @if($shedules->lab_hou2=="17:20:00") selected="selected" @endif >17:20</option>
                              <option value="17:30:00" @if($shedules->lab_hou2=="17:30:00") selected="selected" @endif >17:30</option>
                              <option value="17:40:00" @if($shedules->lab_hou2=="17:40:00") selected="selected" @endif >17:40</option>
                              <option value="17:50:00" @if($shedules->lab_hou2=="17:50:00") selected="selected" @endif >17:50</option>
                              <option value="18:00:00" @if($shedules->lab_hou2=="18:00:00") selected="selected" @endif >18:00</option>
                              <option value="18:10:00" @if($shedules->lab_hou2=="18:10:00") selected="selected" @endif >18:10</option>
                              <option value="18:20:00" @if($shedules->lab_hou2=="18:20:00") selected="selected" @endif >18:20</option>
                              <option value="18:30:00" @if($shedules->lab_hou2=="18:30:00") selected="selected" @endif >18:30</option>
                              <option value="18:40:00" @if($shedules->lab_hou2=="18:40:00") selected="selected" @endif >18:40</option>
                              <option value="18:50:00" @if($shedules->lab_hou2=="18:50:00") selected="selected" @endif >18:50</option>
                              <option value="19:00:00" @if($shedules->lab_hou2=="19:00:00") selected="selected" @endif >19:00</option>
                              <option value="19:10:00" @if($shedules->lab_hou2=="19:10:00") selected="selected" @endif >19:10</option>
                              <option value="19:20:00" @if($shedules->lab_hou2=="19:20:00") selected="selected" @endif >19:20</option>
                              <option value="19:30:00" @if($shedules->lab_hou2=="19:30:00") selected="selected" @endif >19:30</option>
                              <option value="19:40:00" @if($shedules->lab_hou2=="19:40:00") selected="selected" @endif >19:40</option>
                              <option value="19:50:00" @if($shedules->lab_hou2=="19:50:00") selected="selected" @endif >19:50</option>
                              <option value="20:00:00" @if($shedules->lab_hou2=="20:00:00") selected="selected" @endif >20:00</option>
                              <option value="20:10:00" @if($shedules->lab_hou2=="20:10:00") selected="selected" @endif >20:10</option>
                              <option value="20:20:00" @if($shedules->lab_hou2=="20:20:00") selected="selected" @endif >20:20</option>
                              <option value="20:30:00" @if($shedules->lab_hou2=="20:30:00") selected="selected" @endif >20:30</option>
                              <option value="20:40:00" @if($shedules->lab_hou2=="20:40:00") selected="selected" @endif >20:40</option>
                              <option value="20:50:00" @if($shedules->lab_hou2=="20:50:00") selected="selected" @endif >20:50</option>
                              <option value="21:00:00" @if($shedules->lab_hou2=="21:00:00") selected="selected" @endif >21:00</option>
                              <option value="21:10:00" @if($shedules->lab_hou2=="21:10:00") selected="selected" @endif >21:10</option>
                              <option value="21:20:00" @if($shedules->lab_hou2=="21:20:00") selected="selected" @endif >21:20</option>
                              <option value="21:30:00" @if($shedules->lab_hou2=="21:30:00") selected="selected" @endif >21:30</option>
                              <option value="21:40:00" @if($shedules->lab_hou2=="21:40:00") selected="selected" @endif >21:40</option>
                              <option value="21:50:00" @if($shedules->lab_hou2=="21:50:00") selected="selected" @endif >21:50</option>
                              <option value="22:00:00" @if($shedules->lab_hou2=="22:00:00") selected="selected" @endif >22:00</option>
                              <option value="22:10:00" @if($shedules->lab_hou2=="22:10:00") selected="selected" @endif >22:10</option>
                              <option value="22:20:00" @if($shedules->lab_hou2=="22:20:00") selected="selected" @endif >22:20</option>
                              <option value="22:30:00" @if($shedules->lab_hou2=="22:30:00") selected="selected" @endif >22:30</option>
                              <option value="22:40:00" @if($shedules->lab_hou2=="22:40:00") selected="selected" @endif >22:40</option>
                              <option value="22:50:00" @if($shedules->lab_hou2=="22:50:00") selected="selected" @endif >22:50</option>
                              <option value="23:00:00" @if($shedules->lab_hou2=="23:00:00") selected="selected" @endif >23:00</option>
                              <option value="23:10:00" @if($shedules->lab_hou2=="23:10:00") selected="selected" @endif >23:10</option>
                              <option value="23:20:00" @if($shedules->lab_hou2=="23:20:00") selected="selected" @endif >23:20</option>
                              <option value="23:30:00" @if($shedules->lab_hou2=="23:30:00") selected="selected" @endif >23:30</option>
                              <option value="23:40:00" @if($shedules->lab_hou2=="23:40:00") selected="selected" @endif >23:40</option>
                              <option value="23:50:00" @if($shedules->lab_hou2=="23:50:00") selected="selected" @endif >23:50</option>
                           </select>
                        </div>
                     </div>
                     @else
                     <div class="row">
                        <div class="col-md-55"> 
                           <label class="label-date"><?php echo nameDay($i);?></label>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_init[]" class="form-control form-date-1" id="reg_init_1<?php echo $i;?>" disabled="disabled">
                              <option value="">--Desde--</option>
                              <option value="06:00:00">06:00</option>
                              <option value="06:10:00">06:10</option>
                              <option value="06:20:00">06:20</option>
                              <option value="06:30:00">06:30</option>
                              <option value="06:40:00">06:40</option>
                              <option value="06:50:00">06:50</option>
                              <option value="07:00:00">07:00</option>
                              <option value="07:10:00">07:10</option>
                              <option value="07:20:00">07:20</option>
                              <option value="07:30:00">07:30</option>
                              <option value="07:40:00">07:40</option>
                              <option value="07:50:00">07:50</option>
                              <option value="08:00:00">08:00</option>
                              <option value="08:10:00">08:10</option>
                              <option value="08:20:00">08:20</option>
                              <option value="08:30:00">08:30</option>
                              <option value="08:40:00">08:40</option>
                              <option value="08:50:00">08:50</option>
                              <option value="09:00:00">09:00</option>
                              <option value="09:10:00">09:10</option>
                              <option value="09:20:00">09:20</option>
                              <option value="09:30:00">09:30</option>
                              <option value="09:40:00">09:40</option>
                              <option value="09:50:00">09:50</option>
                              <option value="10:00:00">10:00</option>
                              <option value="10:10:00">10:10</option>
                              <option value="10:20:00">10:20</option>
                              <option value="10:30:00">10:30</option>
                              <option value="10:40:00">10:40</option>
                              <option value="10:50:00">10:50</option>
                              <option value="11:00:00">11:00</option>
                              <option value="11:10:00">11:10</option>
                              <option value="11:20:00">11:20</option>
                              <option value="11:30:00">11:30</option>
                              <option value="11:40:00">11:40</option>
                              <option value="11:50:00">11:50</option>
                              <option value="12:00:00">12:00</option>
                              <option value="12:10:00">12:10</option>
                              <option value="12:20:00">12:20</option>
                              <option value="12:30:00">12:30</option>
                              <option value="12:40:00">12:40</option>
                              <option value="12:50:00">12:50</option>
                              <option value="13:00:00">13:00</option>  
                              <option value="13:10:00">13:10</option>
                              <option value="13:20:00">13:20</option>
                              <option value="13:30:00">13:30</option>
                              <option value="13:40:00">13:40</option>
                              <option value="13:50:00">13:50</option>
                              <option value="14:00:00">14:00</option>
                              <option value="14:10:00">14:10</option>
                              <option value="14:20:00">14:20</option>
                              <option value="14:30:00">14:30</option>
                              <option value="14:40:00">14:40</option>
                              <option value="14:50:00">14:50</option>
                              <option value="15:00:00">15:00</option>
                              <option value="15:10:00">15:10</option>
                              <option value="15:20:00">15:20</option>
                              <option value="15:30:00">15:30</option>
                              <option value="15:40:00">15:40</option>
                              <option value="15:50:00">15:50</option>
                              <option value="16:00:00">16:00</option>
                              <option value="16:10:00">16:10</option>
                              <option value="16:20:00">16:20</option>
                              <option value="16:30:00">16:30</option>
                              <option value="16:40:00">16:40</option>
                              <option value="16:50:00">16:50</option>
                              <option value="17:00:00">17:00</option>
                              <option value="17:10:00">17:10</option>
                              <option value="17:20:00">17:20</option>
                              <option value="17:30:00">17:30</option>
                              <option value="17:40:00">17:40</option>
                              <option value="17:50:00">17:50</option>
                              <option value="18:00:00">18:00</option>
                              <option value="18:10:00">18:10</option>
                              <option value="18:20:00">18:20</option>
                              <option value="18:30:00">18:30</option>
                              <option value="18:40:00">18:40</option>
                              <option value="18:50:00">18:50</option>
                              <option value="19:00:00">19:00</option>
                              <option value="19:10:00">19:10</option>
                              <option value="19:20:00">19:20</option>
                              <option value="19:30:00">19:30</option>
                              <option value="19:40:00">19:40</option>
                              <option value="19:50:00">19:50</option>
                              <option value="20:00:00">20:00</option>
                              <option value="20:10:00">20:10</option>
                              <option value="20:20:00">20:20</option>
                              <option value="20:30:00">20:30</option>
                              <option value="20:40:00">20:40</option>
                              <option value="20:50:00">20:50</option>
                              <option value="21:00:00">21:00</option>
                              <option value="21:10:00">21:10</option>
                              <option value="21:20:00">21:20</option>
                              <option value="21:30:00">21:30</option>
                              <option value="21:40:00">21:40</option>
                              <option value="21:50:00">21:50</option>
                              <option value="22:00:00">22:00</option>
                              <option value="22:10:00">22:10</option>
                              <option value="22:20:00">22:20</option>
                              <option value="22:30:00">22:30</option>
                              <option value="22:40:00">22:40</option>
                              <option value="22:50:00">22:50</option>
                              <option value="23:00:00">23:00</option>
                              <option value="23:10:00">23:10</option>
                              <option value="23:20:00">23:20</option>
                              <option value="23:30:00">23:30</option>
                              <option value="23:40:00">23:40</option>
                              <option value="23:50:00">23:50</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_end[]" class="form-control form-date-1" id="reg_end_1<?php echo $i;?>" disabled="disabled">
                              <option value="">--Hasta--</option>
                              <option value="06:00:00">06:00</option>
                              <option value="06:10:00">06:10</option>
                              <option value="06:20:00">06:20</option>
                              <option value="06:30:00">06:30</option>
                              <option value="06:40:00">06:40</option>
                              <option value="06:50:00">06:50</option>
                              <option value="07:00:00">07:00</option>
                              <option value="07:10:00">07:10</option>
                              <option value="07:20:00">07:20</option>
                              <option value="07:30:00">07:30</option>
                              <option value="07:40:00">07:40</option>
                              <option value="07:50:00">07:50</option>
                              <option value="08:00:00">08:00</option>
                              <option value="08:10:00">08:10</option>
                              <option value="08:20:00">08:20</option>
                              <option value="08:30:00">08:30</option>
                              <option value="08:40:00">08:40</option>
                              <option value="08:50:00">08:50</option>
                              <option value="09:00:00">09:00</option>
                              <option value="09:10:00">09:10</option>
                              <option value="09:20:00">09:20</option>
                              <option value="09:30:00">09:30</option>
                              <option value="09:40:00">09:40</option>
                              <option value="09:50:00">09:50</option>
                              <option value="10:00:00">10:00</option>
                              <option value="10:10:00">10:10</option>
                              <option value="10:20:00">10:20</option>
                              <option value="10:30:00">10:30</option>
                              <option value="10:40:00">10:40</option>
                              <option value="10:50:00">10:50</option>
                              <option value="11:00:00">11:00</option>
                              <option value="11:10:00">11:10</option>
                              <option value="11:20:00">11:20</option>
                              <option value="11:30:00">11:30</option>
                              <option value="11:40:00">11:40</option>
                              <option value="11:50:00">11:50</option>
                              <option value="12:00:00">12:00</option>
                              <option value="12:10:00">12:10</option>
                              <option value="12:20:00">12:20</option>
                              <option value="12:30:00">12:30</option>
                              <option value="12:40:00">12:40</option>
                              <option value="12:50:00">12:50</option>
                              <option value="13:00:00">13:00</option>  
                              <option value="13:10:00">13:10</option>
                              <option value="13:20:00">13:20</option>
                              <option value="13:30:00">13:30</option>
                              <option value="13:40:00">13:40</option>
                              <option value="13:50:00">13:50</option>
                              <option value="14:00:00">14:00</option>
                              <option value="14:10:00">14:10</option>
                              <option value="14:20:00">14:20</option>
                              <option value="14:30:00">14:30</option>
                              <option value="14:40:00">14:40</option>
                              <option value="14:50:00">14:50</option>
                              <option value="15:00:00">15:00</option>
                              <option value="15:10:00">15:10</option>
                              <option value="15:20:00">15:20</option>
                              <option value="15:30:00">15:30</option>
                              <option value="15:40:00">15:40</option>
                              <option value="15:50:00">15:50</option>
                              <option value="16:00:00">16:00</option>
                              <option value="16:10:00">16:10</option>
                              <option value="16:20:00">16:20</option>
                              <option value="16:30:00">16:30</option>
                              <option value="16:40:00">16:40</option>
                              <option value="16:50:00">16:50</option>
                              <option value="17:00:00">17:00</option>
                              <option value="17:10:00">17:10</option>
                              <option value="17:20:00">17:20</option>
                              <option value="17:30:00">17:30</option>
                              <option value="17:40:00">17:40</option>
                              <option value="17:50:00">17:50</option>
                              <option value="18:00:00">18:00</option>
                              <option value="18:10:00">18:10</option>
                              <option value="18:20:00">18:20</option>
                              <option value="18:30:00">18:30</option>
                              <option value="18:40:00">18:40</option>
                              <option value="18:50:00">18:50</option>
                              <option value="19:00:00">19:00</option>
                              <option value="19:10:00">19:10</option>
                              <option value="19:20:00">19:20</option>
                              <option value="19:30:00">19:30</option>
                              <option value="19:40:00">19:40</option>
                              <option value="19:50:00">19:50</option>
                              <option value="20:00:00">20:00</option>
                              <option value="20:10:00">20:10</option>
                              <option value="20:20:00">20:20</option>
                              <option value="20:30:00">20:30</option>
                              <option value="20:40:00">20:40</option>
                              <option value="20:50:00">20:50</option>
                              <option value="21:00:00">21:00</option>
                              <option value="21:10:00">21:10</option>
                              <option value="21:20:00">21:20</option>
                              <option value="21:30:00">21:30</option>
                              <option value="21:40:00">21:40</option>
                              <option value="21:50:00">21:50</option>
                              <option value="22:00:00">22:00</option>
                              <option value="22:10:00">22:10</option>
                              <option value="22:20:00">22:20</option>
                              <option value="22:30:00">22:30</option>
                              <option value="22:40:00">22:40</option>
                              <option value="22:50:00">22:50</option>
                              <option value="23:00:00">23:00</option>
                              <option value="23:10:00">23:10</option>
                              <option value="23:20:00">23:20</option>
                              <option value="23:30:00">23:30</option>
                              <option value="23:40:00">23:40</option>
                              <option value="23:50:00">23:50</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_init_2[]" class="form-control form-date-1" id="reg_init_2<?php echo $i;?>" disabled="disabled">
                              <option value="">--Desde--</option>
                              <option value="06:00:00">06:00</option>
                              <option value="06:10:00">06:10</option>
                              <option value="06:20:00">06:20</option>
                              <option value="06:30:00">06:30</option>
                              <option value="06:40:00">06:40</option>
                              <option value="06:50:00">06:50</option>
                              <option value="07:00:00">07:00</option>
                              <option value="07:10:00">07:10</option>
                              <option value="07:20:00">07:20</option>
                              <option value="07:30:00">07:30</option>
                              <option value="07:40:00">07:40</option>
                              <option value="07:50:00">07:50</option>
                              <option value="08:00:00">08:00</option>
                              <option value="08:10:00">08:10</option>
                              <option value="08:20:00">08:20</option>
                              <option value="08:30:00">08:30</option>
                              <option value="08:40:00">08:40</option>
                              <option value="08:50:00">08:50</option>
                              <option value="09:00:00">09:00</option>
                              <option value="09:10:00">09:10</option>
                              <option value="09:20:00">09:20</option>
                              <option value="09:30:00">09:30</option>
                              <option value="09:40:00">09:40</option>
                              <option value="09:50:00">09:50</option>
                              <option value="10:00:00">10:00</option>
                              <option value="10:10:00">10:10</option>
                              <option value="10:20:00">10:20</option>
                              <option value="10:30:00">10:30</option>
                              <option value="10:40:00">10:40</option>
                              <option value="10:50:00">10:50</option>
                              <option value="11:00:00">11:00</option>
                              <option value="11:10:00">11:10</option>
                              <option value="11:20:00">11:20</option>
                              <option value="11:30:00">11:30</option>
                              <option value="11:40:00">11:40</option>
                              <option value="11:50:00">11:50</option>
                              <option value="12:00:00">12:00</option>
                              <option value="12:10:00">12:10</option>
                              <option value="12:20:00">12:20</option>
                              <option value="12:30:00">12:30</option>
                              <option value="12:40:00">12:40</option>
                              <option value="12:50:00">12:50</option>
                              <option value="13:00:00">13:00</option>  
                              <option value="13:10:00">13:10</option>
                              <option value="13:20:00">13:20</option>
                              <option value="13:30:00">13:30</option>
                              <option value="13:40:00">13:40</option>
                              <option value="13:50:00">13:50</option>
                              <option value="14:00:00">14:00</option>
                              <option value="14:10:00">14:10</option>
                              <option value="14:20:00">14:20</option>
                              <option value="14:30:00">14:30</option>
                              <option value="14:40:00">14:40</option>
                              <option value="14:50:00">14:50</option>
                              <option value="15:00:00">15:00</option>
                              <option value="15:10:00">15:10</option>
                              <option value="15:20:00">15:20</option>
                              <option value="15:30:00">15:30</option>
                              <option value="15:40:00">15:40</option>
                              <option value="15:50:00">15:50</option>
                              <option value="16:00:00">16:00</option>
                              <option value="16:10:00">16:10</option>
                              <option value="16:20:00">16:20</option>
                              <option value="16:30:00">16:30</option>
                              <option value="16:40:00">16:40</option>
                              <option value="16:50:00">16:50</option>
                              <option value="17:00:00">17:00</option>
                              <option value="17:10:00">17:10</option>
                              <option value="17:20:00">17:20</option>
                              <option value="17:30:00">17:30</option>
                              <option value="17:40:00">17:40</option>
                              <option value="17:50:00">17:50</option>
                              <option value="18:00:00">18:00</option>
                              <option value="18:10:00">18:10</option>
                              <option value="18:20:00">18:20</option>
                              <option value="18:30:00">18:30</option>
                              <option value="18:40:00">18:40</option>
                              <option value="18:50:00">18:50</option>
                              <option value="19:00:00">19:00</option>
                              <option value="19:10:00">19:10</option>
                              <option value="19:20:00">19:20</option>
                              <option value="19:30:00">19:30</option>
                              <option value="19:40:00">19:40</option>
                              <option value="19:50:00">19:50</option>
                              <option value="20:00:00">20:00</option>
                              <option value="20:10:00">20:10</option>
                              <option value="20:20:00">20:20</option>
                              <option value="20:30:00">20:30</option>
                              <option value="20:40:00">20:40</option>
                              <option value="20:50:00">20:50</option>
                              <option value="21:00:00">21:00</option>
                              <option value="21:10:00">21:10</option>
                              <option value="21:20:00">21:20</option>
                              <option value="21:30:00">21:30</option>
                              <option value="21:40:00">21:40</option>
                              <option value="21:50:00">21:50</option>
                              <option value="22:00:00">22:00</option>
                              <option value="22:10:00">22:10</option>
                              <option value="22:20:00">22:20</option>
                              <option value="22:30:00">22:30</option>
                              <option value="22:40:00">22:40</option>
                              <option value="22:50:00">22:50</option>
                              <option value="23:00:00">23:00</option>
                              <option value="23:10:00">23:10</option>
                              <option value="23:20:00">23:20</option>
                              <option value="23:30:00">23:30</option>
                              <option value="23:40:00">23:40</option>
                              <option value="23:50:00">23:50</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_end_2[]" class="form-control form-date-1" id="reg_end_2<?php echo $i;?>" disabled="disabled">
                              <option value="">--Hasta--</option>
                              <option value="06:00:00">06:00</option>
                              <option value="06:10:00">06:10</option>
                              <option value="06:20:00">06:20</option>
                              <option value="06:30:00">06:30</option>
                              <option value="06:40:00">06:40</option>
                              <option value="06:50:00">06:50</option>
                              <option value="07:00:00">07:00</option>
                              <option value="07:10:00">07:10</option>
                              <option value="07:20:00">07:20</option>
                              <option value="07:30:00">07:30</option>
                              <option value="07:40:00">07:40</option>
                              <option value="07:50:00">07:50</option>
                              <option value="08:00:00">08:00</option>
                              <option value="08:10:00">08:10</option>
                              <option value="08:20:00">08:20</option>
                              <option value="08:30:00">08:30</option>
                              <option value="08:40:00">08:40</option>
                              <option value="08:50:00">08:50</option>
                              <option value="09:00:00">09:00</option>
                              <option value="09:10:00">09:10</option>
                              <option value="09:20:00">09:20</option>
                              <option value="09:30:00">09:30</option>
                              <option value="09:40:00">09:40</option>
                              <option value="09:50:00">09:50</option>
                              <option value="10:00:00">10:00</option>
                              <option value="10:10:00">10:10</option>
                              <option value="10:20:00">10:20</option>
                              <option value="10:30:00">10:30</option>
                              <option value="10:40:00">10:40</option>
                              <option value="10:50:00">10:50</option>
                              <option value="11:00:00">11:00</option>
                              <option value="11:10:00">11:10</option>
                              <option value="11:20:00">11:20</option>
                              <option value="11:30:00">11:30</option>
                              <option value="11:40:00">11:40</option>
                              <option value="11:50:00">11:50</option>
                              <option value="12:00:00">12:00</option>
                              <option value="12:10:00">12:10</option>
                              <option value="12:20:00">12:20</option>
                              <option value="12:30:00">12:30</option>
                              <option value="12:40:00">12:40</option>
                              <option value="12:50:00">12:50</option>
                              <option value="13:00:00">13:00</option>  
                              <option value="13:10:00">13:10</option>
                              <option value="13:20:00">13:20</option>
                              <option value="13:30:00">13:30</option>
                              <option value="13:40:00">13:40</option>
                              <option value="13:50:00">13:50</option>
                              <option value="14:00:00">14:00</option>
                              <option value="14:10:00">14:10</option>
                              <option value="14:20:00">14:20</option>
                              <option value="14:30:00">14:30</option>
                              <option value="14:40:00">14:40</option>
                              <option value="14:50:00">14:50</option>
                              <option value="15:00:00">15:00</option>
                              <option value="15:10:00">15:10</option>
                              <option value="15:20:00">15:20</option>
                              <option value="15:30:00">15:30</option>
                              <option value="15:40:00">15:40</option>
                              <option value="15:50:00">15:50</option>
                              <option value="16:00:00">16:00</option>
                              <option value="16:10:00">16:10</option>
                              <option value="16:20:00">16:20</option>
                              <option value="16:30:00">16:30</option>
                              <option value="16:40:00">16:40</option>
                              <option value="16:50:00">16:50</option>
                              <option value="17:00:00">17:00</option>
                              <option value="17:10:00">17:10</option>
                              <option value="17:20:00">17:20</option>
                              <option value="17:30:00">17:30</option>
                              <option value="17:40:00">17:40</option>
                              <option value="17:50:00">17:50</option>
                              <option value="18:00:00">18:00</option>
                              <option value="18:10:00">18:10</option>
                              <option value="18:20:00">18:20</option>
                              <option value="18:30:00">18:30</option>
                              <option value="18:40:00">18:40</option>
                              <option value="18:50:00">18:50</option>
                              <option value="19:00:00">19:00</option>
                              <option value="19:10:00">19:10</option>
                              <option value="19:20:00">19:20</option>
                              <option value="19:30:00">19:30</option>
                              <option value="19:40:00">19:40</option>
                              <option value="19:50:00">19:50</option>
                              <option value="20:00:00">20:00</option>
                              <option value="20:10:00">20:10</option>
                              <option value="20:20:00">20:20</option>
                              <option value="20:30:00">20:30</option>
                              <option value="20:40:00">20:40</option>
                              <option value="20:50:00">20:50</option>
                              <option value="21:00:00">21:00</option>
                              <option value="21:10:00">21:10</option>
                              <option value="21:20:00">21:20</option>
                              <option value="21:30:00">21:30</option>
                              <option value="21:40:00">21:40</option>
                              <option value="21:50:00">21:50</option>
                              <option value="22:00:00">22:00</option>
                              <option value="22:10:00">22:10</option>
                              <option value="22:20:00">22:20</option>
                              <option value="22:30:00">22:30</option>
                              <option value="22:40:00">22:40</option>
                              <option value="22:50:00">22:50</option>
                              <option value="23:00:00">23:00</option>
                              <option value="23:10:00">23:10</option>
                              <option value="23:20:00">23:20</option>
                              <option value="23:30:00">23:30</option>
                              <option value="23:40:00">23:40</option>
                              <option value="23:50:00">23:50</option>
                           </select>
                        </div>
                     </div>
                     @endif
                     @endfor
                  </div>
                  <div class="form-group">
                     <button type="button" onclick="update_shedules()" disabled="disabled" class="btn btn-success" id="boton-1">Actualizar datos</button>
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
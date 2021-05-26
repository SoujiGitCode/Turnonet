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
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Días y horarios de atención</h4>
      </div>
       <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="f1-steps">
               <div class="f1-progress">
                 <div class="f1-progress-line" data-now-value="53.32" data-number-of-steps="3" style="width: 53.32%;"></div>
              </div>
              <div class="f1-step active" id="step-1">
               <div class="f1-step-icon">1</div>
               <p>Cargue los datos de su empresa.</p>
            </div>
            <div class="f1-step active" id="step-2">
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
               Configure los días y horarios de atención de tu empresa aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" name="id" value="<?php echo $get_business->em_id;?>">
                  <p>Seleccione en que días y rangos horarios desea otorgar turnos para esta Empresa. En caso de trabajar de corrido solo usar el horario 1. Ej. Horario corrido: 9 a 18 hs. / Horario cortado: 9 a 14 y 17 a 20 hs. Esta configuración tomara efecto en todos aquellos prestadores que no tengan configurado sus propios días y horarios, y que su Sucursal correspondiente tampoco se encuentre configurado.</p>
                  <div class="form-group btop " >
                     <label class="label-1">Horarios de atención:</label>
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
                        $shedules=DB::table('tu_dlab')->where('lab_dian',$i)->where('lab_empid',$get_business->em_id)->where('lab_sucid',0)->where('lab_presid',0)->first();
                        
                           ?>
                     @if(isset($shedules)!=0)
                     <div class="row">
                        <div class="col-md-55"> 
                           <label class="label-date"><?php echo nameDay($i);?></label>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_init[]" class="form-control form-date-1" id="reg_init_1<?php echo $i;?>"  >
                              <option value="">--Desde--</option>
                              <option value="06:00:00" @if($shedules->lab_hin=="06:00:00") selected="selected" @endif>06:00</option>
                              <option value="06:30:00" @if($shedules->lab_hin=="06:30:00") selected="selected" @endif >06:30</option>
                              <option value="07:00:00" @if($shedules->lab_hin=="07:00:00") selected="selected" @endif >07:00</option>
                              <option value="07:30:00" @if($shedules->lab_hin=="07:30:00") selected="selected" @endif >07:30</option>
                              <option value="08:00:00" @if($shedules->lab_hin=="08:00:00") selected="selected" @endif >08:00</option>
                              <option value="08:30:00" @if($shedules->lab_hin=="08:30:00") selected="selected" @endif >08:30</option>
                              <option value="09:00:00" @if($shedules->lab_hin=="09:00:00") selected="selected" @endif >09:00</option>
                              <option value="09:30:00" @if($shedules->lab_hin=="09:30:00") selected="selected" @endif >09:30</option>
                              <option value="10:00:00" @if($shedules->lab_hin=="10:00:00") selected="selected" @endif >10:00</option>
                              <option value="10:30:00" @if($shedules->lab_hin=="10:30:00") selected="selected" @endif >10:30</option>
                              <option value="11:00:00" @if($shedules->lab_hin=="11:00:00") selected="selected" @endif >11:00</option>
                              <option value="11:30:00" @if($shedules->lab_hin=="11:30:00") selected="selected" @endif >11:30</option>
                              <option value="12:00:00" @if($shedules->lab_hin=="12:00:00") selected="selected" @endif >12:00</option>
                              <option value="12:30:00" @if($shedules->lab_hin=="12:30:00") selected="selected" @endif >12:30</option>
                              <option value="13:00:00" @if($shedules->lab_hin=="13:00:00") selected="selected" @endif >13:00</option>
                              <option value="13:30:00" @if($shedules->lab_hin=="13:30:00") selected="selected" @endif >13:30</option>
                              <option value="14:00:00" @if($shedules->lab_hin=="14:00:00") selected="selected" @endif >14:00</option>
                              <option value="14:30:00" @if($shedules->lab_hin=="14:30:00") selected="selected" @endif >14:30</option>
                              <option value="15:00:00" @if($shedules->lab_hin=="15:00:00") selected="selected" @endif >15:00</option>
                              <option value="15:30:00" @if($shedules->lab_hin=="15:30:00") selected="selected" @endif >15:30</option>
                              <option value="16:00:00" @if($shedules->lab_hin=="16:00:00") selected="selected" @endif >16:00</option>
                              <option value="16:30:00" @if($shedules->lab_hin=="16:30:00") selected="selected" @endif >16:30</option>
                              <option value="17:00:00" @if($shedules->lab_hin=="17:00:00") selected="selected" @endif >17:00</option>
                              <option value="17:30:00" @if($shedules->lab_hin=="17:30:00") selected="selected" @endif >17:30</option>
                              <option value="18:00:00" @if($shedules->lab_hin=="18:00:00") selected="selected" @endif >18:00</option>
                              <option value="18:30:00" @if($shedules->lab_hin=="18:30:00") selected="selected" @endif >18:30</option>
                              <option value="19:00:00" @if($shedules->lab_hin=="19:00:00") selected="selected" @endif >19:00</option>
                              <option value="19:30:00" @if($shedules->lab_hin=="19:30:00") selected="selected" @endif >19:30</option>
                              <option value="20:00:00" @if($shedules->lab_hin=="20:00:00") selected="selected" @endif >20:00</option>
                              <option value="20:30:00" @if($shedules->lab_hin=="20:30:00") selected="selected" @endif >20:30</option>
                              <option value="21:00:00" @if($shedules->lab_hin=="21:00:00") selected="selected" @endif >21:00</option>
                              <option value="21:30:00" @if($shedules->lab_hin=="21:30:00") selected="selected" @endif >21:30</option>
                              <option value="22:00:00" @if($shedules->lab_hin=="22:00:00") selected="selected" @endif >22:00</option>
                              <option value="22:30:00" @if($shedules->lab_hin=="22:30:00") selected="selected" @endif >22:30</option>
                              <option value="23:00:00" @if($shedules->lab_hin=="23:00:00") selected="selected" @endif >23:00</option>
                              <option value="23:30:00" @if($shedules->lab_hin=="23:30:00") selected="selected" @endif >23:30</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_end[]" class="form-control form-date-1" id="reg_end_1<?php echo $i;?>" >
                              <option value="">--Hasta--</option>
                              <option value="06:00:00" @if($shedules->lab_hou=="06:00:00") selected="selected" @endif>06:00</option>
                              <option value="06:30:00" @if($shedules->lab_hou=="06:30:00") selected="selected" @endif >06:30</option>
                              <option value="07:00:00" @if($shedules->lab_hou=="07:00:00") selected="selected" @endif >07:00</option>
                              <option value="07:30:00" @if($shedules->lab_hou=="07:30:00") selected="selected" @endif >07:30</option>
                              <option value="08:00:00" @if($shedules->lab_hou=="08:00:00") selected="selected" @endif >08:00</option>
                              <option value="08:30:00" @if($shedules->lab_hou=="08:30:00") selected="selected" @endif >08:30</option>
                              <option value="09:00:00" @if($shedules->lab_hou=="09:00:00") selected="selected" @endif >09:00</option>
                              <option value="09:30:00" @if($shedules->lab_hou=="09:30:00") selected="selected" @endif >09:30</option>
                              <option value="10:00:00" @if($shedules->lab_hou=="10:00:00") selected="selected" @endif >10:00</option>
                              <option value="10:30:00" @if($shedules->lab_hou=="10:30:00") selected="selected" @endif >10:30</option>
                              <option value="11:00:00" @if($shedules->lab_hou=="11:00:00") selected="selected" @endif >11:00</option>
                              <option value="11:30:00" @if($shedules->lab_hou=="11:30:00") selected="selected" @endif >11:30</option>
                              <option value="12:00:00" @if($shedules->lab_hou=="12:00:00") selected="selected" @endif >12:00</option>
                              <option value="12:30:00" @if($shedules->lab_hou=="12:30:00") selected="selected" @endif >12:30</option>
                              <option value="13:00:00" @if($shedules->lab_hou=="13:00:00") selected="selected" @endif >13:00</option>
                              <option value="13:30:00" @if($shedules->lab_hou=="13:30:00") selected="selected" @endif >13:30</option>
                              <option value="14:00:00" @if($shedules->lab_hou=="14:00:00") selected="selected" @endif >14:00</option>
                              <option value="14:30:00" @if($shedules->lab_hou=="14:30:00") selected="selected" @endif >14:30</option>
                              <option value="15:00:00" @if($shedules->lab_hou=="15:00:00") selected="selected" @endif >15:00</option>
                              <option value="15:30:00" @if($shedules->lab_hou=="15:30:00") selected="selected" @endif >15:30</option>
                              <option value="16:00:00" @if($shedules->lab_hou=="16:00:00") selected="selected" @endif >16:00</option>
                              <option value="16:30:00" @if($shedules->lab_hou=="16:30:00") selected="selected" @endif >16:30</option>
                              <option value="17:00:00" @if($shedules->lab_hou=="17:00:00") selected="selected" @endif >17:00</option>
                              <option value="17:30:00" @if($shedules->lab_hou=="17:30:00") selected="selected" @endif >17:30</option>
                              <option value="18:00:00" @if($shedules->lab_hou=="18:00:00") selected="selected" @endif >18:00</option>
                              <option value="18:30:00" @if($shedules->lab_hou=="18:30:00") selected="selected" @endif >18:30</option>
                              <option value="19:00:00" @if($shedules->lab_hou=="19:00:00") selected="selected" @endif >19:00</option>
                              <option value="19:30:00" @if($shedules->lab_hou=="19:30:00") selected="selected" @endif >19:30</option>
                              <option value="20:00:00" @if($shedules->lab_hou=="20:00:00") selected="selected" @endif >20:00</option>
                              <option value="20:30:00" @if($shedules->lab_hou=="20:30:00") selected="selected" @endif >20:30</option>
                              <option value="21:00:00" @if($shedules->lab_hou=="21:00:00") selected="selected" @endif >21:00</option>
                              <option value="21:30:00" @if($shedules->lab_hou=="21:30:00") selected="selected" @endif >21:30</option>
                              <option value="22:00:00" @if($shedules->lab_hou=="22:00:00") selected="selected" @endif >22:00</option>
                              <option value="22:30:00" @if($shedules->lab_hou=="22:30:00") selected="selected" @endif >22:30</option>
                              <option value="23:00:00" @if($shedules->lab_hou=="23:00:00") selected="selected" @endif >23:00</option>
                              <option value="23:30:00" @if($shedules->lab_hou=="23:30:00") selected="selected" @endif >23:30</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_init_2[]" class="form-control form-date-1" id="reg_init_2<?php echo $i;?>" >
                              <option value="">--Desde--</option>
                              <option value="06:00:00" @if($shedules->lab_hin2=="06:00:00") selected="selected" @endif>06:00</option>
                              <option value="06:30:00" @if($shedules->lab_hin2=="06:30:00") selected="selected" @endif >06:30</option>
                              <option value="07:00:00" @if($shedules->lab_hin2=="07:00:00") selected="selected" @endif >07:00</option>
                              <option value="07:30:00" @if($shedules->lab_hin2=="07:30:00") selected="selected" @endif >07:30</option>
                              <option value="08:00:00" @if($shedules->lab_hin2=="08:00:00") selected="selected" @endif >08:00</option>
                              <option value="08:30:00" @if($shedules->lab_hin2=="08:30:00") selected="selected" @endif >08:30</option>
                              <option value="09:00:00" @if($shedules->lab_hin2=="09:00:00") selected="selected" @endif >09:00</option>
                              <option value="09:30:00" @if($shedules->lab_hin2=="09:30:00") selected="selected" @endif >09:30</option>
                              <option value="10:00:00" @if($shedules->lab_hin2=="10:00:00") selected="selected" @endif >10:00</option>
                              <option value="10:30:00" @if($shedules->lab_hin2=="10:30:00") selected="selected" @endif >10:30</option>
                              <option value="11:00:00" @if($shedules->lab_hin2=="11:00:00") selected="selected" @endif >11:00</option>
                              <option value="11:30:00" @if($shedules->lab_hin2=="11:30:00") selected="selected" @endif >11:30</option>
                              <option value="12:00:00" @if($shedules->lab_hin2=="12:00:00") selected="selected" @endif >12:00</option>
                              <option value="12:30:00" @if($shedules->lab_hin2=="12:30:00") selected="selected" @endif >12:30</option>
                              <option value="13:00:00" @if($shedules->lab_hin2=="13:00:00") selected="selected" @endif >13:00</option>
                              <option value="13:30:00" @if($shedules->lab_hin2=="13:30:00") selected="selected" @endif >13:30</option>
                              <option value="14:00:00" @if($shedules->lab_hin2=="14:00:00") selected="selected" @endif >14:00</option>
                              <option value="14:30:00" @if($shedules->lab_hin2=="14:30:00") selected="selected" @endif >14:30</option>
                              <option value="15:00:00" @if($shedules->lab_hin2=="15:00:00") selected="selected" @endif >15:00</option>
                              <option value="15:30:00" @if($shedules->lab_hin2=="15:30:00") selected="selected" @endif >15:30</option>
                              <option value="16:00:00" @if($shedules->lab_hin2=="16:00:00") selected="selected" @endif >16:00</option>
                              <option value="16:30:00" @if($shedules->lab_hin2=="16:30:00") selected="selected" @endif >16:30</option>
                              <option value="17:00:00" @if($shedules->lab_hin2=="17:00:00") selected="selected" @endif >17:00</option>
                              <option value="17:30:00" @if($shedules->lab_hin2=="17:30:00") selected="selected" @endif >17:30</option>
                              <option value="18:00:00" @if($shedules->lab_hin2=="18:00:00") selected="selected" @endif >18:00</option>
                              <option value="18:30:00" @if($shedules->lab_hin2=="18:30:00") selected="selected" @endif >18:30</option>
                              <option value="19:00:00" @if($shedules->lab_hin2=="19:00:00") selected="selected" @endif >19:00</option>
                              <option value="19:30:00" @if($shedules->lab_hin2=="19:30:00") selected="selected" @endif >19:30</option>
                              <option value="20:00:00" @if($shedules->lab_hin2=="20:00:00") selected="selected" @endif >20:00</option>
                              <option value="20:30:00" @if($shedules->lab_hin2=="20:30:00") selected="selected" @endif >20:30</option>
                              <option value="21:00:00" @if($shedules->lab_hin2=="21:00:00") selected="selected" @endif >21:00</option>
                              <option value="21:30:00" @if($shedules->lab_hin2=="21:30:00") selected="selected" @endif >21:30</option>
                              <option value="22:00:00" @if($shedules->lab_hin2=="22:00:00") selected="selected" @endif >22:00</option>
                              <option value="22:30:00" @if($shedules->lab_hin2=="22:30:00") selected="selected" @endif >22:30</option>
                              <option value="23:00:00" @if($shedules->lab_hin2=="23:00:00") selected="selected" @endif >23:00</option>
                              <option value="23:30:00" @if($shedules->lab_hin2=="23:30:00") selected="selected" @endif >23:30</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_end_2[]" class="form-control form-date-1" id="reg_end_2<?php echo $i;?>" >
                              <option value="">--Hasta--</option>
                              <option value="06:00:00" @if($shedules->lab_hou2=="06:00:00") selected="selected" @endif>06:00</option>
                              <option value="06:30:00" @if($shedules->lab_hou2=="06:30:00") selected="selected" @endif >06:30</option>
                              <option value="07:00:00" @if($shedules->lab_hou2=="07:00:00") selected="selected" @endif >07:00</option>
                              <option value="07:30:00" @if($shedules->lab_hou2=="07:30:00") selected="selected" @endif >07:30</option>
                              <option value="08:00:00" @if($shedules->lab_hou2=="08:00:00") selected="selected" @endif >08:00</option>
                              <option value="08:30:00" @if($shedules->lab_hou2=="08:30:00") selected="selected" @endif >08:30</option>
                              <option value="09:00:00" @if($shedules->lab_hou2=="09:00:00") selected="selected" @endif >09:00</option>
                              <option value="09:30:00" @if($shedules->lab_hou2=="09:30:00") selected="selected" @endif >09:30</option>
                              <option value="10:00:00" @if($shedules->lab_hou2=="10:00:00") selected="selected" @endif >10:00</option>
                              <option value="10:30:00" @if($shedules->lab_hou2=="10:30:00") selected="selected" @endif >10:30</option>
                              <option value="11:00:00" @if($shedules->lab_hou2=="11:00:00") selected="selected" @endif >11:00</option>
                              <option value="11:30:00" @if($shedules->lab_hou2=="11:30:00") selected="selected" @endif >11:30</option>
                              <option value="12:00:00" @if($shedules->lab_hou2=="12:00:00") selected="selected" @endif >12:00</option>
                              <option value="12:30:00" @if($shedules->lab_hou2=="12:30:00") selected="selected" @endif >12:30</option>
                              <option value="13:00:00" @if($shedules->lab_hou2=="13:00:00") selected="selected" @endif >13:00</option>
                              <option value="13:30:00" @if($shedules->lab_hou2=="13:30:00") selected="selected" @endif >13:30</option>
                              <option value="14:00:00" @if($shedules->lab_hou2=="14:00:00") selected="selected" @endif >14:00</option>
                              <option value="14:30:00" @if($shedules->lab_hou2=="14:30:00") selected="selected" @endif >14:30</option>
                              <option value="15:00:00" @if($shedules->lab_hou2=="15:00:00") selected="selected" @endif >15:00</option>
                              <option value="15:30:00" @if($shedules->lab_hou2=="15:30:00") selected="selected" @endif >15:30</option>
                              <option value="16:00:00" @if($shedules->lab_hou2=="16:00:00") selected="selected" @endif >16:00</option>
                              <option value="16:30:00" @if($shedules->lab_hou2=="16:30:00") selected="selected" @endif >16:30</option>
                              <option value="17:00:00" @if($shedules->lab_hou2=="17:00:00") selected="selected" @endif >17:00</option>
                              <option value="17:30:00" @if($shedules->lab_hou2=="17:30:00") selected="selected" @endif >17:30</option>
                              <option value="18:00:00" @if($shedules->lab_hou2=="18:00:00") selected="selected" @endif >18:00</option>
                              <option value="18:30:00" @if($shedules->lab_hou2=="18:30:00") selected="selected" @endif >18:30</option>
                              <option value="19:00:00" @if($shedules->lab_hou2=="19:00:00") selected="selected" @endif >19:00</option>
                              <option value="19:30:00" @if($shedules->lab_hou2=="19:30:00") selected="selected" @endif >19:30</option>
                              <option value="20:00:00" @if($shedules->lab_hou2=="20:00:00") selected="selected" @endif >20:00</option>
                              <option value="20:30:00" @if($shedules->lab_hou2=="20:30:00") selected="selected" @endif >20:30</option>
                              <option value="21:00:00" @if($shedules->lab_hou2=="21:00:00") selected="selected" @endif >21:00</option>
                              <option value="21:30:00" @if($shedules->lab_hou2=="21:30:00") selected="selected" @endif >21:30</option>
                              <option value="22:00:00" @if($shedules->lab_hou2=="22:00:00") selected="selected" @endif >22:00</option>
                              <option value="22:30:00" @if($shedules->lab_hou2=="22:30:00") selected="selected" @endif >22:30</option>
                              <option value="23:00:00" @if($shedules->lab_hou2=="23:00:00") selected="selected" @endif >23:00</option>
                              <option value="23:30:00" @if($shedules->lab_hou2=="23:30:00") selected="selected" @endif >23:30</option>
                           </select>
                        </div>
                     </div>
                     @else
                     <div class="row">
                        <div class="col-md-55"> 
                           <label class="label-date"><?php echo nameDay($i);?></label>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_init[]" class="form-control form-date-1" id="reg_init_1<?php echo $i;?>" >
                              <option value="">--Desde--</option>
                              <option value="06:00:00">06:00</option>
                              <option value="06:30:00">06:30</option>
                              <option value="07:00:00">07:00</option>
                              <option value="07:30:00">07:30</option>
                              <option value="08:00:00">08:00</option>
                              <option value="08:30:00">08:30</option>
                              <option value="09:00:00">09:00</option>
                              <option value="09:30:00">09:30</option>
                              <option value="10:00:00">10:00</option>
                              <option value="10:30:00">10:30</option>
                              <option value="11:00:00">11:00</option>
                              <option value="11:30:00">11:30</option>
                              <option value="12:00:00">12:00</option>
                              <option value="12:30:00">12:30</option>
                              <option value="13:00:00">13:00</option>
                              <option value="13:30:00">13:30</option>
                              <option value="14:00:00">14:00</option>
                              <option value="14:30:00">14:30</option>
                              <option value="15:00:00">15:00</option>
                              <option value="15:30:00">15:30</option>
                              <option value="16:00:00">16:00</option>
                              <option value="16:30:00">16:30</option>
                              <option value="17:00:00">17:00</option>
                              <option value="17:30:00">17:30</option>
                              <option value="18:00:00">18:00</option>
                              <option value="18:30:00">18:30</option>
                              <option value="19:00:00">19:00</option>
                              <option value="19:30:00">19:30</option>
                              <option value="20:00:00">20:00</option>
                              <option value="20:30:00">20:30</option>
                              <option value="21:00:00">21:00</option>
                              <option value="21:30:00">21:30</option>
                              <option value="22:00:00">22:00</option>
                              <option value="22:30:00">22:30</option>
                              <option value="23:00:00">23:00</option>
                              <option value="23:30:00">23:30</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_end[]" class="form-control form-date-1" id="reg_end_1<?php echo $i;?>" >
                              <option value="">--Hasta--</option>
                              <option value="06:00:00">06:00</option>
                              <option value="06:30:00">06:30</option>
                              <option value="07:00:00">07:00</option>
                              <option value="07:30:00">07:30</option>
                              <option value="08:00:00">08:00</option>
                              <option value="08:30:00">08:30</option>
                              <option value="09:00:00">09:00</option>
                              <option value="09:30:00">09:30</option>
                              <option value="10:00:00">10:00</option>
                              <option value="10:30:00">10:30</option>
                              <option value="11:00:00">11:00</option>
                              <option value="11:30:00">11:30</option>
                              <option value="12:00:00">12:00</option>
                              <option value="12:30:00">12:30</option>
                              <option value="13:00:00">13:00</option>
                              <option value="13:30:00">13:30</option>
                              <option value="14:00:00">14:00</option>
                              <option value="14:30:00">14:30</option>
                              <option value="15:00:00">15:00</option>
                              <option value="15:30:00">15:30</option>
                              <option value="16:00:00">16:00</option>
                              <option value="16:30:00">16:30</option>
                              <option value="17:00:00">17:00</option>
                              <option value="17:30:00">17:30</option>
                              <option value="18:00:00">18:00</option>
                              <option value="18:30:00">18:30</option>
                              <option value="19:00:00">19:00</option>
                              <option value="19:30:00">19:30</option>
                              <option value="20:00:00">20:00</option>
                              <option value="20:30:00">20:30</option>
                              <option value="21:00:00">21:00</option>
                              <option value="21:30:00">21:30</option>
                              <option value="22:00:00">22:00</option>
                              <option value="22:30:00">22:30</option>
                              <option value="23:00:00">23:00</option>
                              <option value="23:30:00">23:30</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_init_2[]" class="form-control form-date-1" id="reg_init_2<?php echo $i;?>" >
                              <option value="">--Desde--</option>
                              <option value="06:00:00">06:00</option>
                              <option value="06:30:00">06:30</option>
                              <option value="07:00:00">07:00</option>
                              <option value="07:30:00">07:30</option>
                              <option value="08:00:00">08:00</option>
                              <option value="08:30:00">08:30</option>
                              <option value="09:00:00">09:00</option>
                              <option value="09:30:00">09:30</option>
                              <option value="10:00:00">10:00</option>
                              <option value="10:30:00">10:30</option>
                              <option value="11:00:00">11:00</option>
                              <option value="11:30:00">11:30</option>
                              <option value="12:00:00">12:00</option>
                              <option value="12:30:00">12:30</option>
                              <option value="13:00:00">13:00</option>
                              <option value="13:30:00">13:30</option>
                              <option value="14:00:00">14:00</option>
                              <option value="14:30:00">14:30</option>
                              <option value="15:00:00">15:00</option>
                              <option value="15:30:00">15:30</option>
                              <option value="16:00:00">16:00</option>
                              <option value="16:30:00">16:30</option>
                              <option value="17:00:00">17:00</option>
                              <option value="17:30:00">17:30</option>
                              <option value="18:00:00">18:00</option>
                              <option value="18:30:00">18:30</option>
                              <option value="19:00:00">19:00</option>
                              <option value="19:30:00">19:30</option>
                              <option value="20:00:00">20:00</option>
                              <option value="20:30:00">20:30</option>
                              <option value="21:00:00">21:00</option>
                              <option value="21:30:00">21:30</option>
                              <option value="22:00:00">22:00</option>
                              <option value="22:30:00">22:30</option>
                              <option value="23:00:00">23:00</option>
                              <option value="23:30:00">23:30</option>
                           </select>
                        </div>
                        <div class="col-md-55">
                           <select name="reg_end_2[]" class="form-control form-date-1" id="reg_end_2<?php echo $i;?>" >
                              <option value="">--Hasta--</option>
                              <option value="06:00:00">06:00</option>
                              <option value="06:30:00">06:30</option>
                              <option value="07:00:00">07:00</option>
                              <option value="07:30:00">07:30</option>
                              <option value="08:00:00">08:00</option>
                              <option value="08:30:00">08:30</option>
                              <option value="09:00:00">09:00</option>
                              <option value="09:30:00">09:30</option>
                              <option value="10:00:00">10:00</option>
                              <option value="10:30:00">10:30</option>
                              <option value="11:00:00">11:00</option>
                              <option value="11:30:00">11:30</option>
                              <option value="12:00:00">12:00</option>
                              <option value="12:30:00">12:30</option>
                              <option value="13:00:00">13:00</option>
                              <option value="13:30:00">13:30</option>
                              <option value="14:00:00">14:00</option>
                              <option value="14:30:00">14:30</option>
                              <option value="15:00:00">15:00</option>
                              <option value="15:30:00">15:30</option>
                              <option value="16:00:00">16:00</option>
                              <option value="16:30:00">16:30</option>
                              <option value="17:00:00">17:00</option>
                              <option value="17:30:00">17:30</option>
                              <option value="18:00:00">18:00</option>
                              <option value="18:30:00">18:30</option>
                              <option value="19:00:00">19:00</option>
                              <option value="19:30:00">19:30</option>
                              <option value="20:00:00">20:00</option>
                              <option value="20:30:00">20:30</option>
                              <option value="21:00:00">21:00</option>
                              <option value="21:30:00">21:30</option>
                              <option value="22:00:00">22:00</option>
                              <option value="22:30:00">22:30</option>
                              <option value="23:00:00">23:00</option>
                              <option value="23:30:00">23:30</option>
                           </select>
                        </div>
                     </div>
                     @endif
                     @endfor
                  </div>
                  <div class="form-group">
                     <button type="button" onclick="update_shedules_business()"  class="btn btn-success" id="boton-1">Guardar</button>
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
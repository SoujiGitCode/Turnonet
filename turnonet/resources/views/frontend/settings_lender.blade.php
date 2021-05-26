@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?> > <?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Configuración del prestador</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Actualice las configuración del prestador aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" name="id" value="<?php echo $settings->cf_id;?>">
                  <input type="hidden" name="pres_id" value="<?php echo $get_lender->tmsp_id;?>">

                  <input type="hidden" name="emp_id" value="<?php echo  $get_business->em_id;?>">

                  <p>Seleccione los parámetros generales de configuración del prestador. En caso de duda o consulta comuniquese con nuestro sector de soporte.</p>
                  <div class="form-group btop bdashed">
                     <label class="label-1">Duración:</label>
                     <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6">
                           <label>Duración Promedio</label>
                           <?php
                              $date = explode(':',$settings->cf_turt);
                              $hour=$date[0];
                              $minutes=$date[1]; 
                              
                              ?>
                           <select name="hours" class="form-control" id="hours">
                           <option value="00" @if($hour=="00") selected="selected" @endif >0 Horas</option>
                           <option value="01" @if($hour=="01") selected="selected" @endif >1 Hora</option>
                           <option value="02" @if($hour=="02") selected="selected" @endif >2 Horas</option>
                           <option value="03" @if($hour=="03") selected="selected" @endif >3 Horas</option>
                           <option value="04" @if($hour=="04") selected="selected" @endif >4 Horas</option>
                           <option value="05" @if($hour=="05") selected="selected" @endif >05 Horas</option>
                           <option value="06" @if($hour=="06") selected="selected" @endif >06 Horas</option>
                           <option value="07" @if($hour=="07") selected="selected" @endif >07 Horas</option>
                           <option value="08" @if($hour=="08") selected="selected" @endif >08 Horas</option>
                           <option value="09" @if($hour=="09") selected="selected" @endif >09 Horas</option> 
                           <option value="10" @if($hour=="10") selected="selected" @endif >10 Horas</option>
                           <option value="11" @if($hour=="11") selected="selected" @endif >11 Horas</option>
                           <option value="12" @if($hour=="12") selected="selected" @endif >12 Horas</option>
                           <option value="13" @if($hour=="13") selected="selected" @endif >13 Horas</option>
                           <option value="14" @if($hour=="14") selected="selected" @endif >14 Horas</option> 
                           <option value="15" @if($hour=="15") selected="selected" @endif >15 Horas</option>
                           <option value="16" @if($hour=="16") selected="selected" @endif >16 Horas</option> 
                           <option value="17" @if($hour=="17") selected="selected" @endif >17 Horas</option>
                           <option value="18" @if($hour=="18") selected="selected" @endif >18 Horas</option> 
                           <option value="19" @if($hour=="19") selected="selected" @endif >19 Horas</option>
                           <option value="20" @if($hour=="20") selected="selected" @endif >20 Horas</option>  
                           </select>
                           <br>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                           <label>&nbsp;</label>
                           <select name="minutes" class="form-control" id="minutes">
                           <option value="00" @if($minutes=="00") selected="selected" @endif >0 Minutos</option>
                           <option value="05" @if($minutes=="05") selected="selected" @endif >5 Minutos</option>
                           <option value="10" @if($minutes=="10") selected="selected" @endif >10 Minutos</option>
                           <option value="12" @if($minutes=="12") selected="selected" @endif >12 Minutos</option>
                           <option value="15" @if($minutes=="15") selected="selected" @endif >15 Minutos</option>  
                           <option value="20" @if($minutes=="20") selected="selected" @endif >20 Minutos</option>  
                           <option value="25" @if($minutes=="25") selected="selected" @endif >25 Minutos</option>  
                           <option value="30" @if($minutes=="30") selected="selected" @endif >30 Minutos</option>  
                           <option value="35" @if($minutes=="35") selected="selected" @endif >35 Minutos</option>  
                           <option value="40" @if($minutes=="40") selected="selected" @endif >40 Minutos</option>  
                           <option value="45" @if($minutes=="45") selected="selected" @endif >45 Minutos</option> 
                           <option value="50" @if($minutes=="50") selected="selected" @endif >50 Minutos</option>
                           <option value="55" @if($minutes=="55") selected="selected" @endif >55 Minutos</option> 
                           </select>
                        </div>
                     </div>
                     <p class="form-desc">La duración promedio del turno determina la cantidad de turnos disponibles en el rango horario de atención.</p>
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">Turnos Simultáneos:</label>
                     <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                           <label>Cantidad</label>
                           <select name="cf_simtu" class="form-control" id="cf_simtu">
                           <option value="1" @if($settings->cf_simtu=="1") selected="selected" @endif>1 Turno</option>
                           <option value="2"  @if($settings->cf_simtu=="2") selected="selected" @endif>2 Turnos</option>
                           <option value="3"  @if($settings->cf_simtu=="3") selected="selected" @endif>3 Turnos</option>
                           <option value="4"  @if($settings->cf_simtu=="4") selected="selected" @endif>4 Turnos</option>
                           <option value="5"  @if($settings->cf_simtu=="5") selected="selected" @endif>5 Turnos</option>
                           <option value="6"  @if($settings->cf_simtu=="6") selected="selected" @endif>6 Turnos</option>
                           <option value="7"  @if($settings->cf_simtu=="7") selected="selected" @endif>7 Turnos</option>
                           <option value="8"  @if($settings->cf_simtu=="8") selected="selected" @endif>8 Turnos</option>
                           <option value="9"  @if($settings->cf_simtu=="9") selected="selected" @endif>9 Turnos</option>
                           <option value="10" @if($settings->cf_simtu=="10") selected="selected" @endif>10 Turnos</option>
                           <option value="11"  @if($settings->cf_simtu=="11") selected="selected" @endif>11 Turnos</option>
                           <option value="12"  @if($settings->cf_simtu=="12") selected="selected" @endif>12 Turnos</option>
                           <option value="13"  @if($settings->cf_simtu=="13") selected="selected" @endif>13 Turnos</option>
                           <option value="14"  @if($settings->cf_simtu=="14") selected="selected" @endif>14 Turnos</option>
                           <option value="15"  @if($settings->cf_simtu=="15") selected="selected" @endif>15 Turnos</option>
                           <option value="16"  @if($settings->cf_simtu=="16") selected="selected" @endif>16 Turnos</option>
                           <option value="17"  @if($settings->cf_simtu=="17") selected="selected" @endif>17 Turnos</option>
                           <option value="18"  @if($settings->cf_simtu=="18") selected="selected" @endif>18 Turnos</option>
                           <option value="19"  @if($settings->cf_simtu=="19") selected="selected" @endif>19 Turnos</option>
                           <option value="20"  @if($settings->cf_simtu=="20") selected="selected" @endif>20 Turnos</option>
                           </select>
                        </div>
                     </div>
                     <p class="form-desc"><br>Mediante esta opción se permite que un único centro de atención administre simultaneidad de turnos de forma sencilla. Es ideal donde no es relevante el prestador que atiende al cliente. Es recomendado su utilización para peluquerías, centros de estética, centros de atención al cliente y gestión de reclamos.</p>
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">Dar Turnos Desde:</label>
                     <p class="form-desc">Defina hasta cuantos días antes los clientes pueden tomar turnos. </p>
                     <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                           <label>a partir de</label>
                           <select name="cf_daysp" class="form-control" id="cf_daysp">
                           <option value="0" @if($settings->cf_daysp=="0") selected="selected" @endif>--En cualquier momento--</option>
                           <option value="1" @if($settings->cf_daysp=="1") selected="selected" @endif>1 Día</option>
                           <option value="2" @if($settings->cf_daysp=="2") selected="selected" @endif>2 Días</option>
                           <option value="3" @if($settings->cf_daysp=="3") selected="selected" @endif>3 Días</option>
                           <option value="4" @if($settings->cf_daysp=="4") selected="selected" @endif>4 Días</option>
                           <option value="5" @if($settings->cf_daysp=="5") selected="selected" @endif>5 Días</option>
                           <option value="6" @if($settings->cf_daysp=="6") selected="selected" @endif>6 Días</option>
                           <option value="7" @if($settings->cf_daysp=="7") selected="selected"  @endif>7 Días</option>
                           <option value="8" @if($settings->cf_daysp=="8") selected="selected" @endif>8 Días</option>
                           <option value="9" @if($settings->cf_daysp=="9") selected="selected" @endif>9 Días</option>
                           <option value="10"  @if($settings->cf_daysp=="10") selected="selected"  @endif>10 Días</option>
                           <option value="11" @if($settings->cf_daysp=="11") selected="selected"  @endif>11 Días</option>
                           <option value="12" @if($settings->cf_daysp=="12") selected="selected"  @endif>12 Días</option>
                           <option value="13" @if($settings->cf_daysp=="13") selected="selected"  @endif>13 Días</option>
                           <option value="14" @if($settings->cf_daysp=="14") selected="selected"  @endif>14 Días</option>
                           <option value="15" @if($settings->cf_daysp=="15") selected="selected"  @endif>15 Días</option>
                           </select>
                           <br>
                        </div>
                     </div>
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">Dar Turnos Hasta:</label>
                     <p class="form-desc">Defina el horizonte temporal por el cuál los clientes van a estar tomando turnos.</p>
                     <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                           <label>Los Próximos</label>
                           <select name="cf_days" class="form-control" id="cf_days">
                           <option value="7" @if($settings->cf_days=="7") selected="selected" @endif >7  Días</option>
                           <option value="15" @if($settings->cf_days=="15") selected="selected" @endif >15  Días</option>
                           <option value="28" @if($settings->cf_days=="28") selected="selected" @endif >28  Días</option>
                           <option value="30" @if($settings->cf_days=="30") selected="selected" @endif >30 Días</option>
                           <option value="60" @if($settings->cf_days=="60") selected="selected" @endif >60 Días</option>
                           <option value="90" @if($settings->cf_days=="90") selected="selected" @endif >90 Días</option>
                           <option value="120" @if($settings->cf_days=="120") selected="selected" @endif >120 Días</option>
                           <option value="150" @if($settings->cf_days=="150") selected="selected" @endif >150 Días</option>
                           <option value="180" @if($settings->cf_days=="180") selected="selected" @endif >180 Días</option>
                           <option value="210" @if($settings->cf_days=="210") selected="selected" @endif>210 Días</option>
                           <option value="240" @if($settings->cf_days=="240") selected="selected" @endif >240 Días</option>
                           <option value="270" @if($settings->cf_days=="270") selected="selected" @endif >270 Días</option>
                           <option value="300" @if($settings->cf_days=="300") selected="selected" @endif >300 Días</option>
                           <option value="330" @if($settings->cf_days=="330") selected="selected" @endif >330 Días</option>
                           <option value="360" @if($settings->cf_days=="360") selected="selected" @endif >360 Días</option>
                           </select>
                           <br>
                        </div>
                     </div>
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">Cancelación:</label>
                     <p class="form-desc">Defina el tiempo limite por el cuál los clientes pueden cancelar sus turnos.</p>
                     <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                           <label>Hasta</label>
                           <select name="cf_tcan" class="form-control" id="cf_tcan">
                           <option value="00:30:00" @if($settings->cf_tcan=="00:30:00") selected="selected" @endif>Media Hora antes</option>
                           <option value="01:00:00" @if($settings->cf_tcan=="01:00:00") selected="selected" @endif >1 Hora antes</option>
                           <option value="02:00:00" @if($settings->cf_tcan=="02:00:00") selected="selected" @endif>2 Horas antes</option>
                           <option value="04:00:00" @if($settings->cf_tcan=="04:00:00") selected="selected" @endif>4 Horas antes</option>
                           <option value="08:00:00" @if($settings->cf_tcan=="08:00:00") selected="selected" @endif>8 Horas antes</option>
                           <option value="24:01:00"  @if($settings->cf_tcan=="24:01:00") selected="selected" @endif>El Dia anterior</option>
                           <option value="48:01:00" @if($settings->cf_tcan=="48:01:00") selected="selected" @endif>2 Dias antes</option>
                           <option value="72:01:00" @if($settings->cf_tcan=="72:01:00") selected="selected" @endif>3 Dias antes</option>
                           </select>
                           <br>
                        </div>
                     </div>
                  </div>
                  <div class="form-group btop bdashed">
                     <label class="label-1">Validación de clientes:</label>
                     <p class="form-desc">Seleccione la forma en que validan los usuarios para solicitar turno.</p>
                     <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                           <label>Tipo</label>
                           <select name="cf_tipval" class="form-control" id="cf_tipval">
                           <option value="1" @if($settings->cf_tipval=='1') selected="selected" @endif >Email</option>
                           <option value="5" @if($settings->cf_tipval=='5') selected="selected" @endif>DNI</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  @if($get_business->zoom_act==1)
                  <div class="form-group btop bdashed">
                     <label class="label-1">VIDEO CONFERENCIAS: <img src="<?php echo url('img/video-player.png');?>" class="mpago1"></label>
                     <p>Genera un meeting por cada turno que tus usuarios tomen en la agenda.</p>
                     <div class="demoo1">
                     	<input type="checkbox" name="activate_zoom" id="activate_zoom" value="1" @if($get_lender->activate_zoom=='1') checked="checked" @endif>
                     	<label for="activate_zoom"><span></span>Activar/Desactivar creación de meeting.</label>
                     </div>
                  </div>
                  @endif
                  @if($get_business->em_mp==1 && $get_business->access_token!="")
                  <?php $service = DB::table('tu_emps_serv')->where('serv_presid',$get_lender->tmsp_id)->where('serv_estado','1')->where('serv_tipo','1')->count(); ?>
                  <div class="form-group btop bdashed">
                     <label class="label-1">MERCADO PAGO: <img src="<?php echo url('img/mplogo.png');?>" class="mpago"></label>
                     @if($service!=0) 
                     <div class="demoo1">
                        <input type="checkbox" name="tmsp_pagoA" id="tmsp_pagoA" value="1" @if($get_lender->tmsp_pagoA=='ALTA') checked="checked" @endif>
                        <label for="tmsp_pagoA"><span></span>Active/Desactive la pasarela de pagos en el frame de turnos.</label>
                     </div>
                     @else
                     <br>
                     <div class="alert alert-warning" role="alert">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> No tienes servicios disponibles para  activar mercado pago. Registra un servicio haciendo <a href="<?php echo url('prestador/servicios/'.$get_lender->tmsp_id);?>">click aquí</a>
                     </div>
                     @endif
                  </div>
                  @endif
                  <div class="form-group">
                     <br>
                     <button type="button" onclick="update_settings()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                  </div>
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
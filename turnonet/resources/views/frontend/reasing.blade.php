@extends('layouts.template_frontend_inside')
@section('content')
<form id="form">
   <div class="container" ng-app="myApp" ng-controller="post">
      <div class="row">
         <div class="col-md-8 col-sm-8 col-xs-12">
            <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
            <h4 class="title-date-2">AGENDA</h4>
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px">
               <div class="panel">
                  <div class="panel-heading">
                     Reasignar Turno
                  </div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                           <div class="panel-pres">
                              <div class="col-xs-1 no-padding-desktop">
                                 <?php
                                    $image=url('/').'/img/empty.jpg';
                                    
                                    $get_business=DB::table('tu_emps')->where('em_id',$lender->emp_id)->first();
                                    
                                    $sucursal=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first();
                                    
                                    if(isset($get_business)!=0 && $get_business->em_pfot!=""){
                                      $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
                                    }
                                    if(isset($sucursal)!=0 && $sucursal->suc_pfot!=""){
                                      $image="https://www.turnonet.com/fotos/sucursales/".$sucursal->suc_pfot;
                                    }
                                    if($lender->tmsp_pfot!=""){
                                      $image="https://www.turnonet.com/fotos/prestadores/".$lender->tmsp_pfot;
                                    }
                                    
                                    ?>
                                 <img src="<?php echo $image;?>" class="img-pres">
                              </div>
                              <div class="col-xs-11 no-padding-desktop">
                                 <div class="tit" style="margin-bottom: 0vw;">
                                    <?php echo mb_strtoupper($lender->tmsp_pnom);?>     
                                 </div>
                                 <?php $address=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first(); ?>
                                 @if(isset($address)!=0)
                                 <div class="subtit "> <?php echo $address->suc_dom;?> <?php echo $address->suc_domnum;?> <?php echo $address->suc_dompiso;?> </div>
                                 @endif
                              </div>
                           </div>
                           <div class="form-group btop" >
                              <label class="label-1">DATOS DEL CLIENTE:</label>
                              <p class="p-1" title="Ver datos del cliente" onclick="actModalPac('<?php echo $user->id;?>','<?php echo $shift->tu_id;?>')" style="cursor: pointer;"><i class="fa fa-search"></i> <?php echo mb_strtoupper($user->name);?></p>
                              @if($user->email!="")
                              <p class="p-1" onclick="window.open('mailto:<?php echo strtolower($user->email);?>')"><i class="fa fa-envelope-o"></i> <?php echo strtolower($user->email);?></p>
                              @endif
                           </div>
                           <div class="form-group btop" >
                              <label class="label-1">Motivo de reasignación:</label>
                              {!! Form::textarea('message',null,['id'=>'message','placeholder'=>'Ingresá un comentario*','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
                           </div>
                           @if($shift->service_select!="0" &&  $shift->service_select!="" && count($services)!=0)
                           @if(count($services)==1)
                           <input type="hidden" id="service_select" name="service_select" value="<?php echo $shift->service_select;?>">
                           @else
                           <input type="hidden" id="service_select" name="service_select" value="<?php echo $shift->service_select;?>">
                           <div class="form-group input-serv">
                              <label class="label-1">SERVICIOS SOLICITADOS*</label>
                              <select name="service" id="service" class="form-control">
                                 <option value="" >Seleccioná</option>
                                 @foreach($services as $rs)
                                 <option value="<?php echo $rs->serv_id;?>" @if(strpos($rs->serv_id,$shift->service_select)!== false) disabled @endif><?php echo trim($rs->serv_nom);?></option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="form-group input-serv">
                              <ul class="list-opt" id="list-opt">
                                 <?php
                                    if (substr_count($shift->service_select, '-') <= 0){
                                    
                                    
                                    $get_service = DB::table('tu_emps_serv')->where('serv_id',$shift->service_select)->first();
                                    
                                     if (isset($get_service) != 0) {
                                      
                                    
                                       echo '<li id="serv-'.$get_service->serv_id.'">'.trim($get_service->serv_nom).'<label class="edit-profile" onclick="removeLi('.$get_service->serv_id.')" title="Eliminar servicio"><i class="fa fa-times"></i></label></li>';
                                    }
                                    
                                    }else{
                                    
                                    
                                    for ($i = 0; $i <= substr_count($shift->service_select, '-'); $i++) {
                                     $service = explode('-', $shift->service_select);
                                     $get_service = DB::table('tu_emps_serv')->where('serv_id',$service[$i])->first();
                                    
                                     if (isset($get_service) != 0) {
                                    
                                    
                                       echo '<li id="serv-'.$get_service->serv_id.'">'.trim($get_service->serv_nom).'<label class="edit-profile" onclick="removeLi('.$get_service->serv_id.')" title="Eliminar servicio"><i class="fa fa-times"></i></label></li>';
                                    }
                                    
                                    }
                                    }
                                    
                                    ?>
                              </ul>
                           </div>
                           @endif
                           @endif
                           <br>
                            <div class="form-group">
                            <br>
                             <div class="demoo1">
                              <input type="checkbox" name="aviso" id="aviso" value="1" checked="checked">
                              <label for="aviso"><span></span>Enviar email de aviso al cliente</label>
                            </div>
                            <br>
                          </div>
                           <div class="form-group">
                              <label class="switch-wrap">
                                 <input type="checkbox" id="tu_st" name="tu_st" value="1" @if($shift->tu_st==1) checked="checked" @endif onchange="upSobreturno()">
                                 <div class="switch"></div>
                              </label>
                              <span>Cargar como sobreturno</span>
                              <br>
                           </div>
                           <div id="capa">
                              <div class="col-xs-12 content-spinner" id="prevloader">
                                 <div class="spinner">
                                    <div class="double-bounce1"></div>
                                    <div class="double-bounce2"></div>
                                 </div>
                              </div>
                              <div class="col-xs-12" id="prevcalendario" style="display: none; padding: 0px">
                                 <div class="lodcal">
                                    <div id="s_cal">
                                       <div class="row s_day" style="margin-left:0; margin-right:0  ">
                                          <div class="col-xs-2">
                                             <a ng-click="prevMonth()" ng-show="prevmonth!=month_act || month_act!=month_select"  class="relcal"><img src="https://i.imgur.com/FGybL6Z.png" class="arrow-prev" id="arrowprev"></a>
                                          </div>
                                          <div class="col-xs-8">
                                             <div class="">@{{name_month}}</div>
                                          </div>
                                          <div class="col-xs-2">
                                             <a class="relcal" ng-click="nextMonth()"><img src="https://i.imgur.com/R8865BH.png" class="arrow-next"></a>
                                          </div>
                                       </div>
                                       <div class="s_sd">L</div>
                                       <div class="s_sd">M</div>
                                       <div class="s_sd">M</div>
                                       <div class="s_sd">J</div>
                                       <div class="s_sd">V</div>
                                       <div class="s_sd">S</div>
                                       <div class="s_sd" style="border-right: none!important;">D</div>
                                       <div ng-repeat="row in list_calendar" class="@{{row.class}}">
                                          <div ng-show="row.date!='' && row.active=='1'" class="circlegreen" ng-click="selectDay(row.date)">@{{row.day}}</div>
                                          <div ng-show="row.date!='' && row.active=='0'" class="circlepink">@{{row.day}}</div>
                                       </div>
                                    </div>
                                    </script>                   
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <p>Campos obligatorios (*)</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-4 col-xs-12 n-ppding">
            @include('includes.business')
         </div>
      </div>
      <div id="myModal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <h3 id="title-modal">Horarios</h3>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class=" col-xs-12">
                        <div class="col-md-12  col-sm-12 col-xs-12 content-spinner" id="prevloadertimes">
                           <div class="spinner">
                              <div class="double-bounce1"></div>
                              <div class="double-bounce2"></div>
                           </div>
                        </div>
                        <div class="col-md-12 col-xs-12" style="display: none; padding: 0px" id="prevtimes">
                           <p class="text-date-33 text-center">@{{name_day}}</p>
                           <div class="col-md-12 col-xs-12 text-center" ng-show="totalItems_times==0">
                              <img src="<?php echo url('/');?>/uploads/icons/noresult.png" class="img-not-res">
                              <p class="text-date text-center">No quedan turnos disponibles para la fecha seleccionada.<br><br></p>
                              <div class="row">
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button  class="btn btn-block btn-primary" name="button" type="button" onclick="closeModal()">Seleccioná otra fecha</button>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12 col-xs-12 text-center" style="clear: both; padding: 0px"  ng-show="totalItems_times!=0">
                              <div class="times" ng-repeat="row in list_times">
                                 <div class="hora" >
                                    <a ng-click="selectTime(row.time,row.id)" id="time-@{{row.id}}" title="@{{row.title}}" class="cturno">@{{row.time_format}}</a>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12 col-xs-12" style="clear: both; padding: 0px"  ng-show="totalItems_times!=0">
                              <p class="text-date-33 text-center">Toca para seleccioná un horario</p>
                              <div class="row">
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button  class="btn btn-block btn-primary" name="button" type="button" onclick="closeModal()">Seleccioná otra fecha</button>
                                    <button class="btn btn-block btn-info" id="btn-create" type="button" disabled name="button" onclick="guardar()">Agendar Turno</button>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" id="empid" name="emp_id" value="<?php echo $lender->emp_id;?>">
   <input type="hidden" id="sucid" name="suc_id" value="<?php echo $lender->suc_id;?>">
   <input type="hidden" id="presid" name="pres_id" value="<?php echo $lender->tmsp_id;?>">
   <input type="hidden" id="us_id"  name="us_id" value="<?php echo $shift->us_id;?>">
   <input type="hidden" id="vadcod" name="vadcod" value="<?php echo substr($get_business->em_valcod,0,4);?>">
   <input type="hidden" name="tu_id"  id="tu_id" value="<?php echo $shift->tu_id;?>">
   <input type="hidden" name="tu_fec"  id="date">
   <input type="hidden" name="tu_hora" id="times">
   <input type="hidden" id="month" value="<?php echo date("m");?>">
   <input type="hidden" id="year" value="<?php echo date("Y");?>">
   <input type="hidden" id="redirect" value="/agenda/empresa/<?php echo $get_business->em_id;?>">
   <?php echo Html::script('frontend/js/reasing_shift.js?v='.time())?>
   <?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
</form>
@stop
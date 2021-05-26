<?php 
$get_country=DB::table('tu_pais')->where('pa_id', $get_business->em_pais)->first();
date_default_timezone_set($get_country->time_zone);
?>


@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="post">
   <div class="row">
      <div class="col-md-8 col-sm-8 col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <div class="col-md-12 col-sm-12 col-xs-12">
    
         <ul class="nav nav-pills">
            <li class="active"><a href="<?php echo url('directorio/empresa/'.$get_business->em_id);?>">Ver otros clientes</a></li>
       
         </ul>
      
      </div>
         <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px">
            <div class="panel">
               <div class="panel-heading">
                  <?php echo $get_user->name ?>
               </div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-3 col-sm-3 col-xs-12"  style="clear: both;">
                        <select class="form-control select-1" id="type_shift" name="type_shift" onchange="angular.element(this).scope().rows()">
                           <option value="ALL">Todos los turnos</option>
                           <option value="3">Próximos </option>
                           <option value="1">Atendidos</option>
                           <option value="0">Ausencia</option>
                           <option value="2">Asistencia parcial</option>
                           <option value="SOBRETURNO">Sobreturno</option>
                           <option value="ADMIN">Agendado por prestador</option>
                           <option value="USER">Agendado por cliente</option>
                        </select>
                     </div>
                      @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')

                      <div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                        <select class="form-control select-1" id="branch" name="branch" onchange="changeSucursal()">
                           <option value="ALL">Todas las sucursales</option>
                           @foreach($branch as $rs)
                           <option value="<?php echo $rs->suc_id;?>"><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                           @endforeach
                        </select>
                     </div>

                     <div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                        <select class="form-control select-1" id="lenders" name="lenders" onchange="angular.element(this).scope().rows()">
                           <option value="ALL">Todos los prestadores</option>
                           @foreach($lenders as $rs)
                           <option value="<?php echo $rs->tmsp_id;?>"><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                           @endforeach
                        </select>
                     </div>
                     
                     @else


                     <div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                        @if(isset(Auth::guard('user')->User()->suc_id) && Auth::guard('user')->User()->suc_id!="" && Auth::guard('user')->User()->suc_id!="0" )
                        <select class="form-control select-1" id="branch" name="branch" disabled="disabled" onchange="changeSucursal()">
                           <option value="ALL">Todas las sucursales</option>
                           @foreach($branch as $rs)
                           <option value="<?php echo $rs->suc_id;?>" @if($rs->suc_id==Auth::guard('user')->User()->suc_id) selected="selected" @endif><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                           @endforeach
                        </select>
                        @else
                        <select class="form-control select-1" id="branch" name="branch" onchange="changeSucursal()">
                           <option value="ALL">Todas las sucursales</option>
                           @foreach($branch as $rs)
                           <option value="<?php echo $rs->suc_id;?>"><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                           @endforeach
                        </select>
                        @endif
                     </div>

                     <div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                        @if(isset(Auth::guard('user')->User()->pres_id) && Auth::guard('user')->User()->pres_id!="" && Auth::guard('user')->User()->pres_id!="0")
                        <select class="form-control select-1" id="lenders" name="lenders" disabled="disabled" onchange="angular.element(this).scope().rows()">
                           <option value="ALL">Todos los prestadores</option>
                           @foreach($lenders as $rs)
                           <option value="<?php echo $rs->tmsp_id;?>" @if($rs->tmsp_id==Auth::guard('user')->User()->pres_id) selected="selected" @endif ><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                           @endforeach
                        </select>
                        @else
                        <select class="form-control select-1" id="lenders" name="lenders"  onchange="angular.element(this).scope().rows()">
                           <option value="ALL">Todos los prestadores</option>
                           @foreach($lenders as $rs)
                           <option value="<?php echo $rs->tmsp_id;?>"  ><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                           @endforeach
                        </select>
                        @endif
                     </div>
                     
                     @endif
                     <div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                         <input type='text' id='fecha_diary' class="form-control select-1" value="" placeholder="Seleccioná una fecha"/>
                       
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12" style="clear: both;">
                        <br>
                        <a ng-click="sortBy('timestamp')" ng-if="filteredItems!=0" title="Ordena el listado turnos por fecha/hora">Ordenar por Fecha/Hora  <span   class="sortorder"  ng-class="{reverse: reverse}"></span></a>
                        <br>
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12" style="clear: both;">
                        <br>
                     </div>
                     
                     <div class="mtop2 bg-preload" style="width: 100%">
                        <div class="row">
                           <div class="col-xs-12 content-spinner">
                              <div class="spinner">
                                 <div class="double-bounce1"></div>
                                 <div class="double-bounce2"></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="bg-list"  style="display: none; width: 100%" >
                        <div class="row" ng-if="filteredItems <= 0 || (list | filter:search).length === 0">
                           <div class="col-md-12" style="text-align: center;">
                              <img src="<?php echo url('/');?>/uploads/icons/noresult.png" alt="No hay resultados" class="img-not-res">
                              <p>No hay turnos solicitados por el cliente</p>
                           </div>
                        </div>
                     </div>
                     <div class="bg-list" ng-show="filteredItems > 0" style="display: none; width: 100%">
                        <div class="row list-hives" ng-repeat="row in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                           <div class="col-xs-10 p04">
                              <h3 class="card-title" >
                                 @{{row.date}} @{{row.hour}} @{{row.time}}
                              </h3>

                              <img src="<?php echo url('uploads/icons/clock.png');?>" title="Sobreturno" class="clock-inc" ng-show="row.tu_st==1">
                              <h4 ng-click="goTourl(row.code)" title="Ver detalle del turno" style="cursor: pointer;">@{{row.code}}</h4>
                              <p ng-click="OpemModalPas(row.us_id,row.id)" style="cursor: pointer;" title="Ver datos del cliente"><strong><i class="fa fa-search"></i> CLTE:</strong> @{{row.name}}</p>
                              <p><strong>Pres:</strong> @{{row.lender}}</p>
                              <p ng-show="row.services!=''"><strong>Serv:</strong> @{{row.services}}</p>
                              <div class="cntent-ass" style="display: none;" id="content-@{{row.id}}">
                                 <div class="panel-ass">
                                    <ul>
                                       <li class="li-asis" ng-click="asisConf(row.id)" ng-show="row.tu_asist!=1" style="border-bottom: 1px solid #fbf1f1">Confirmá la asistencia</li>
                                       <li class="li-au" ng-click="asisCan(row.id)" ng-show="row.tu_asist!=0">Informá la ausencia</li>
                                       <li class="li-p" ng-click="asisPar(row.id)" ng-show="row.tu_asist!=2" style="border-top: 1px solid #fbf1f1;">Asistencia parcial</li>
                                    </ul>
                                 </div>
                              </div>
                            
                              <div class="caret-op1" ng-show="row.status !='ALTA'">CANCELADO</div>
                            


                              <div class="caret-op" ng-show="row.tu_asist=='1' && row.status=='ALTA'">Atendido</div>
                              <div class="caret-op1" ng-show="row.tu_asist=='0' && row.status=='ALTA' ">Ausencia</div>
                              <div class="caret-op2" ng-show="row.tu_asist=='2' && row.status=='ALTA'">Asistencia parcial</div>
                              <div class="caret-op4" ng-show="row.tu_asist=='3' && row.status=='ALTA'">Por atender</div>
                              <div class="caret-op4" ng-show="row.tu_asist=='4' && row.status=='ALTA'">Expirado</div>
                              <div class="caret-op5" ng-show="row.tu_carga=='0'">Agendado: Pres.</div>
                              <div class="caret-op5" ng-show="row.tu_carga !='0'">Agendado: Clte.</div>

                              <div class="caret-op7 hidden-xs" ng-click="pdfCliente(row.code,row.id_business,row.us_id)"  title="Ver en PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Datos del cliente</div>
                              <div class="caret-op8 hidden-xs" ng-click="pdfTurno(row.code)"  title="Ver en PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Detalle del turno</div>
                           </div>
                           <div class="col-xs-2 bg-grey">
                              <ul class="lists-opt" ng-show="row.status !='ALTA'" >
                                 <li title="Gestionar Asistencia" disabled="disabled" style="cursor: not-allowed;"><a style="cursor: not-allowed;" ><span class="nav-icon-1  confirm-as" style="cursor: not-allowed;"></span></a></li>
                                 <li title="Cancelar turno" disabled="disabled" style="cursor: not-allowed;"><a style="cursor: not-allowed;" ><span class="nav-icon-1  cancel-1" style="cursor: not-allowed;"></span></a></li>
                                 <li title="Reasignar turno" disabled="disabled" style="cursor: not-allowed;"><a style="cursor: not-allowed;" ><span class="nav-icon-1 reload" style="cursor: not-allowed;"></span></a></li>
                                 <li title="Nuevo turno" disabled="disabled" style="cursor: not-allowed;" style="cursor: not-allowed;"><a ng-click="optNew(row.id,row.code)"><span class="nav-icon-1 add-plus1"></span></a></li>
                              </ul>
                              <ul class="lists-opt" ng-show="row.status =='ALTA'" >
                                 <li title="Gestionar Asistencia"><a ng-click="optAsis(row.id)"><span class="nav-icon-1  confirm-as"></span></a></li>
                                 <li title="Cancelar turno"><a ng-click="optCan(row.id,row.code)"><span class="nav-icon-1  cancel-1"></span></a></li>
                                 <li title="Reasignar turno"><a ng-click="optReasign(row.id,row.code)"><span class="nav-icon-1 reload"></span></a></li>
                                 <li title="Nuevo turno"><a ng-click="optNew(row.id,row.code)"><span class="nav-icon-1 add-plus1"></span></a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12" ng-show="filteredItems > 0">
                        <br>
                        <ul class="pagination" class="col-md-12">
                           <li  pagination="" page="currentPage" on-select-page="setPage(page)" total-items="filteredItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
                        </ul>
                        <p ng-show="filteredItems != 0" class="results-table" ><br>@{{totalItems}} turno(s) encontrado(s)</p>
                     </div>
                     <hr>


                  </div>

                 <div class="row">
                    
                     <div class="col-md-12">
                         <hr>
                           <p style="margin-bottom: 10px; margin-top: 10px">Exportá los turnos solicitados:</p>

                        </div>
                       <?php 
                     $week_start = date("Y-m-d", strtotime('last Monday', time()));
                     $week_end = date("Y-m-d", strtotime('next Sunday', time()));
                     $week_end = date("Y-m-d", strtotime($week_end."+ 1 days"));
                     ?>

                     <div class="col-md-4 col-sm-4 col-xs-12" style="clear: both;"> 
                        <input type='text' id='fecha_inicial' class="form-control" value="<?php echo date('d-m-Y',strtotime($week_start));?>" placeholder="Desde" />
                     </div>
                     <div class="col-md-4 col-sm-4 col-xs-12">
                        <input type='text' id='fecha_final' class="form-control"  value="<?php echo date('d-m-Y',strtotime($week_end));?>" placeholder="Hasta" />
                     </div>
                         <div class="col-md-4 col-sm-4 col-xs-12" >
                        <select class="form-control select-1" id="type_shift-1" name="type_shift">
                           <option value="ALL">Todos los turnos</option>
                           <option value="3">Próximos </option>
                           <option value="1">Atendidos</option>
                           <option value="0">Ausencia</option>
                           <option value="2">Asistencia parcial</option>
                           <option value="SOBRETURNO">Sobreturno</option>
                           <option value="ADMIN">Agendado por prestador</option>
                           <option value="USER">Agendado por cliente</option>
                        </select>
                     </div>
                        @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')

                        <div class="col-md-4 col-sm-4 col-xs-12" >
                        <br class="hidden-xs">
                        <select class="form-control select-1" id="branch-1" name="branch">
                           <option value="ALL">Todas las sucursales</option>
                           @foreach($branch as $rs)
                           <option value="<?php echo $rs->suc_id;?>"><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-4 col-sm-4 col-xs-12" >
                        <br class="hidden-xs">
                        <select class="form-control select-1" id="lenders-1" name="lenders" >
                           <option value="ALL">Todos los prestadores</option>
                           @foreach($lenders as $rs)
                           <option value="<?php echo $rs->tmsp_id;?>"><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                           @endforeach
                        </select>
                     </div>
                     
                     @else

                     <div class="col-md-4 col-sm-4 col-xs-12" >
                        <br class="hidden-xs">
                        @if(isset(Auth::guard('user')->User()->suc_id) && Auth::guard('user')->User()->suc_id!="" && Auth::guard('user')->User()->suc_id!="0" )
                        <select class="form-control select-1" id="branch-1" name="branch" disabled="disabled">
                           <option value="ALL">Todas las sucursales</option>
                           @foreach($branch as $rs)
                           <option value="<?php echo $rs->suc_id;?>" @if($rs->suc_id==Auth::guard('user')->User()->suc_id) selected="selected" @endif><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                           @endforeach
                        </select>
                        @else
                        <select class="form-control select-1" id="branch-1" name="branch" >
                           <option value="ALL">Todas las sucursales</option>
                           @foreach($branch as $rs)
                           <option value="<?php echo $rs->suc_id;?>"><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                           @endforeach
                        </select>
                        @endif
                     </div>
                     <div class="col-md-4 col-sm-4 col-xs-12" >
                        <br class="hidden-xs">
                        @if(isset(Auth::guard('user')->User()->pres_id) && Auth::guard('user')->User()->pres_id!="" && Auth::guard('user')->User()->pres_id!="0")
                        <select class="form-control select-1" id="lenders-1" name="lenders" disabled="disabled" >
                           <option value="ALL">Todos los prestadores</option>
                           @foreach($lenders as $rs)
                           <option value="<?php echo $rs->tmsp_id;?>"  @if($rs->tmsp_id==Auth::guard('user')->User()->pres_id) selected="selected" @endif><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                           @endforeach
                        </select>
                        @else
                        <select class="form-control select-1" id="lenders-1" name="lenders"  >
                           <option value="ALL">Todos los prestadores</option>
                           @foreach($lenders as $rs)
                           <option value="<?php echo $rs->tmsp_id;?>"><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                           @endforeach
                        </select>
                        @endif
                     </div>
                     
                     @endif
                        <div class="col-md-4 col-sm-4 col-xs-12">
                           <br class="hidden-xs">
                           <button  class="btn btn-success btn-block btn-excel" ng-click="buscar_fecha()" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargá  Excel</button>
                        </div>
                        <div class="col-xs-12">
                            <p class="form-desc" style="color: #ec0e08!important"><br>Se exportarán los primeros 2000 registros en el rango de fecha seleccionado.</p>
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
</div>
<input type="hidden" id="title" value="<?php echo $get_user->name ?>">
<input type="hidden" id="us_id" value="<?php echo $get_user->us_id;?>">
<input type="hidden" name="status" id="status" value="ALTA">
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/diary_user.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
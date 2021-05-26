<?php 
$get_country=DB::table('tu_pais')->where('pa_id', $get_business->em_pais)->first();
date_default_timezone_set($get_country->time_zone);
?>


@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="post">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?> > <?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Agenda</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
               <div class="panel-heading">
                 @{{name_date}}
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
                     <div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                        <select class="form-control select-1" disabled="disabled" id="branch-1" name="branch-1">
                           @foreach($branch as $rs)
                           <option value="<?php echo $rs->suc_id;?>" @if($rs->suc_id==$get_branch->suc_id) selected="selected" @endif>
                              <?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                        <select class="form-control select-1" disabled="disabled" id="lenders" name="lenders">
                           @foreach($lenders as $rs)
                           <option value="<?php echo $rs->tmsp_id;?>" @if($rs->tmsp_id==$get_lender->tmsp_id) selected="selected" @endif>
                              <?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?>
                                 
                              </option>
                           @endforeach
                        </select>
                     </div>
                     
                    <div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                        <div class="btn-group btn-group-1">
                           <button class="btn btn-info btn-diary" onclick="openDays()" id="day">Hoy</button>
                           <button class="btn btn-default btn-diary" onclick="openMonth()" id="month">Mes</button>
                        </div>
                     </div>
                    <div class="col-md-6 col-sm-6 col-xs-12" style="clear: both;">
                        <a ng-click="sortBy('timestamp')" ng-if="filteredItems!=0" title="Ordena el listado turnos por hora">Ordenar por Hora  <span   class="sortorder"  ng-class="{reverse: reverse}"></span></a>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 hidden-xs">
                        <div class="form-group">
                           <div class="input-group">
                              <span class="input-group-addon" style="border-top-left-radius: 3rem;
                                 border-bottom-left-radius: 3rem;"><i class="fa fa-search"></i></span>
                              <input type="text" ng-model="search" ng-change="filter()" placeholder="Ingresá el nombre o correo del cliente" class="form-control" style="border-top-right-radius: 3rem; border-bottom-right-radius: 3rem" />
                           </div>
                        </div>
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
                              <p>No hay turnos solicitados para la fecha</p>
                           </div>
                        </div>
                     </div>
                     <div class="bg-list" ng-show="filteredItems > 0" style="display: none; width: 100%">
                        <div class="row list-hives" ng-repeat="row in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                           <div class="col-xs-10 p04">
                              <span class="turr-3 hidden-xs" title="Ver todos los turnos del cliente" ng-click="goToShiftC(row.id_business,row.id_usuario)"><i class="fa fa-list" aria-hidden="true"></i></span>


                               <img src="<?php echo url('uploads/icons/zoom-1.png');?>" title="Video Conferencia" class="zoom-i hidden-xs" ng-show="row.id_meeting!=''" ng-click="goTourl(row.code)" style="cursor: pointer;">


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
                              <div class="caret-op" ng-show="row.tu_asist=='1'">Atendido</div>
                              <div class="caret-op1" ng-show="row.tu_asist=='0'">Ausencia</div>
                              <div class="caret-op2" ng-show="row.tu_asist=='2'">Asistencia parcial</div>
                              <div class="caret-op4" ng-show="row.tu_asist=='3'">Por atender</div>
                              <div class="caret-op4" ng-show="row.tu_asist=='4'">Expirado</div>
                              <div class="caret-op5" ng-show="row.tu_carga=='0'">Agendado: Pres.</div>
                              <div class="caret-op5" ng-show="row.tu_carga !='0'">Agendado: Clte.</div>

                              <div class="caret-op7 hidden-xs" ng-click="pdfCliente(row.code,row.id_business,row.us_id)"  title="Ver en PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Datos del cliente</div>
                              <div class="caret-op8 hidden-xs" ng-click="pdfTurno(row.code)"  title="Ver en PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Detalle del turno</div>
                           </div>
                           <div class="col-xs-2 bg-grey">
                              <ul class="lists-opt">
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
                     <div class="col-md-4 col-sm-4 col-xs-12">
                           <br class="hidden-xs">
                          <select class="form-control select-1" id="branch-1" name="branch" disabled="disabled">
                           <option value="ALL">Todas las sucursales</option>
                           @foreach($branch as $rs)
                           <option value="<?php echo $rs->suc_id;?>" @if($rs->suc_id==$get_branch->suc_id) selected="selected" @endif>
                              <?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                           @endforeach
                        </select>
                        </div>
                         <div class="col-md-4 col-sm-4 col-xs-12">
                          <br class="hidden-xs">
                          <select class="form-control select-1" id="lenders-1" name="lenders" disabled="disabled">
                           <option value="ALL">Todos los prestadores</option>
                           @foreach($lenders as $rs)
                           <option value="<?php echo $rs->tmsp_id;?>" @if($rs->tmsp_id==$get_lender->tmsp_id) selected="selected" @endif><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                           @endforeach
                        </select>
                        </div>
                         
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
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.lenders')
      </div>
   </div>
</div>
<input class="form-control-date datepicker" id="datepicker" style="opacity:0" onchange="setDate()">
<input type="hidden" name="now" id="now" value="<?php echo date('Y-m-d');?>">
<input type="hidden" name="date" id="date" value="<?php echo date('Y-m-d');?>">
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" id="select_branch" value="<?php echo $get_branch->suc_id;?>">
<input type="hidden" id="select_lender" value="<?php echo $get_lender->tmsp_id;?>">
<input type="hidden" id="title" value="<?php echo $get_lender->name ?>">
<input type="hidden" name="status" id="status" value="ALTA">
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/diary_lender.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_lenders.js?v='.time())?>
@stop
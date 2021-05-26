@extends('layouts.template_frontend_inside')
@section('content')
<?php echo Html::script('backend/js/jquery-ui.js')?>
<div class="container" ng-app="myApp" ng-controller="post">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2">MIS PRESTADORES</h2>
         <h4 class="title-date-2">Ingrese, actualice y elimine información de sus prestadores</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Listado de prestadores
            </div>
            <div class="panel-body">
 <div class="row">
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <select class="form-control select-1" id="type" name="type" onchange="angular.element(this).scope().rows()">
                     <option value="ALTA">ALTA</option>
                     <option value="BAJA">BAJA</option>
                  </select>
                  <br>
               </div>
               <div class="col-md-9 col-sm-9 col-xs-12">
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
                  <div class="table-responsive">
                     <table width="100%" class="table" id="sample-table-1" >
                        <thead>
                           <tr>
                              <th style="width: 10%">Foto</th>
                                    <th style="width: 20%">Nombre</th>
                                    <th style="width: 10%; text-align: center;">Cant Turnos</th>
                                    <th style="width: 40%"></th>
                           </tr>
                        </thead>
                           <tbody id="navsm">
                              <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" id="item_@{{row.id}}" >
                                 <td style="vertical-align: middle; text-align: left;">
                                    <img src="@{{row.image}}" class="img-pres-table" ng-click="goTo(row.id)">
                                 </td>
                                 <td style="vertical-align: middle;">
                                    <div style="text-transform: capitalize;">
                                       <div  style="cursor: pointer;" ng-click="goToConfig(row.id)" title="Configurar Mercado Pago">
                                         <img src="https://www.turnonet.com/frame/imagenes/mplogo.png" class="img-pay-1" ng-show="row.mp=='ALTA'">

                                         <img src="<?php echo url('img/video-player.png');?>" class="img-zoom-1 hidden-xs" ng-show="row.zoom==1" title="Configurar Videoconferencia"> 
                                      </div>
                                   @{{row.title}} @{{row.name}}</div>
                                   <div style="color: #FF5722; cursor: pointer;" ng-click="goToSpeciality(row.id)" title="Ver Especialidades"> @{{row.services}}</div>
                                </td>
                                <td style="vertical-align: middle; text-align: center; color: #FF5722; cursor: pointer;" title="Administrar Agenda" ng-click="goAgenda(row.id)">@{{row.shift}}</td>
                                
                                <td style="vertical-align: middle;">
                                 <span class="controls" ng-show="row.tmsp_estado=='ALTA'">
                                   <a class="edituser purple lighten-2" style="cursor:move" title="Ordenar posición en el Frame"><i class="fa fa-arrows" aria-hidden="true"></i> Mover</a>
                                   <a ng-click="goTo(row.id)" class="edituser blue" title="Editar prestador"><i class="ti-pencil-alt"></i> Editar</a>
                                   <a  ng-click="goAgenda(row.id)" class="deleteuser red" title="Administrar Agenda"><i class="ti-trash"></i> Agenda</a>

                                    <a  ng-click="goToConfig(row.id)" class="deleteuser blue" title="Administrar Configuración"><i class="fa fa-cog"></i></a>
                                       <a  ng-click="goToShedules(row.id)" class="deleteuser green" title="Administrar Horarios"><i class="fa fa-clock-o"></i></a>
                                </span>
                                <span class="controls" ng-show="row.tmsp_estado=='BAJA'">
                                 <a ng-click="optAlta(row.id)" class="deleteuser red"  title="Dar de alta"><i class="fa fa-check"></i> Dar de Alta</a>
                              </span>
                           </td>
                        </tr>
                           <tr ng-if="totalItems <= 0" >
                              <td colspan="5">
                                 <p class="text-italic"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No hay prestadores disponibles en este momento</p>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                     <ul class="pagination" class="col-md-12" ng-show="totalItems > 0">
                        <li  pagination="" page="currentPage" on-select-page="setPage(page)"  total-items="totalItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
                     </ul>
                     <p ng-show="totalItems != 0" class="results-table" ><br>@{{totalItems}} Registro(s) encontrado(s)</p>
                     <br>
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
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/lenders_lists.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_lenders.js?v='.time())?>
@stop
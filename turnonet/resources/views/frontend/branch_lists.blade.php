@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="post">
<div class="row">
   <div class="col-xs-12">
         <h2 class="title-section-2">MIS SUCURSALES</h2>
         <h4 class="title-date-2">Ingrese, actualice y elimine información de sus sucursales</h4>
      </div>
   <div class="col-md-12 col-sm-12 col-xs-12">
      <br>
   </div>
   <div class="col-md-8 col-sm-8 col-xs-12">

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
      <div class="mtop2 bg-preload">
         <div class="row">
            <div class="col-xs-12 content-spinner">
               <div class="spinner">
                  <div class="double-bounce1"></div>
                  <div class="double-bounce2"></div>
               </div>
            </div>
         </div>
      </div>
      <div class="bg-list"  style="display: none;">
         <div class="row" ng-if="filteredItems <= 0 || (list | filter:search).length === 0">
            <div class="col-md-12" style="text-align: center;">
               <img src="<?php echo url('/');?>/uploads/icons/noresult.png" alt="No hay resultados" class="img-not-res">
               <p>No hay sucursales disponibles en este momento</p>
            </div>
         </div>
      </div>
      <div class="bg-list" ng-show="filteredItems > 0" style="display: none;">
         <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12" ng-repeat="row in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
               <div class="card card-profile-1 mb-30 text-center" >
                  <!----><!---->
                  <div class="card-body">
                     <!----><!---->
                     <div class="avatar box-shadow-2 mb-3"><img src="@{{row.image}}" ng-click="goEdit(row.id)" title="Editar Sucursal"></div>
                     <h5 class="m-0">@{{row.name}}</h5>
                      <p>@{{row.address}}</p>

<div style="float: left;
    width: 100%;
    text-align: center;" >
                     <span class="controls controls-p" ng-show="row.suc_estado=='ALTA'">
                                 
                                    <a  ng-click="goToConfig(row.id)" class="deleteuser blue" title="Administrar Configuración"><i class="fa fa-cog"></i></a>
                                       <a  ng-click="goToShedules(row.id)" class="deleteuser green" title="Administrar Horarios"><i class="fa fa-clock-o"></i></a>
                                </span>

</div>
                     <button class="btn btn-primary btn-rounded" ng-show="row.suc_estado=='ALTA'" ng-click="gocreateLender(row.id)" title="Agrega un nuevo prestador a la sucursal" style="clear: both;"> Nuevo Prestador </button>
                     <button class="btn btn-primary btn-rounded" ng-show="row.suc_estado=='ALTA'" ng-click="goLenders(row.id)" title="Ver todos los prestadores de la sucursal"> Prestadores </button>

                      <button class="btn btn-primary btn-rounded" ng-show="row.suc_estado=='BAJA'" ng-click="optAlta(row.id)" title="Dar de alta"> Dar de Alta </button>
                  </div>
                  <div class="card-socials-simple card-socials-simple-1 mt-4">
                     <ul>
                        <li title="Total de turnos" ng-click="goAgenda(row.id)"><strong>Turnos</strong><br>@{{row.shift}}</li>
                        <li title="Total de prestadores" ng-click="goLenders(row.id)" ><strong>Prestadores</strong><br>@{{row.lenders}}</li>
                     </ul>
                  </div>
                  <!----><!---->
               </div>
            </div>
         </div>
      </div>
   </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.branch')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/branch.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_branch.js?v='.time())?>
@stop
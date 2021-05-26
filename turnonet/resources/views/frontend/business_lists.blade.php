@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="post">
<div class="row">
   <div class="col-xs-12">
         <h2 class="title-section-2">MIS EMPRESAS</h2>
         <h4 class="title-date-2">Ingrese, actualice y elimine información de sus empresas</h4>
      </div>
   <div class="col-md-12 col-sm-12 col-xs-12">
      <br>
   </div>
   <div class="col-md-8 col-sm-8 col-xs-12">
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
               <p>No hay empresas disponibles en este momento</p>
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
                     <div class="avatar box-shadow-2 mb-3" ng-click="goEdit(row.id)" title="Editar Empresa"><img src="@{{row.image}}"></div>
                     <h5 class="m-0">@{{row.name}}</h5>
                     <div style="float: left;
    width: 100%;
    text-align: center;" >
                     <span class="controls controls-p">
                                 
                                    <a  ng-click="goToConfig(row.id)" class="deleteuser blue" title="Administrar Configuración"><i class="fa fa-cog"></i></a>
                                       <a  ng-click="goToShedules(row.id)" class="deleteuser green" title="Administrar Horarios"><i class="fa fa-clock-o"></i></a>
                                </span>

</div>
                     <button class="btn btn-primary btn-rounded" ng-click="goAgenda(row.id)" title="Gestioná la agenda"> Agenda </button>
                     <button class="btn btn-primary btn-rounded" ng-click="goDirectorio(row.id)" title="Ver los clientes de la empresa"> Directorio </button>
                  </div>
                  <div class="card-socials-simple mt-4">
                     <ul>
                        <li title="Total de turnos" ng-click="goAgenda(row.id)"><strong>Turnos</strong><br>@{{row.shift}}</li>
                        <li title="Total de sucursales" ng-click="goBranch()"><strong>Sucursales</strong><br>@{{row.branch}}</li>
                        <li title="Total de prestadores" ng-click="goLenders()"><strong>Prestadores</strong><br>@{{row.lenders}}</li>
                     </ul>
                  </div>
                  <!----><!---->
               </div>
            </div>
         </div>
      </div>
   </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.business')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/business.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
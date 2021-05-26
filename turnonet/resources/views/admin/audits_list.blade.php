@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-user"></i> Auditoria</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Auditoria</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
    <div class="widgets-wrapper" ng-app="myApp" ng-controller="paginado">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <input type="hidden" name="id" value="" id="id">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="widget z-depth-1">
               <div class="widget-bar">
                  <div class="col s6">
                    @if (Auth::guard('admin')->User()->level=='1' || Auth::guard('admin')->User()->rol=='1')
                     <span class="controls" style="display: none;" id="op-excel">
                     <a href="{{url('excel/audits')}}"  ng-show="filteredItems > 0"  id="btn-excel" class="edituser blue lighten-2" target="_blank"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar excel </a>
                     </span>
                     @endif
                  </div>
                  <div class="col s12" style="clear: both; height: 8px">
                  </div>
                  <div class="col s3" style="clear: both;"> 
                           <input type='text' id='start' class="form-control" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);padding: 8px; width: 100%" value="01-<?php echo date('m');?>-<?php echo date("Y");?>" placeholder="Desde" />
                        </div>
                  <div class="col s3">
                           <input type='text' id='end' class="form-control" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);padding: 8px; width: 100%" value="<?php echo date('d-m-Y');?>" placeholder="Hasta" />
                        </div>
                <div class="col s3">
                     {!!Form::select('tech',$tech, null, ['placeholder' => 'Todos los usuarios','class'=>'browser-default','id'=>'tech','style'=>'background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);width: 100%'])!!}
                  </div>
                  <div class="col s3" style="text-align: right;">
                     <a  class="btn blue" ng-click="search_items()" style="height: 45px;line-height: 45px; border-radius: 0.3rem;background-color: #33b56a!important; width: 100%"> <i class="ti-search"></i> Generar Reporte </a>
                  </div>
                 <div class="col-md-12" style="clear: both;">
                  <br>
                  </div>
                  </div>
                   <div class="widget-content">
                 
                  <div class="projects-table col-md-12">
                     <br>
                   
                       <table width="100%" class="table">
                        <thead>
                           <tr>
                              <th style="width: 12.6%">
                                 <div class="text-center"> Usuario</div>
                              </th>
                              <th style="width: 12.8%">
                                 <div class="text-center">Correo electrónico</div>
                              </th>
                              <th style="width: 10%">
                                 <div class="text-center">IP</div>
                              </th>
                              <th style="width: 28%">
                                 <div class="text-center">Actividad</div>
                              </th>
                              <th style="width: 12.6%">
                                 <div class="text-center">Fecha/Hora</div>
                              </th>
                              
                           </tr>
                        </thead>
                        <tbody>
                         <tr id="loader">
                              <td colspan="6">
                                 <p class="text-italic text-center"><img src="{{url('/')}}/uploads/icons/loader1.gif" style="width: 40px"></p>
                              </td>
                           </tr>
                           <tr ng-repeat="row in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit"
                              >
                              <td class="text-center" style="vertical-align: middle;">
                                 <div>@{{row.name}}</div>
                              </td>
                              <td class="text-center" style="vertical-align: middle;">
                                 <div>@{{row.email}}</div>
                              </td>
                              <td style="vertical-align: middle; text-align: center;">
                                 @{{row.ip}}
                              </td>
                              <td style="vertical-align: middle; text-align: center;">
                                 @{{row.activity}}
                              </td>
                              <td style="vertical-align: middle; text-align: center;">
                                  @{{row.created_at | date : MM/dd/yyyy }} 
                              </td>
                             
                           </tr>
                           
                           <tr ng-show="filteredItems == 0" >
                              <td colspan="6">
                                  <p class="text-italic"><i class="ti-info-alt"></i> No hay resultados para mostrar</p>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                                                   

                  </div>
                  <div class="col s12" style="clear: both;">
                     <ul class="pagination" class="col-md-12" ng-show="filteredItems != 0" style="clear: both;">
                        <li  pagination="" page="currentPage" on-select-page="setPage(page)" total-items="filteredItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
                     </ul>
                      <p ng-show="filteredItems != 0" ><br>@{{totalItems}} Registro (s) encontrado (s)</p>
                  </div>
                 
                    
                
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>
<script type="text/javascript">
    $(function() {
        $('#start').datetimepicker({
            format: 'MM-DD-YYYY'
        });
        $('#end').datetimepicker({
            format: 'MM-DD-YYYY'
        });
        
    });
    var app = angular.module('myApp', ['ui.bootstrap']);
    app.filter('startFrom', function() {
        return function(input, start) {
            if (input) {
                start = +start;
                return input.slice(start);
            }
            return [];
        }
    });
    app.controller('paginado', function($scope, $http, $window) {
        $scope.entryLimit = 100;
        
        $scope.rows = function() {
          $http.get("{{url('audits_listing')}}").then(function successCallback(response)  {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $("#loader").hide();
          });
        }
        $scope.rows();
        $scope.setPage = function(pageNo) {
          $scope.currentPage = pageNo;
        };
        $scope.filter = function() {
          $scope.filteredItems = $scope.filtered.length;
          $scope.totalItems =  $scope.filtered.length

        };
        $scope.sort_by = function(predicate) {
          $scope.predicate = predicate;
          $scope.reverse = !$scope.reverse;
        };
        $scope.search_items = function() {
           $("#op-excel").show();
            var start = $("#start").val();
            var end = $("#end").val();
            var tech = $("#tech").val();
            if (start == "") {
                start = 0;
            }
            if (end == "") {
                end = 0;
            }
            if (tech == "") {
                tech = 0;
            }
            $("#btn-excel").attr("href", "{{url('/')}}/excel/audits/" + tech + "/" + start + '/' + end);
            $http.get("{{url('/')}}/audits_search_listing/" + tech + "/" + start + '/' + end).then(function successCallback(response)  {
                $scope.list = response.data;
                $scope.currentPage = 1;
                $scope.filteredItems = $scope.list.length;
                $scope.totalItems = $scope.list.length;
            });
        }
        
        //borrar página
     
    });
    
</script>
@stop
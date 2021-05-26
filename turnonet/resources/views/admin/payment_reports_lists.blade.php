@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-briefcase"></i> Pagos Marketplace</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Pagos Marketplace</li>
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
                     <div class="col s8">

                      <div class="row">
                           <div class="col-md-3 col-sm-3 col-xs-12" style="clear: both;"> 
                              <input type='text' id='fecha_inicial' class="form-control" value="01-<?php echo date('m');?>-<?php echo date("Y");?>" placeholder="Desde" />
                           </div>
                           <div class="col-md-3 col-sm-3 col-xs-12">
                              <input type='text' id='fecha_final' class="form-control"  value="<?php echo date('d-m-Y');?>" placeholder="Hasta" />
                           </div>

                           <div class="col-md-3 col-sm-3 col-xs-12">
                              <select id="business" class="browser-default">
                              	<option value="">Todas las empresas</option>
                              	@foreach($business as $rs)
                                 <option value="<?php echo $rs->em_id;?>"><?php echo mb_substr(mb_convert_encoding($rs->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100);?></option>
                              	@endforeach
                              </select>
                           </div>
                           <div class="col-md-3 col-sm-3 col-xs-12">
                              <a class="btn orange" ng-click="buscar_fecha()"  style="height: 47px;line-height: 50px;" ><i class="fa fa-search" aria-hidden="true"></i> Filtrar</a>
                           </div>
                        </div>
                        
                     </div>
                  
                  </div>
                  <div class="widget-content" style="    flex: auto;">
                     <div class="projects-table" style="width: 100%">



                         <table width="100%" class="table" id="sample-table-1" >
                              <thead>
                                 <tr>
                                    <th style="width: 20%;text-align: center;">Empresa</th>
                                    <th style="width: 10%;text-align: center;">ID de pago</th>
                                    <th style="width: 10%; text-align: center;">ID de turno</th>
                                    <th style="width: 15%;text-align: center">Monto</th>
                                    <th style="width: 15%;text-align: center">Comisión</th>
                                    <th style="width: 35%;text-align: center">Fecha</th>
                                 </tr>
                              </thead>
                              <tbody>
                                <tr id="loader">
                                 <td colspan="6">
                                    <p class="text-italic text-center"><img src="{{url('/')}}/uploads/icons/loader1.gif" style="width: 40px"></p>
                                 </td>
                              </tr>
                                 <tr class="results-table" ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                                    <td style="vertical-align: middle; text-transform: uppercase;">@{{row.business}}</td>
                                    <td style="vertical-align: middle; text-transform: capitalize;">@{{row.id_payment}}</td>
                                    <td style="vertical-align: middle;text-align: center;">@{{row.code}}</td>
                                    <td style="vertical-align: middle;text-align: center">$ @{{row.amount}}</td>
                                      <td style="vertical-align: middle;text-align: center">$ @{{row.commission}}</td>
                                    <td style="vertical-align: middle;text-align: center">@{{row.created_at}}</td>
                                 </tr>
                                 <tr class="results-table" ng-if="totalItems <= 0" >
                                    <td colspan="6">
                                       <p class="text-italic"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No tienes pagos registrados en este momento</p>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        
                     </div>

                     <ul class="pagination" class="col-md-12 results-table" ng-show="filteredItems > 0">
                        <li  pagination="" page="currentPage" on-select-page="setPage(page)"  total-items="filteredItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
                     </ul>
                     <p ng-show="filteredItems != 0" class="results-table" ><br>@{{totalItems}} Registro(s) encontrado(s)</p>
                     <br>
                     @if (Auth::guard('admin')->User()->level=='1' || Auth::guard('admin')->User()->rol=='1')
                     <div class="row results-table" ng-show="filteredItems != 0" style="border-top: 1px solid #ddd;">
                        <div class="input-field col s12">
                           <br>
                        </div>
                        <div class="col s3">
                           <a  class="btn orange" id="btn-excel" href="<?php echo url('excel/commissions');?>" target="_blank" style="height: 45px;line-height: 50px; border-radius: 0.3rem;background-color: #33b56a!important;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargá  Excel</a>
                        </div>
                     </div>
                     @endif
                     
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Content Area -->

<script type="text/javascript">
$(function() {
    $('#fecha_inicial').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $('#fecha_final').datetimepicker({
        format: 'DD-MM-YYYY'
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
    $scope.entryLimit = 200;
    $scope.propertyName = 'um';
    $scope.reverse = true;
    $scope.data = {
        availableOptions: [{
                id: '25',
                name: '25'
            },
            {
                id: '50',
                name: '50'
            },
            {
                id: '100',
                name: '100'
            },
            {
                id: '200',
                name: '200'
            },
            {
                id: '500',
                name: '500'
            }
        ],
        selectedOption: {
            id: '200',
            name: '200'
        }
    };
    $scope.showrow = function() {
        $scope.entryLimit = $("#mySelect").val();
    }
    $scope.sortBy = function(propertyName) {
        $scope.propertyName = propertyName;
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.rows();
    }
    $scope.rows = function() {
        $("#mask").hide();
        $(".results-table").hide();
        $("#loader").show();
        $http.get("{{url('/')}}/business_lists_payments").then(function successCallback(response) {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $(".results-table").show();
            $("#loader").hide();
        });
    }
    $scope.rows();
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        if ($("#search").val() == '') {
            $(".pagination").show();
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
        } else {
            $(".pagination").hide();
            $scope.filteredItems = $scope.filtered.length;
            $scope.totalItems = $scope.filtered.length;
        }
    };

     $scope.buscar_fecha= function(){
          if ($('#fecha_inicial').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_inicial').focus();
            return (false);
        }
        if ($('#fecha_final').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_final').focus();
            return (false);
        }else{
          var fecha_inicial =document.getElementById("fecha_inicial").value;
          var fecha_final =document.getElementById("fecha_final").value;
          var business =document.getElementById("business").value;

          if(business==""){
          	business=0;
          }

          $(".results-table").hide();
        $("#loader").show();
        $("#btn-excel").attr('href','<?php echo url('excel/commissions-date');?>/'+fecha_inicial+'/'+fecha_final+"/"+business);
        $http.get("{{url('/')}}/business_lists_payments_search/"+fecha_inicial+"/"+fecha_final+"/"+business).then(function successCallback(response) {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $(".results-table").show();
            $("#loader").hide();
        });
          



        }
      }
      
    
});
</script>



@stop
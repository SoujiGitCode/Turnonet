@extends('layouts.template')
@section('content')

<div class="content-area">
   <div class="breadcrumb-bar">
       <div class="page-title">
         <h1><i class="ti-layers-alt"></i> Bloques</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Bloques</li>
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
                  <div class="col s4">
                     <p>Bloques del sitio</p>
                  </div>
                  <div class="col s8">
                     <form class="search-admin">
                        <input type="text" ng-model="search" ng-change="filter()" placeholder="Buscar...." >
                        <button><i class="ti-search"></i></button>
                     </form>
                  </div>
                  </div>
                   <div class="widget-content"> 
                  <div class="projects-table">
                     <table width="100%" class="table">
                         <thead>
                           <tr>
                              <th style="width: 1%">
                                 <div >ID</div>
                              </th>
                              <th style="width: 57%">
                                 <div >Título</div>
                              </th>
                            
                              
                              <th style="width: 25%">
                                 <div class="text-center">Registro</div>
                              </th>
                              <th style="width: 20%"></th>
                           </tr>
                        </thead>
                      
                           <tbody>
                           <tr ng-repeat="row in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
                              <td style="vertical-align: middle;"> @{{$index+1}}</td>
                              <td style="vertical-align: middle;"> @{{row.title}} </td>
                              <td style="vertical-align: middle; text-align: center;">  @{{row.created_at | date : MM/dd/yyyy }} </td>
                              <td style="vertical-align: middle;">
                              <span class="controls">
                                
                                <a href="{{url('/')}}/section/@{{row.id}}/edit" class="edituser blue"><i class="ti-pencil-alt"></i> Editar</a>
                            
                              </span>
                            </div>
                              </td>
                           </tr>
                           <tr ng-show="filteredItems == 0" >
                              <td colspan="5">
                                  <p class="text-italic"><i class="ti-info-alt"></i> No hay resultados para mostrar</p>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <ul class="pagination" class="col-md-12" ng-show="filteredItems > 0">
                     <li  pagination="" page="currentPage" on-select-page="setPage(page)" total-items="filteredItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
                  </ul>
                   <p ng-show="filteredItems != 0" ><br>@{{totalItems}} Registro(s) encontrado(s)</p>
                   <br>
                  
               </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Content Area -->




<script type="text/javascript">
        $("#mask").show();
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
    //Listar Paginas 
    $scope.rows = function() {
        $http.get("{{url('sections_lists')}}").then(function successCallback(response)  {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.entryLimit = 100;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $("#mask").hide();
        });
    }
    $scope.rows();
    //cambiar numero de pagina
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    // filtrar paginas
    $scope.filter = function() {
        $scope.filteredItems = $scope.filtered.length;
        $scope.totalItems = $scope.filtered.length
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    //borrar página
    $scope.borrar = function(id) {
        swal({
                title: "Confirmá que querés eliminar este registro",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si eliminá',
                cancelButtonText: 'Cancelar',
                closeOnConfirm: true
            },
            function() {
                // Url 
                var route = "{{url('/')}}/section/" + id + "";
                var token = $("#token").val();
                //Enviar Datos
                $.ajax({
                    url: route,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    type: 'DELETE',
                    dataType: 'json',
                    success: function() {
                        $.growl.error({
                            title: "<i class='fa fa-exclamation-circle'></i> Atención",
                            message: "Registro eliminado"
                        });
                        $scope.rows();
                    },
                    error: function(msj) {
                        $.growl.error({
                            title: "<i class='fa fa-exclamation-circle'></i> Error",
                            message: 'Ha ocurrido un error por favor intente más tarde'
                        });
                    }
                });
            });
    }
});

 </script>
 @stop
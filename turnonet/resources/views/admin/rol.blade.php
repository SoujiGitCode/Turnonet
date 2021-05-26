@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-user"></i> Roles</h1>
      </div>
      <ul class="admin-breadcrumb">
        <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Roles</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper" ng-app="myApp" ng-controller="role">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <input type="hidden" name="id" value="" id="id">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s4">
                  <div class="widget z-depth-1">

                     <div class="widget-title">
                        <h3><i class="ti-pencil"></i>  @{{titulo}}</h3>
                     </div>
                     <div class="widget-content">
                     <div class="input-field">
                       <label>Nombre <small style="color: #fd2923">*</small></label>
                        {!!Form::text('name',null,['id'=>'name'])!!}
                        <span class="md-input-bar "></span>
                     </div>
                     <div class="input-field">
                           <label style="color: #fd2923!important">Campos requeridos (*)</label><br>
                        </div>
                     <div class="input-field">
                        <button class="btn waves-effect waves-light orange " ng-click="guardar()"><i class="fa fa-paper-plane" aria-hidden="true"></i>  @{{btn}}</button>
                     </div>
                     </div>
                  </div>
               </div>
               <div class="col s8">
                  <div class="widget z-depth-1">

                     <div class="widget-title">
                        <h3>Roles</h3>
                     </div>
                      <div class="widget-content">
                     <div class="projects-table">
                        <table width="100%" class="table">
                           <thead>
                              <tr>
                                 <th>
                                    <div class="text-center">#</div>
                                 </th>
                                 <th>
                                    <div class="text-center">Nombre</div>
                                 </th>
                                 <th>
                                    <div class="text-center">Permisos</div>
                                 </th>
                                 <th>
                                    <div class="text-center">Registro</div>
                                 </th>
                                 <th></th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr ng-repeat="row in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
                                 <td  class="text-center" style="vertical-align: middle;"> @{{$index+1}}</td>
                                 <td  class="text-center" style="vertical-align: middle;"> @{{row.name}} </td>
                                 <td  class="text-center" style="vertical-align: middle;"> <a  href="{{url('/')}}/permission/@{{row.id}}" title="Actualizá permisos" alt="Actualizá Permisos"><i class="ti-lock orange-text"></i></a></td>
                                 <td  class="text-center" style="vertical-align: middle;">  @{{row.created_at | date : MM/dd/yyyy }} </td>
                                 <td  class="text-center" style="vertical-align: middle;">
                                 <span class="controls">
                                    <a ng-click="asignarValor(row.id,row.name)"  title="Edit" class="edituser blue"><i class="ti-pencil-alt"></i> Editar </a>
                                  </span>
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
                  </div>
               </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Content Area -->
<script type="text/javascript">
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
app.controller('role', function($scope, $http, $window) {
    $scope.titulo = "Nuevo rol";
    $scope.btn = "Guardar";
    $scope.rows = function() {
        $http.get('{{url("rol_lists")}}').then(function successCallback(response)  {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.entryLimit = 20;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
        });
    }
    $scope.rows();
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        $scope.filteredItems = $scope.filtered.length;
        $scope.totalItems = $scope.filtered.length
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    $scope.guardar = function() {
        var id = $("#id").val();
        var name = $('#name').val();
        var token = $("#token").val();
        if (name == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá el nombre"
            });
            $('#name').focus();
            return (false);
        } else {
            if (id == "") {
                $.ajax({
                    url: "{{url('rol')}}",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        name: name
                    },
                    success: function(data) {
                        $("#name").val('');
                        $.growl.notice({
                            title: "<i class='fa fa-exclamation-circle'></i> Atención",
                            message: "Registro exitoso"
                        });
                        $scope.rows();
                    },
                    error: function(msj) {
                        $.growl.error({
                            title: "<i class='fa fa-exclamation-circle'></i> Atención",
                            message: "" + msj.responseJSON.name + ""
                        });
                    }
                });
            } else {
                $.ajax({
                    url: "{{url('/')}}/rol/" + id + "",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    type: 'PUT',
                    dataType: 'json',
                    data: {
                        name: name,
                        _method: 'PUT', 
                        _token: token
                    },
                    success: function(data) {
                        $("#name").val('');
                        $("#id").val('');
                        $.growl.warning({
                            title: "<i class='fa fa-exclamation-circle'></i> Atención",
                            message: "Datos Actualizados"
                        });
                        $scope.rows();
                        $scope.titulo = "Nuevo rol";
                        $scope.btn = "Guardar";
                    },
                    error: function(msj) {
                        $.growl.error({
                            title: "<i class='fa fa-exclamation-circle'></i> Atención",
                            message: "" + msj.responseJSON.name + ""
                        });
                    }
                });
            }
        }
    }
    $scope.asignarValor = function(id, name) {
        $("#id").val(id);
        $("#name").val(name);
        $scope.titulo = "Actualizá tu rol";
        $scope.btn = "Guardar";
    }
});
</script>


@stop
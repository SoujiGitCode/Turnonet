@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1>Permisos</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Permisos</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper" ng-app="myApp" ng-controller="permission">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <div class="row">
         <div class="masonary">
            <div class="col s6">
               <div class="widget z-depth-1">
                  <div class="widget-title">
                     <h3><i class="ti-lock orange-text"></i> Actualizá los permisos de tu rol {{$rol->name}} </h3>
                  </div>
                  <div class="widget-content">
                 
                     <ul class="exclude list">
                        <li class="uk-nestable-item" ng-repeat-start="modulo in list">
                           <div class="uk-nestable-panel">
                              <div class="col s8">
                                 <p style="font-weight: 300; font-size: 16px; margin-bottom: 0px"><i class="@{{modulo.class}}" style="
                                    color: #fff;
                                    display: inline-block;
                                    font-size: 14px;
                                    height: 32px;
                                    line-height: 32px;
                                    margin-right: 10px;
                                    text-align: center;
                                    width: 32px;
                                    border-radius: 50%;"></i>  @{{ modulo.nombre }}</p>
                              </div>
                              <div class="col s4">
                                 <span class="controls">
                                 <a class="edituser green" ng-click="asignarmodulo(modulo.id_modulo)" ng-if="modulo.status == 2" > <i class="ti-check-box"></i> Activar</a>
                                 <a class="edituser grey" ng-click="retirarmodulo(modulo.id_modulo)" ng-if="modulo.status == 1" >  <i class="ti-close"></i> Desactivar</a>
                                 </span>  
                              </div>
                           </div>
                        </li>
                        <ul class="exclude list">
                           <li class="uk-nestable-item"  ng-repeat="submodulo in modulo.submodulo">
                              <div class="uk-nestable-panel">
                                 <div class="col s8">
                                     <p style="font-weight: 300; font-size: 16px; margin-bottom: 0px"> @{{ submodulo.nombre }} </p>
                                 </div>
                                 <div class="col s4">
                                    <span class="controls">
                                    <a class="edituser green" ng-click="asignarsubmodulo(submodulo.id,modulo.status)" ng-if="submodulo.status == 2" > <i class="ti-check-box"></i> Activar</a>
                                    <a class="edituser grey"  ng-click="retirarsubmodulo(submodulo.id,modulo.status)" ng-if="submodulo.status == 1">  <i class="ti-close"></i> Desactivar</a>
                                    </span>  
                                 </div>
                              </div>
                           </li>
                        </ul>
                        <br>
                        </li>
                        <li ng-repeat-end></li>
                       
                       
                  
              
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   var app = angular.module('myApp', []);
app.controller('permission', function($scope, $http, $window) {
    $scope.rows = function() {
        $scope.list = [];
        $http.get("{{url('permission_lists/'.$rol->id)}}").then(function successCallback(response)  {
          console.log(response.data);
           $scope.list = response.data;
        });
    }
    $scope.rows();
    $scope.asignarmodulo = function(module) {
        var route = "{{url('assign')}}";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'POST',
            dataType: 'json',
            data: {
                'role': '{{$rol->id}}',
                'module': module,
                'type': '1'
            },
            success: function(data) {
                $.growl.notice({
                    title: "<i class='fa fa-exclamation-circle'></i> Atención",
                    message: "Módulo activado"
                });
                $scope.rows();
            },
            error: function(msj) {
                $.growl.error({
                    title: "<i class='fa fa-exclamation-circle'></i> Error",
                    message: 'Ha ocurrido un error por favor intentá más tarde'
                });
            }
        });
    }
    $scope.retirarmodulo = function(module) {
        var route = "{{url('remove')}}";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'POST',
            dataType: 'json',
            data: {
                'role': '{{$rol->id}}',
                'module': module,
                'type': '1'
            },
            success: function(data) {
                $.growl.error({
                    title: "<i class='fa fa-exclamation-circle'></i> Atención",
                    message: "Módulo desactivado"
                });
                $scope.rows();
            },
            error: function(msj) {
                $.growl.error({
                    title: "<i class='fa fa-exclamation-circle'></i> Error",
                    message: 'Ha ocurrido un error por favor intentá más tarde'
                });
            }
        });
    }
    $scope.asignarsubmodulo = function(id, modulo) {
        if (modulo != 2) {
            var route = "{{url('assign')}}";
            var token = $("#token").val();
            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    'type': '2'
                },
                success: function(data) {
                    $.growl.notice({
                        title: "<i class='fa fa-exclamation-circle'></i> Atención",
                        message: "Módulo activado"
                    });
                    $scope.rows();
                },
                error: function(msj) {
                    $.growl.error({
                        title: "<i class='fa fa-exclamation-circle'></i> Error",
                        message: 'Ha ocurrido un error por favor intentá más tarde'
                    });
                }
            });
        }
    }
    $scope.retirarsubmodulo = function(id, modulo) {
        if (modulo != 2) {
            var route = "{{url('remove')}}";
            var token = $("#token").val();
            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    'type': '2'
                },
                success: function(data) {
                    $.growl.error({
                        title: "<i class='fa fa-exclamation-circle'></i> Atención",
                        message: "Módulo desactivado"
                    });
                    $scope.rows();
                },
                error: function(msj) {
                    $.growl.error({
                        title: "<i class='fa fa-exclamation-circle'></i> Error",
                        message: 'Ha ocurrido un error por favor intentá más tarde'
                    });
                }
            });
        }
    }
});
</script>
@stop
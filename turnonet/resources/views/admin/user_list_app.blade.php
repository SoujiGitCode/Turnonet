@extends('layouts.template')
@section('content')

<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-user"></i> Usuarios</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Usuarios</li>
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
                      <div class="md-input-16"  style="padding-left: 0px">
                        <select class="browser-default" name="mySelect" id="mySelect" ng-options="option.name for option in data.availableOptions track by option.id"  ng-model="data.selectedOption" ng-change="showrow()" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);"> </select>
                      </div>
                    
                        <a class="btn orange" href="{{url('users-app/create')}}" style="height: 3.5vw;
    line-height: 3.8vw;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo Usuario </a>
                      </div>
                   
                     <div class="col s8">
                        <form class="search-admin">
                           <input type="text" ng-model="search" ng-change="filter()" id="search" placeholder="Buscar...." >
                           <button><i class="ti-search"></i></button>
                        </form>
                     </div>
                  </div>
                  <div class="widget-content">
                     <div class="projects-table">
                        <table width="100%" class="table">
                           <thead>
                              <tr>
                                <th style="width: 5%; cursor: pointer;" title="Ordenar" ng-click="sortBy('id')">ID <span   class="sortorder" ng-show="propertyName === 'id'" ng-class="{reverse: reverse}"></th>
                                 <th style="width: 15%; cursor: pointer;" title="Ordenar" ng-click="sortBy('name')">Nombre y apellido <span   class="sortorder" ng-show="propertyName === 'name'" ng-class="{reverse: reverse}"></th>
                                 <th style="width: 25%; cursor: pointer;" title="Ordenar" ng-click="sortBy('email')">Correo electrónico <span   class="sortorder" ng-show="propertyName === 'email'" ng-class="{reverse: reverse}"></th>
                             
                                 <th style="width: 10%; cursor: pointer;" title="Ordenar" ng-click="sortBy('status')">Estatus <span   class="sortorder" ng-show="propertyName === 'status'" ng-class="{reverse: reverse}"></th>
                                 <th style="width: 20%; cursor: pointer;" title="Ordenar" ng-click="sortBy('created_at')">Registro <span   class="sortorder" ng-show="propertyName === 'created_at'" ng-class="{reverse: reverse}"></span></th>
                                 <th style="width: 20%">Opciones</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr id="loader">
                                 <td colspan="7">
                                    <p class="text-italic text-center"><img src="{{url('/')}}/uploads/icons/loader1.gif" style="width: 40px"></p>
                                 </td>
                              </tr>
                              <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" class="results-table" style="background-color: @{{row.color}};    border-bottom: 1px dashed #a59e9e;" >
                                <td style="vertical-align: middle; text-transform: uppercase;">
                                <input type="hidden" id="id_@{{row.id}}" value="@{{row.id}}">
                                @{{row.id}}</td>
                                 <td style="vertical-align: middle; text-transform: uppercase;">@{{row.name}}</td>
                                 <td style="vertical-align: middle;">@{{row.email}}</td>
                              
                                 <td style="vertical-align: middle;"> @{{row.status}} </td>
                                 <td style="vertical-align: middle;">  @{{row.created_at }} </td>
                                 <td style="vertical-align: middle;">


                                  <select name="select_1" class="browser-default select-list" id="@{{row.id}}" onchange="selectOpt(this.id)" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12)">
                                   <option value="">Seleccioná</option>
                                   <optgroup label="GENERAL" >
                                     <option value="1">Actualizar datos</option>
                                   </optgroup>
                                   <optgroup label="CUENTA" ng-show="row.status=='ALTA'">
                                     <option value="2" ng-show="row.status=='ALTA'">Abrir cuenta</option>
                                     <option value="6" ng-show="row.status=='ALTA'">Ver Actividad</option>
                                   </optgroup>
                                   <optgroup label="ESTATUS">
                                     <option value="3" ng-show="row.status=='ALTA'">Dar de Baja</option>
                                     <option value="4" ng-show="row.status !='ALTA'">Dar de Alta</option>
                                     <option value="5">Eliminar Usuario</option>
                                   </optgroup>
                                    </select>

                                   
                                 </td>
                              </tr>
                              <tr ng-show="filteredItems == 0">
                                 <td colspan="7">
                                    <p class="text-italic"><i class="ti-info-alt"></i> No hay resultados para mostrar</p>
                                 </td>
                              </tr>
                           </tbody>
                     </table>
                  </div>
                  <ul class="pagination" class="col-md-12 results-table" ng-show="filteredItems > 0"  >
                     <li  pagination="" page="currentPage" on-select-page="setPage(page)"  total-items="filteredItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
                  </ul>
                   <p ng-show="filteredItems != 0" class="results-table"   style="display: none;" ><br>@{{totalItems}} Registro(s) encontrado(s)</p>
                  <br>
                  @if (Auth::guard('admin')->User()->level=='1' || Auth::guard('admin')->User()->rol=='1')
                  <div class="row results-table" ng-show="filteredItems != 0" style="border-top: 1px solid #ddd;display: none;">
                        <div class="col s12">
                           <p style="margin-bottom: 10px; margin-top: 10px">Seleccioná un rango de fechas y exportá tus registros:</p>

                        </div>
                        <div class="col s3" style="clear: both;"> 
                           <input type='text' id='fecha_inicial' class="form-control" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);padding: 8px;" value="01-<?php echo date('m');?>-<?php echo date("Y");?>" placeholder="Desde" />
                        </div>
                        <div class="col s3">
                           <input type='text' id='fecha_final' class="form-control" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);padding: 8px;" value="<?php echo date('d-m-Y');?>" placeholder="Hasta" />
                        </div>
                        <div class="col s3">
                           <a  class="btn orange" ng-click="buscar_fecha()"  style="height: 45px;line-height: 50px; border-radius: 0.3rem;background-color: #33b56a!important;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargá  Excel</a>
                        </div>
                        <div class="input-field col s12">
                           <label style="color: #ec0e08!important">Se exportarán los primeros 5000 registros en el rango de fecha seleccionado.</label><br>
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
    $scope.propertyName = 'created_at';
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
        id: '500',
        name: '500'
      }
    };
    $scope.showrow = function() {
      $scope.entryLimit=$("#mySelect").val();
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

         $http.get("{{url('user_app_lists')}}").then(function successCallback(response)  {
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
      $scope.sort_by = function(predicate) {
         $scope.predicate = predicate;
         $scope.reverse = !$scope.reverse;
      };
      
      $scope.bajar = function(id) {
         swal({
            title: "Confirmá que querés dar de baja a este usuario",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-status-user')}}";
            var token = $("#token").val();
            $.ajax({
               url: route,
               headers: {
                  'X-CSRF-TOKEN': token
               },
               type: 'POST',
               dataType: 'json',
               data: {
                 status: '2',
                 id: id
              },
               success: function() {
                  $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus del usuario ha sido actualizado"});
                  $scope.rows();
                  $('select').val('');
               },
                error: function(msj) {
                    $.growl.error({
                        title: "<i class='fa fa-exclamation-circle'></i> Error",
                        message: 'Ha ocurrido un error por favor intentá más tarde'
                    });
                }
            });
         });
      }
      $scope.activar = function(id) {
         swal({
            title: "Confirmá que querés dar de alta a este usuario",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-status-user')}}";
            var token = $("#token").val();
            $.ajax({
               url: route,
               headers: {
                  'X-CSRF-TOKEN': token
               },
               type: 'POST',
               dataType: 'json',
               data: {
                 status: '1',
                 id: id
              },
               success: function() {
                  $.growl.warning({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus del usuario ha sido actualizado"});
                  $('select').val('');
                  $scope.rows();
               },
                error: function(msj) {
                    $.growl.error({
                        title: "<i class='fa fa-exclamation-circle'></i> Error",
                        message: 'Ha ocurrido un error por favor intentá más tarde'
                    });
                }
            });
         });
      }
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
          var url="<?php echo url('/');?>/excel/users/"+fecha_inicial+"/"+fecha_final;
          window.open(url);
        }
      }
   });
function selectOpt(id) {
    switch ($("#" + id).val()) {
        case '1':
           window.open("<?php echo url('/');?>/users-app/" + $("#id_" + id).val()+'/edit');
            break;
        case '2':
            window.open("<?php echo url('/');?>/users-app/" + $("#id_" + id).val()+'/open');
            break;
           
        case '3':

         angular.element($('[ng-controller="paginado"]')).scope().bajar($("#id_" + id).val());
          break;
            
        case '4':
        angular.element($('[ng-controller="paginado"]')).scope().activar($("#id_" + id).val());
            
        case '5':
        angular.element($('[ng-controller="paginado"]')).scope().borrar($("#id_" + id).val());
           
        break;

        case '6':
            window.open("<?php echo url('/');?>/users-app/" + $("#id_" + id).val()+'/activities');
            break;
       
    }
}
</script>



@stop
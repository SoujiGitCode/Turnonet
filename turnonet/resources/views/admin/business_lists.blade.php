@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-briefcase"></i> Empresas</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Empresas</li>
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
                        <div class="md-input-16"  style="padding-left: 0px">
                           <select class="browser-default" name="mySelect" id="mySelect" ng-options="option.name for option in data.availableOptions track by option.id"  ng-model="data.selectedOption" ng-change="showrow()" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);"> </select>
                        </div>
                        <div class="md-input-16">
                           {!!Form::select('status', ['ALTA' => 'ALTA','BAJA'=>'BAJA'],'ALTA',['class' => 'browser-default','style'=>'background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12)','id'=>'status','onchange'=>'angular.element(this).scope().rows()'])!!}
                        </div>
                     </div>
                     <div class="col s4">
                        <form class="search-admin">
                           <input type="text" ng-model="search" ng-change="filter()" id="search" placeholder="Buscar...." >
                           <button><i class="ti-search"></i></button>
                        </form>
                     </div>
                  </div>
                  <div class="widget-content" style="    flex: auto;">
                     <div class="projects-table" style="width: 100%">
                        <table width="100%" class="table">
                           <thead>
                              <tr>
                                 <th style="width: 5%;cursor: pointer;text-align: center;" title="Ordernar" ng-click="sortBy('id')">ID <span   class="sortorder" ng-show="propertyName === 'id'" ng-class="{reverse: reverse}"></span></th>
                                 <th style="width: 30%;cursor: pointer" title="Ordernar" ng-click="sortBy('name')">Empresa/Correo  <span   class="sortorder" ng-show="propertyName === 'name'" ng-class="{reverse: reverse}"></span></th>
                                 <th style="width: 20%;cursor: pointer" title="Ordernar" ng-click="sortBy('name')">Usuario/Correo  <span   class="sortorder" ng-show="propertyName === 'username'" ng-class="{reverse: reverse}"></span></th>
                                 <th style="width: 20%; cursor: pointer;"  ng-click="sortBy('created_at')" title="Ordernar">Registro <span   class="sortorder" ng-show="propertyName === 'created_at'" ng-class="{reverse: reverse}"></span></th>
                                 <th style="width: 25%;">Opciones</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr id="loader">
                                 <td colspan="7">
                                    <p class="text-italic text-center"><img src="{{url('/')}}/uploads/icons/loader1.gif" style="width: 40px"></p>
                                 </td>
                              </tr>
                              <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" class="results-table" style="background-color: @{{row.color}};    border-bottom: 1px dashed #a59e9e;">
                                 <td style="vertical-align: middle;">@{{row.id}}
                                    <input type="hidden" id="id_@{{row.id}}" value="@{{row.id}}">
                                    <input type="hidden" id="frame_@{{row.id}}" value="@{{row.frame}}">
                                    <input type="hidden" id="id_user_@{{row.id}}" value="@{{row.id_user}}">
                                 </td>
                                 <td style="vertical-align: middle;">
                                    <div style="text-transform: uppercase;">@{{row.name}}</div>
                                    <div>@{{row.email}}</div>
                                 </td>
                                 <td style="vertical-align: middle;">
                                    <div>@{{row.username}}</div>
                                    <div>@{{row.useremail}}</div>
                                    <div>@{{row.password}}</div>
                                 </td>
                                 <td style="vertical-align: middle;">  @{{row.created_at }} </td>
                                 <td style="vertical-align: middle;">
                                    <select name="select_1" class="browser-default select-list" id="@{{row.id}}" onchange="selectOpt(this.id)" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12)">
                                       <option value="">Seleccioná</option>
                                       <optgroup label="USUARIOS" >
                                          <option value="4" ng-show="row.status_user=='ALTA'">Bajar Usuario</option>
                                          <option value="5" ng-show="row.status_user !='ALTA'">Activar Usuario</option>
                                          <option value="16" ng-show="row.status_user=='ALTA'">Abrir cuenta</option>
                                          <option value="26">Actualizar datos</option>
                                          <option value="8">Eliminar Usuario</option>
                                       </optgroup>
                                       <optgroup label="EMPRESA" >
                                          <option value="6" ng-show="row.status_business !='ALTA'">Activar Empresa</option>
                                          <option value="7" ng-show="row.status_business=='ALTA'">Bajar Empresa</option>
                                          <option value="18" ng-show="row.frame!=''">Ver Frame Turnos</option>
                                       </optgroup>

                                       <optgroup label="VIDEOCONFERENCIA" ng-show="row.status_business=='ALTA'"  >
                                          <option value="28" ng-show="row.status_business=='ALTA' && row.zoom =='NO'">Activar Servicio</option>
                                          <option value="27" ng-show="row.status_business=='ALTA' && row.zoom=='SI'">Bajar Servicio</option>
                                       </optgroup>

                                       <optgroup label="PAGOS" ng-show="row.status_business=='ALTA'" >
                                          <option value="1"  ng-show="row.status_business=='ALTA'">Actualizar comisión MP</option>
                                          <option value="10"  ng-show="row.status_business=='ALTA' && row.pay=='SI'">No paga más</option>
                                          <option value="11"  ng-show="row.status_business=='ALTA' && row.pay=='NO'">Empieza a pagar</option>
                                       </optgroup>
                                       <optgroup label="SMS" ng-show="row.status_business=='ALTA'">
                                          <option value="14" ng-show="row.status_smscontrol=='BAJA'">Activar SMS Empresa</option>
                                          <option value="15" ng-show="row.status_smscontrol=='ALTA'">Bajar SMS Empresa</option>
                                       </optgroup>
                                       <optgroup label="REPORTES">
                                          <option value="19" >Generar Reportes</option>
                                       </optgroup>
                                    </select>
                                    
                                 </td>
                              </tr>
                              <tr ng-show="filteredItems == 0" class="results-table">
                                 <td colspan="11">
                                    <p class="text-italic"><i class="ti-info-alt"></i> No hay resultados para mostrar</p>
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
        id: '200',
        name: '200'
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

         $http.get("{{url('/')}}/business_lists/"+$("#status").val()).then(function successCallback(response)  {
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
     
      
      $scope.borrar = function(id) {
         swal({
            title: "Confirmá que querés eliminar esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si eliminá',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('/')}}/business/" + id + "";
            var token = $("#token").val();
            $.ajax({
               url: route,
               headers: {
                  'X-CSRF-TOKEN': token
               },
               type: 'DELETE',
               dataType: 'json',
               success: function() {
                  $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "Registro eliminado"});
                 location.reload();
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
      $scope.bajar = function(id,user) {
         swal({
            title: "Confirmá que querés dar de baja a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-status-business')}}";
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
                 id: id,
                 user: user
              },
               success: function() {
                  $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus de la empresa ha sido actualizado"});
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
      $scope.activar = function(id,user) {
         swal({
            title: "Confirmá que querés dar de alta a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-status-business')}}";
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
                 id: id,
                 user: user
              },
               success: function() {
                  $.growl.warning({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus de la empresa ha sido actualizado"});
                 location.reload();
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
      $scope.bajar_pago = function(id) {
         swal({
            title: "Confirmá que querés actualizar el estatus de pago a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-pay-business')}}";
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
                  $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus de pago de la empresa ha sido actualizado"});
                  location.reload();
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
      $scope.activar_pago = function(id) {
         swal({
            title: "Confirmá que querés actualizar el estatus de pago a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-pay-business')}}";
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
                  $.growl.warning({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus de pago de la empresa ha sido actualizado"});
                 location.reload();
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
      $scope.bajar_sms = function(id) {
         swal({
            title: "Confirmá que querés actualizar el estatus de sms a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-sms-business')}}";
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
                  $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus de sms de la empresa ha sido actualizado"});
                  location.reload();
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
      $scope.activar_sms = function(id) {
         swal({
            title: "Confirmá que querés actualizar el estatus de sms a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-sms-business')}}";
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
                  $.growl.warning({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus de sms de la empresa ha sido actualizado"});
                 location.reload();
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
      $scope.bajar_sms_res = function(id) {
         swal({
            title: "Confirmá que querés actualizar el estatus de respuesta con sms a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-sms-res-business')}}";
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
                  $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus de respuesta con sms de la empresa ha sido actualizado"});
                  location.reload();
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
      $scope.activar_sms_res = function(id) {
         swal({
            title: "Confirmá que querés actualizar el estatus de respuesta con sms a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-sms-res-business')}}";
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
                  $.growl.warning({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus de respuesta con sms de la empresa ha sido actualizado"});
                  location.reload();
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
      $scope.borrar_us = function(id) {
         swal({
            title: "Confirmá que querés eliminar el usuario",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si eliminá',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('/')}}/users-app/" + id + "";
            var token = $("#token").val();
            $.ajax({
               url: route,
               headers: {
                  'X-CSRF-TOKEN': token
               },
               type: 'DELETE',
               dataType: 'json',
               success: function() {
                  $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "Registro eliminado"});
                  location.reload();
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
      $scope.bajar_us = function(id) {
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
                  location.reload();
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
      $scope.activar_us = function(id) {
         swal({
            title: "Confirmá que querés dar de alta a este usuario?",
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
                  location.reload();
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
      $scope.search_rows = function() {
        var  pay= $("#pay").val();
        var status = $("#status").val();
        if (status != "" || pay!="") {
          if(status==""){ status=0; } if(pay==""){ pay=0; }

          $http.get("{{url('/')}}/business_lists_search/" + pay+"/" + status).then(function successCallback(response)  {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
          });
        } else {
          $scope.rows();
        }
      }


      $scope.bajar_video = function(id) {
         swal({
            title: "Confirmá que querés desactivar el servicio de videoconferencia a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-video-business')}}";
            var token = $("#token").val();
            $.ajax({
               url: route,
               headers: {
                  'X-CSRF-TOKEN': token
               },
               type: 'POST',
               dataType: 'json',
               data: {
                 status: '0',
                 id: id
              },
               success: function() {
                  $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus del servicio ha sido actualizado"});
                  location.reload();
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
      $scope.activar_video = function(id) {
         swal({
            title: "Confirmá que querés activar el servicio de videoconferencia a esta empresa",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#CE0505',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            closeOnConfirm: true,
         },
         function() {
            var route = "{{url('up-video-business')}}";
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
                  $.growl.warning({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "El estatus del servicio ha sido actualizado"});
                  location.reload();
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
     
   });
function selectOpt(id) {
    switch ($("#" + id).val()) {
        case '1':
            backToUrl("<?php echo url('/');?>/business/" + $("#id_" + id).val() + "/edit");
            break;
        case '4':
            angular.element($('[ng-controller="paginado"]')).scope().bajar_us($("#id_user_" + id).val());
            break;
        case '5':
            angular.element($('[ng-controller="paginado"]')).scope().activar_us($("#id_user_" + id).val());
            break;
        case '6':
            angular.element($('[ng-controller="paginado"]')).scope().activar($("#id_" + id).val(), $("#id_user_" + id).val());
            break;
        case '7':
            angular.element($('[ng-controller="paginado"]')).scope().bajar($("#id_" + id).val(), $("#id_user_" + id).val());
            break;
        case '8':
            angular.element($('[ng-controller="paginado"]')).scope().borrar_us($("#id_user_" + id).val());
            break;
        case '9':
            angular.element($('[ng-controller="paginado"]')).scope().borrar($("#id_" + id).val());
            break;
        case '10':
            angular.element($('[ng-controller="paginado"]')).scope().bajar_pago($("#id_" + id).val());
            break;
        case '11':
            angular.element($('[ng-controller="paginado"]')).scope().activar_pago($("#id_" + id).val());
            break;
        case '12':
            angular.element($('[ng-controller="paginado"]')).scope().activar_sms($("#id_" + id).val());
            break;
        case '13':
            angular.element($('[ng-controller="paginado"]')).scope().bajar_sms($("#id_" + id).val());
            break;
        case '14':
            angular.element($('[ng-controller="paginado"]')).scope().activar_sms_res($("#id_" + id).val());
            break;
        case '15':
            angular.element($('[ng-controller="paginado"]')).scope().bajar_sms_res($("#id_" + id).val());
            break;
        case '16':
            backToUrl("<?php echo url('/');?>/users-app/" + $("#id_user_" + id).val() + "/open");
            break;
        case '17':
            backToUrl("<?php echo url('/');?>/business_lists_users/" + $("#id_" + id).val());
            break;
        case '18':
            backToUrl($("#frame_" + id).val());
            break;
        case '19':
            backToUrl("<?php echo url('/');?>/reports-account/" + $("#id_" + id).val());
            break;
        case '26':
            backToUrl("<?php echo url('/');?>/users-app/" + $("#id_user_" + id).val() + "/edit");
            break;
         case '27':
            angular.element($('[ng-controller="paginado"]')).scope().bajar_video($("#id_" + id).val());
            break;
        case '28':
            angular.element($('[ng-controller="paginado"]')).scope().activar_video($("#id_" + id).val());
            break;
    }
}

function backToUrl(url){

  console.log(url);

var link = document.createElementNS("http://www.w3.org/1999/xhtml", "a");
    link.href = url;
    link.target = '_blank';
    var event = new MouseEvent('click', {
        'view': window,
        'bubbles': false,
        'cancelable': true
    });
    link.dispatchEvent(event);
}
</script>



@stop
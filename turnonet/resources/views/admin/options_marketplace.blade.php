@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-settings"></i> Marketplace Mercado Pago</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Maretplace</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper" ng-app="myApp"  ng-controller="opciones">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s12">
                  <div class="col s12">
                     <div class="widget z-depth-1">
                        <div class="widget-title">
                           <h3><i class="ti-pencil"></i> Actualizá aquí la configuración de tu marketplace de mercado pago</h3>
                        </div>
                        <div class="widget-content">
                           <?php $site = DB::table('tu_settingsmp')->where('id', '1')->first();  ?> 
                           <div class="input-field col s12">
                              <label class="active">App ID <small style="color: #fd2923">*</small></label>
                              <input  type="text" id="client_id" value="{{$site->client_id}}" >
                           </div>
                           <div class="input-field col s12">
                              <label class="active">ACCESS_TOKEN <small style="color: #fd2923">*</small></label>
                              <input  type="text" id="client_secret" value="{{$site->client_secret}}" >
                           </div>
                           <div class="input-field col s12">
                              <label style="color: #fd2923!important">Campos requeridos (*)</label><br>
                           </div>
                           <div class="input-field col s12">
                              <a class="btn orange " ng-click="updatesite()"><i class="fa fa-paper-plane" aria-hidden="true"></i> Actualizar </a>
                           </div>
                        </div>
                     </div>
                  
               </div>
             </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>
<script type="text/javascript">
   var app = angular.module('myApp', []);
   app.controller('opciones', function($scope, $http, $window) {
    //Update Logo
    $scope.updatesite = function() {
        //Validar que ingrese un image
        if ($("#client_id").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá el app id"
            });
            $("#client_id").focus();
            return (false);
        }
        else if ($("#client_secret").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá el client secret"
            });
            $("#client_secret").focus();
            return (false);
        } else {
            //obtener Datos
            var route = "{{url('update_options')}}";
            //enviar Datos
            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': $("#token").val()
                },
                type: 'POST',
                dataType: 'json',
                data: {
                    client_id: $("#client_id").val(),
                    client_secret: $("#client_secret").val(),
                    type:'marketplace',
                },
                success: function(data) {
                    $.growl.warning({
                        title: "<i class='fa fa-exclamation-circle'></i> Atención",
                        message: "Datos Actualizados"
                    });
                    location.reload();
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
    //Update Logo
    $scope.updatelogo2 = function() {
        //Validar que ingrese un image
        if ($("#image-2").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá una imagen"
            });
            $("#image-2").focus();
            return (false);
        } else {
            //obtener Datos
            var route = "{{url('update_options')}}";
            var image = $("#image-2").val();
            image = image.replace(',', "");
            var token = $("#token").val();
            //enviar Datos
            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: 'POST',
                dataType: 'json',
                data: {
                    image: image,
                    type: 'image-2'
                },
                success: function(data) {
                    $.growl.warning({
                        title: "<i class='fa fa-exclamation-circle'></i> Atención",
                        message: "Datos Actualizados"
                    });
                    location.reload();
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
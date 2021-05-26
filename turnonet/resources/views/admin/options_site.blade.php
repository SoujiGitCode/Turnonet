@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-settings"></i> Nombre/Logos</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Nombre/Logos</li>
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
                           <h3><i class="ti-pencil"></i> Actualizá aquí el nombre del sitio</h3>
                        </div>
                        <div class="widget-content">
                           <?php $site = DB::table('site')->where('id', '1')->first();  ?> 
                           <div class="input-field col s12">
                              <label class="active">Nombre del sitio <small style="color: #fd2923">*</small></label>
                              <input  type="text" id="name" value="{{$site->name}}" >
                           </div>
                           <div class="input-field col s12">
                              <label style="color: #fd2923!important">Campos requeridos (*)</label><br>
                           </div>
                           <div class="input-field col s12">
                              <a class="btn orange " ng-click="updatesite()"><i class="fa fa-paper-plane" aria-hidden="true"></i> Guardar </a>
                           </div>
                        </div>
                     </div>
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-image"></i> Actualizá aquí la imagen del sitio</h3>
                     </div>
                     <div class="widget-content">
                        <div class="col s8">
                           <input name="image" id="image-1" value="" type="hidden">
                           <div id="dropzone-1" class="dropzone" style="min-height: 150px"></div>
                        </div>
                        <div class="col s4">
                           <img src="<?php echo url('/');?>/uploads/{{$site->image_1}}" alt="Photo" style='max-width: 100%;auto;display: block;padding: 3px'>
                        </div>
                        <div class="input-field col s12">
                           <a class="btn orange " ng-click="updatelogo1()"><i class="fa fa-paper-plane" aria-hidden="true"></i> Guardar</a>
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
    $scope.updatelogo1 = function() {
        //Validar que ingrese un image
        if ($("#image-1").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá una imagen"
            });
            $("#image-1").focus();
            return (false);
        } else {
            //obtener Datos
            var route = "{{url('update_options')}}";
            var image = $("#image-1").val();
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
                    type: 'image-1'
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

    //Update Logo
    $scope.updatelogo3 = function() {
        //Validar que ingrese un image
        if ($("#image-3").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá una imagen"
            });
            $("#image-3").focus();
            return (false);
        } else {
            //obtener Datos
            var route = "{{url('update_options')}}";
            var image = $("#image-3").val();
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
                    type: 'image-3'
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
    
    $scope.updatelogo = function() {
        //Validar que ingrese un image
        if ($("#image").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá una imagen"
            });
            $("#image").focus();
            return (false);
        } else {
            //obtener Datos
            var route = "{{url('update_options')}}";
            var image = $("#image").val();
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
                    type: 'image'
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
   
    //Update Nombre del sitio
    $scope.updatesite = function() {
        //validar que el nombre este lleno
        if ($('#name').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá el nombre del sitio"
            });
            $('#name').focus();
            return (false);
        } else {
            //Obtener Datos
            var name = $('#name').val();
            var name_es = $('#name_es').val();
            var route = "{{url('update_options')}}";
            var token = $("#token").val();
            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: 'POST',
                dataType: 'json',
                data: {
                    name: name,
                    type: 'name'
                },
                success: function(data) {
                    $.growl.warning({
                        title: "<i class='fa fa-exclamation-circle'></i> Atención",
                        message: "Datos Actualizados"
                    });
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
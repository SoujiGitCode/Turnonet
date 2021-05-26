@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-settings"></i> Clientes</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Dashboard"><i class="ti-home"></i> Dashboard</a></li>
         <li>Clientes</li>
      </ul>
   </div>
<!-- Breadcrumb Bar -->
<div class="widgets-wrapper" ng-app="myApp"  ng-controller="clients">
   <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
   <div class="row">
      <div class="masonary">
         <div class="col s12">
            <div class="col s9">
               <div class="widget z-depth-1">
              
                  <div class="widget-title">
                     <h3><i class="ti-pencil"></i> Ingresa tus imágenes aquí (600px x 300px)</h3>
                  </div>
 <div class="widget-content">
                  <div class="input-field col s12">
                     {!!Form::label('Etiqueta')!!}
                     {!!Form::text('name',null,['class' => 'md-input','id'=>'name'])!!}
                  </div>

                  <div class="input-field col s12">
                     <input name="image" id="image" value="" type="hidden">
                     <div id="dropzone" class="dropzone"></div>
                  </div>
                  </div>

               </div>
            </div>
            <div class="col s3">
               <div class="widget z-depth-1">
              
                  <div class="widget-title">
                     <h3><i class="ti-check"></i> Guardar Imágenes</h3>
                  </div>
                   <div class="widget-content">
                  <p class="margin-bottom-30">Haga clic aquí para guardar su imagen.</p>
                  <button type="button" class="btn waves-effect waves-light orange " ng-click="registrar()" style="width: 100%"><i class="fa fa-paper-plane" aria-hidden="true"></i> Guardar </button> 
                  </div>
               </div>
            </div>
             <div class="col s12">
             <div class="widget z-depth-1">
          
              <div class="widget-title">
               <h3><i class="ti-image"></i> Galería de imágenes </h3>
             </div>
              <div class="widget-content">
             <div id="nvar">
              <div class="col s3" ng-repeat="photo in data"  id="item_@{{photo.id}}">
               <br>
               <img src="{{url('/')}}/uploads/@{{photo.image}}" alt="Photo" style='width: 80%;display: block;padding: 3px;border: 0.5px solid #ddd; cursor: move;'>
               <div class="col s12"><br></div>
               <span class="controls">
                 <a class="edituser purple lighten-2" style="cursor:move" ><i class="ti-move"></i></a>
                 <a  ng-click="borrar(photo.id);" class="deleteuser red"><i class="ti-trash"></i></a>
                 <a  href="{{url('/')}}/clients/@{{photo.id}}/edit" class="edituser blue"><i class="ti-pencil"></i></a>
               </span>
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
<!-- Content Area -->
<script type="text/javascript">
  $("#mask").show();
var app = angular.module('myApp', []);
app.controller('clients', function($scope, $http) {
    $scope.data = [];
    $scope.rows = function() {
        $http.get("{{url('clients_lists')}}").then(function successCallback(response)  {
            $scope.data = response.data;
            $("#mask").hide();
        });
    }
    $scope.rows();
    $scope.borrar = function(id) {
        swal({
                title: "Confirma que quieres eliminar este registro",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#212D43',
                confirmButtonText: 'Si eliminar',
                cancelButtonText: 'Cancelar',
                closeOnConfirm: true
            },
            function() {
                // Url 
                var route = "{{url('/')}}/clients/" + id + "";
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
    $scope.registrar = function() {
        if ($("#image").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresa una imagen"
            });
            $("#image").focus();
            return (false);
        } else if ($("#name").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresa un nombre"
            });
            $("#name").focus();
            return (false);
        } else {
            //obtener Datos
            var route = "{{url('clients')}}";
            var image = $("#image").val();
            var name = $("#name").val();
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
                    name: name
                },
                success: function(data) {
                    $.growl.notice({
                        title: "<i class='fa fa-exclamation-circle'></i> Atención",
                        message: "Registro exitoso"
                    });
                    location.reload();
                },
                error: function(msj) {
                    $.growl.error({
                        title: "<i class='fa fa-exclamation-circle'></i> Error",
                        message: 'Ha ocurrido un error por favor intente más tarde'
                    });
                }
            });
        }
    }
});
$('#nvar').sortable({
    revert: true,
    opaitem: 0.6,
    cursor: 'move',
    update: function() {
        var order = $('#nvar').sortable("serialize") + '&action=orderState';
        $.post("{{url('move-customer')}}", order, function(theResponse) {
            window.location.reload();
        });
    }
});


</script>
@stop
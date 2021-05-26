@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-settings"></i> Videos</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Dashboard"><i class="ti-home"></i> Dashboard</a></li>
         <li>Videos</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper" ng-app="myApp"  ng-controller="galleries">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s9">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-pencil"></i> Ingrese sus videos aquí</h3>
                     </div>
                     <div class="widget-content">
                        <div class="input-field col s12">
                           {!!Form::label('Nombre')!!}
                           {!!Form::text('name',null,['class' => 'md-input','id'=>'name'])!!}
                        </div>
                        <div class="input-field col s12">
                           <input name="image" id="image" value="" type="hidden">
                           <div id="dropzone-video" class="dropzone"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col s3">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-check"></i> Guardar video</h3>
                     </div>
                     <div class="widget-content">
                        <p class="margin-bottom-30">Haga clic aquí para guardar su video.</p>
                        <button type="button" class="btn waves-effect waves-light orange " ng-click="registrar()" style="width: 100%"><i class="fa fa-paper-plane" aria-hidden="true"></i> Guardar </button> 
                     </div>
                  </div>
               </div>
               <div class="col s12">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-image"></i> Videos</h3>
                     </div>
                     <div class="widget-content">
                        <div id="nvar">
                           @foreach($galleries as $rs)
                           <div class="col s3">
                            <p><?php echo $rs->name;?></p>
                              <video width="320" height="240" controls style="border: 1px solid #ddd;
    margin-bottom: 10px;
    clear: both;
    width: 100%;">
                                 <source src="{{url('/')}}/uploads/{{$rs->image}}" type="video/mp4">
                                 Your browser does not support the video tag.
                              </video>
                              <span class="controls"  style="clear: both;width: 100%;">
                              <a class="edituser purple lighten-2" onclick="copia_portapapeles('<?php echo url("/");?>/uploads/<?php echo $rs->image?>')" style="cursor:pointer;" title="Copiar Url" ><i class="fa fa-clipboard"></i></a>
                              <a  ng-click="borrar('<?php echo $rs->id;?>');" style="cursor:pointer;" class="deleteuser red" title="Eliminar"><i class="ti-trash"></i></a>
                              </span>
                           </div>
                           @endforeach
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
   var app = angular.module('myApp', []);
     app.controller('galleries', function($scope, $http) {
     
   
       $scope.borrar = function(id) {
   
   
   
       swal({
             title: "Confirmá que querés eliminar este registro",
              text: "",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: '#fd2923',
              confirmButtonText: 'Si eliminá',
                  cancelButtonText: 'Cancelar',
                  closeOnConfirm: true
         },
         function() {
             // Url 
             var route = "{{url('/')}}/videos/" + id + "";
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
                  window.location.reload();   
               }
             });
           });
       }
   
       $scope.registrar = function() {
        if ($("#image").val() == "") {
          $.growl.error({
                      title: "<i class='fa fa-exclamation-circle'></i> Alert",
                      message: "You must the enter image"
                  });
          $("#image").focus();
          return (false);
        } 
        else if ($("#name").val() == "") {
          $.growl.error({
                      title: "<i class='fa fa-exclamation-circle'></i> Alert",
                      message: "You must the enter name label"
                  });
          $("#name").focus();
          return (false);
        } else {
           //obtener Datos
           var route = "{{url('videos')}}";
           var image = $("#image").val();
           var image = $("#image").val();
           image = image.replace(',', "");
           var name=$("#name").val();
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
                 $.growl.notice({title: "<i class='fa fa-exclamation-circle'></i> Atención",message: "Registro exitoso"});
                 location.reload();
            }
          });
         }
       }
   
       
   
       
     });
   
    function copia_portapapeles(url) {
  var aux = document.createElement("input");
  aux.setAttribute("value", url);
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");
  document.body.removeChild(aux);
     $.growl.warning({title: "<i class='fa fa-exclamation-circle'></i> Atención",message: "Url copiada al portapapeles"});
}

</script>
@stop
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
   <div class="widgets-wrapper" ng-app="myApp" ng-controller="paginas">
      {!!Form::model($section,['route'=>['section.update',$section],'method'=>'PUT'])!!}
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s12">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-pencil"></i> Actualizá el contenido de tu bloque aquí</h3>
                     </div>
                     <div class="widget-content">
                        <div class="input-field col s12" style="clear: both;"><br>
                           <label class="active">Título <small style="color: #fd2923">*</small></label>                     {!!Form::text('title',null,['class' => 'md-input'])!!}
                        </div>
                        <div class="input-field col s12">
                           <label>Contenido <small style="color: #fd2923">*</small><br></label> 
                           <br>
                        </div>
                        <div class="input-field col s12"> 
                           {!! Form::textarea('content',null,['class'=>"materialize-textarea",'cols'=>'5','rows'=>'5','id'=>'editor1']) !!}
                        </div>
                        <div class="input-field col s6" style="clear: both;">
                           <label class="active">Nombre del Botón #1</label>                     
                           {!!Form::text('name_boton',null,['class' => 'form-control'])!!}
                        </div>
                        <div class="input-field col s6">
                           <label class="active">Url del Botón #1</label>
                           {!!Form::text('url_boton',null,['class' => 'form-control'])!!}
                        </div>
                        <div class="input-field col s6" style="clear: both;">
                           <label class="active">Nombre del Botón #2</label>                     
                           {!!Form::text('name_boton_en',null,['class' => 'form-control'])!!}
                        </div>
                        <div class="input-field col s6">
                           <label class="active">Url del Botón #2</label>
                           {!!Form::text('url_boton_en',null,['class' => 'form-control'])!!}
                        </div>
                     </div>
                     <div class="input-field col s6" style="clear: both;">
                        <div class="demo">
                           <input type="checkbox" id="demo" name="status_content" @if($section->status_content=='0') checked="checked" @endif value="1">
                           <label for="demo"><span></span>Ocultar titulos del Bloque</label>
                        </div>
                     </div>
                     <div class="input-field col s6">
                        <div class="demo">
                           <input type="checkbox" id="demo-1" name="status" @if($section->status=='0') checked="checked" @endif value="1">
                           <label for="demo-1"><span></span>Ocultar todo el bloque</label>
                        </div>
                     </div>
                     <div class="input-field col s12"  style="clear: both;">
                        <label style="color: #fd2923!important">Campos requeridos (*)</label><br>
                     </div>
                  </div>
               <div class="widget z-depth-1">
                  <div class="widget-title">
                     <h3><i class="ti-image"></i> Imagen </h3>
                  </div>
                  <div class="widget-content">
                     <div class="input-field col s12">
                        <input name="image" id="image" value="{{$section->image}}" type="hidden">
                        <div id="dropzone-thumb" class="dropzone"></div>
                     </div>
                     <br>
                     <div class="col s3" ng-repeat="photo in data">
                        <br>
                        <img src="<?php echo url('/');?>/uploads/@{{photo.nombre}}" alt="Photo"  alt="Photo" style='display: block;border: 0.5px solid #ddd; width: 50%'>
                        <a style="cursor:pointer;font-size: 13px;" ng-click="borrar(photo.nombre)" class="red-text"><i class="fa fa-times red-text"></i> Eliminar</a>
                     </div>
                  </div>
               </div>
               <div style="width: 100%; text-align: center;"><br><br>
                  <button  class="btn orange" id="enviar"><i class="fa fa-paper-plane" aria-hidden="true"></i> Guardar</button> 
                  <br><br>
               </div>
            </div>
         </div>
      </div>
   </div>
   {!! Form::close() !!}
</div>
</div>
<!-- Content Area -->
<script type="text/javascript">
   var app = angular.module('myApp', []);
   app.controller('paginas', function($scope, $http) {
     $scope.rows = function() {
       $scope.data = [];
       $http.post("{{url('/')}}/lists-photo-section", {
         'id': <?=$section->id;?>
       })
       .then(function successCallback(response)  {
         $scope.data = response.data;
       });
     }
     $scope.rows();
     $scope.borrar = function(name) {
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
         var route = "{{url('delete-photo-section')}}";
         var token = $("#token").val();
         var image = $("#image").val();
         var patron = "," + name;
         image = image.replace(patron, '');
         $("#image").val(image);
         $.ajax({
           url: route,
           headers: {
             'X-CSRF-TOKEN': token
           },
           type: 'POST',
           dataType: 'json',
           data: {
             image: image,
             id: '<?=$section->id;?>'
           },
           success: function() {
                $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "Registro eliminado"});
             $scope.rows();
           }
         });
       });
     }
   
   });
   $( "#enviar" ).click(function() {
   $("#enviar").prop('disabled', true);
   $("form").submit();
   });
</script>
@stop
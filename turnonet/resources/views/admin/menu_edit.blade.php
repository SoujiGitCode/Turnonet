@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-settings"></i>  Menú Principal</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Menú Principal</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper" ng-app="myApp" ng-controller="menu">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <input type="hidden" name="id" value="" id="id">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s4">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                       <h3><i class="ti-pencil"></i> Configurá los elementos en tu menú aquí</h3>
                     </div>
                     <div class="widget-content">
                        <div class="input-field col s12">
                           <label class="active">Url <small style="color: #fd2923">*</small></label>
                           {!!Form::text('url',$menu->url,['id'=>'url'])!!}
                        </div>
                        <div class="input-field col s12">
                           <label class="active">Nombre <small style="color: #fd2923">*</small></label>
                           {!!Form::text('title',$menu->title,['id'=>'title'])!!}
                        </div>
                        
                       
                        
                         <div class="input-field col s12">
                        <label style="color: #fd2923!important">Campos requeridos (*)</label><br>
                      </div>
                        <div class="input-field col s12">
                           <button class="btn waves-effect waves-light orange" ng-click="guardarurl()" style="width: 100%"><i class="fa fa-paper-plane" aria-hidden="true"></i> Guardar</button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col s8">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-layers"></i> Items del menú</h3>
                     </div>
                     <div class="widget-content">
                        <div class="sortable-style">
                           <ul class="exclude list" id="navsm">
                              <li id="item_@{{row.id}}" class="uk-nestable-item" ng-repeat="row in data" style="border: 1px solid #f2f2f2;    border-radius: 0.3rem;">
                                 <div class="col s6">
                                    <p style="font-weight: 300; font-size: 16px; margin-bottom: 0px">@{{ row.title }}</p>
                                 </div>
                                 <div class="col s6">
                                    <span class="controls">
                                    <a class="edituser purple lighten-2" style="cursor:move;    padding: 6px 16px;" ><i class="ti-move"></i> Mover</a>
                                    <a  href="{{url('/')}}/menu/@{{row.id}}/edit"  class="edituser blue" style="    padding: 6px 16px;" ><i class="ti-pencil-alt"></i> Editar</a>
                                    <a  ng-click="desactivar(row.id)"  ng-show="row.status=='1'" class="edituser red" style="    padding: 6px 16px;"> Desactivar</a>
                                    <a  ng-click="activar(row.id)" ng-show="row.status=='2'" class="edituser green" style="    padding: 6px 16px;"> Activar</a>
                                    </span>
                                 </div>
                              </li>
                           </ul>
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
        $("#mask").show();
   var app = angular.module('myApp', []);
app.controller('menu', function($scope, $http, $window) {
    //Listar Menu
    $scope.rows = function() {
        $scope.data = [];
        $http.get("{{url('menu_lists')}}").then(function successCallback(response)  {
            $scope.data = response.data;
                  $("#mask").hide();
        });
    }
    $scope.rows();
    // Guardar urls
    $scope.guardarurl = function() {
        //Obtener Datos
        var title = $("#title").val();
        var url = $("#url").val();
        var token = $("#token").val();
        var image=$("#image").val();
        var route = "{{url('/')}}/menu/<?=$menu->id;?>";
        //validar que titulo Not este vacio
        if (url == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá el url en español"
            });
            $('#url').focus();
            return (false);
        } else if (title == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                message: "Ingresá el nombre en español"
            });
            $('#title').focus();
            return (false);
        }
         else {
            //Enviar Datos
            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: 'PUT',
                dataType: 'json',
                data: {
                    title: title,
                    url: url,
                    image: image,
                    type: '2'
                },
                success: function(data) {
                    $("#title").val('');
                    $("#url").val('');
                    $.growl.warning({
                        title: "<i class='fa fa-exclamation-circle'></i> Atención",
                        message: "Datos Actualizados"
                    });
                    $scope.rows();
                    window.location = '{{url("menu")}}';
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
    //Boorar Item Menu
    $scope.desactivar = function(id) {
        swal({
                title: "Confirmá que querés desactivar este item del menú",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#CE0505',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                closeOnConfirm: true,
            },
            function() {
                //Obtener Datos
                var route = "{{url('up-status-menu')}}";
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
                        $.growl.error({
                            title: "<i class='fa fa-exclamation-circle'></i> Atención",
                            message: "El estatus del item ha sido actualizado"
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
            });
    }
    $scope.activar = function(id) {
        swal({
                title: "Confirmá que querés activar este item del menú",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#CE0505',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                closeOnConfirm: true,
            },
            function() {
                //Obtener Datos
                var route = "{{url('up-status-menu')}}";
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
                        $.growl.warning({
                            title: "<i class='fa fa-exclamation-circle'></i> Atención",
                            message: "El estatus del item ha sido actualizado"
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
            });
    }

    $scope.rows_images = function() {
       $scope.data_photo = [];
       $http.post("{{url('/')}}/lists-photo-menu", {
         'id': <?=$menu->id;?>
       })
       .then(function successCallback(response)  {
         $scope.data_photo = response.data;
       });
     }
     $scope.rows_images();
     $scope.borrar_image = function(name) {
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
         var route = "{{url('delete-photo-menu')}}";
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
             id: '<?=$menu->id;?>'
           },
           success: function() {
                $.growl.error({ title: "<i class='fa fa-exclamation-circle'></i> Atención", message: "Registro eliminado"});
             $scope.rows_images();
           }
         });
       });
     }
});
$('#navsm').sortable({
    revert: true,
    opaitem: 0.6,
    cursor: 'move',
    update: function() {
        var order = $('#navsm').sortable("serialize") + '&action=orderState';
        $.post("{{url('move-menu')}}", order, function(theResponse) {
            window.location.reload();
        });
    }
});
</script>
@stop
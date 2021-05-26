@extends('layouts.template')
@section('content')

<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-announcement"></i> Comunicaciones</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Comunicaciones</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
      <div class="widgets-wrapper" ng-app="myApp" ng-controller="paginas">
      {!!Form::model($communication,['route'=>['communication.update',$communication],'method'=>'PUT'])!!}
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s12">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-pencil"></i> Actualizá la información de tu comunicación aquí</h3>
                     </div>
                     <div class="widget-content">
                        <div class="input-field col s12" style="clear: both;"><br>
                           <label class="active">Título <small style="color: #ec0e08">*</small></label>{!!Form::text('title',null,['class' => 'form-control'])!!}
                        </div>
                        <div class="input-field col s12" style="clear: both;"><br>
                           <label class="active">Contenido <small style="color: #ec0e08">*</small> </label> 
                              </div>
                              <div class="input-field col s12"> 
                          {!! Form::textarea('content',null,['class'=>"materialize-textarea",'cols'=>'5','rows'=>'5','id'=>'editor1'])!!}
                        </div>
                        <div class="input-field col s12">
                           <label style="color: #ec0e08!important">Campos requeridos (*)</label><br>
                        </div>
                     </div>
                  </div>
                  <div style="width: 100%; text-align: center;"><br><br>
                     <button  class="btn waves-effect waves-light orange"><i class="fa fa-paper-plane" aria-hidden="true"></i> Guardar</button> 
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
@stop
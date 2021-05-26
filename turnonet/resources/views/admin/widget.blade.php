@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-layout"></i> Características</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Características</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper">
      {!! Form::open(['route' => 'widget.store','method' => 'POST']) !!}
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s12">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-pencil"></i> Ingresá el contenido de tu widget aquí</h3>
                     </div>
                     <div class="widget-content">
                        
                        <div class="input-field col s12" style="clear: both;"><br>
                           <label class="active">Título<small style="color: #fd2923">*</small></label>                     {!!Form::text('title',null,['class' => 'form-control'])!!}
                        </div>
                        <div class="input-field col s12">
                           <label>Contenido<small style="color: #fd2923">*</small><br></label> 
                           <br>
                        </div>
                        <div class="input-field col s12"> 
                           {!! Form::textarea('content',null,['class'=>"materialize-textarea",'cols'=>'5','rows'=>'5','id'=>'editor1']) !!}
                        </div>
                     </div>
                     <div class="input-field col s12">
                        <label style="color: #fd2923!important">Campos requeridos (*)</label><br>
                     </div>
                  </div>
               
               <div class="widget z-depth-1">
                  <div class="widget-title">
                     <h3><i class="ti-image"></i> Imagen (200px x 200px)</h3>
                  </div>
                  <div class="widget-content">
                     <div class="input-field col s12">
                        <input name="image" id="image" value="" type="hidden">
                        <div id="dropzone-thumb" class="dropzone"></div>
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
   $( "#enviar" ).click(function() {
   $("#enviar").prop('disabled', true);
    $("form").submit();
   });
</script>
@stop
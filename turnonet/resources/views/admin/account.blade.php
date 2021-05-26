@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1>Datos de la cuenta</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Datos de cuenta</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s6">
                  <div class="widget z-depth-1">
                     
                     <div class="widget-title">
                        <h3><i class="ti-lock"></i> Actualizá la información de tu cuenta aquí</h3>
                     </div>
                     <div class="widget-content">
                     {!!Form::model($user,['route'=>['dashboard.update',$user],'method'=>'PUT'])!!}
                     <div class="input-field col s12">
                          <label class="active">Nombre <small style="color: #ec0e08">*</small></label>
                        {!!Form::text('name',null)!!}
                        <span class="md-input-bar "></span>
                     </div>
                     <div class="input-field col s12">
                        <label class="active">Correo electrónico <small style="color: #ec0e08">*</small></label>
                        {!!Form::text('email',null)!!}
                        <span class="md-input-bar "></span>
                     </div>
                     <div class="input-field col s12">
                        {!!Form::label('Contraseña')!!}
                        {!!Form::password('password', ['class' => 'form-control'])!!}
                        <span class="md-input-bar "></span>
                     </div>
                     <div class="input-field col s12">
                           <label style="color: #ec0e08!important">Campos requeridos (*)</label><br>
                     </div>
                     <div class="input-field col s12">
                        {!!Form::submit('Guardar', ['class' => 'btn waves-effect waves-light orange'])!!}
                     </div>
                     {!! Form::close() !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Content Area -->
@stop
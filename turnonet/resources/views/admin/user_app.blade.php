@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-user"></i> Usuarios</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Usuarios</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s6">
                  <div class="widget z-depth-1">
                     <div class="loader"></div>
                     <div class="widget-title">
                        <h3><i class="ti-pencil"></i> Ingresá la información de tu usuario aquí</h3>
                     </div>
                          <div class="widget-content"> 
                     {!! Form::open(['route' => 'users-app.store','method' => 'POST']) !!}
                     <div class="input-field col s12">
                        <label class="active">Nombre y apellido <small style="color: #ec0e08">*</small></label>
                        {!!Form::text('us_nom',null,['class' => 'form-control'])!!}
                     </div>
                     <div class="input-field col s12">
                         <label class="active">Correo electrónico <small style="color: #ec0e08">*</small></label>
                        {!!Form::text('us_mail',null,['class' => 'form-control'])!!}
                     </div>
                     <div class="input-field col s12">
                           <label style="color: #ec0e08!important">Campos requeridos (*)</label><br>
                        </div>
                     <div class="input-field col s12">
                        {!!Form::label(' ')!!}
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
@stop
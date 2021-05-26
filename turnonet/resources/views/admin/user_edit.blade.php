@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-user"></i> Administradores</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Administradores</li>
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
                        <h3><i class="ti-pencil"></i> Actualizá la información de tu usuario aquí</h3>
                     </div>
                     <div class="widget-content"> 
                     {!!Form::model($user,['route'=>['user.update',$user],'method'=>'PUT'])!!}
                     <div class="input-field col s12">
                        <label class="active">Nombre <small style="color: #fd2923">*</small></label>
                        {!!Form::text('name',null)!!}
                     </div>
                     <div class="input-field col s12">
                        <label class="active">Correo electrónico <small style="color: #fd2923">*</small></label>
                        {!!Form::text('email',null)!!}
                     </div>
                     <div class="input-field col s12">
                     <label class="active">Rol <small style="color: #fd2923">*</small></label>
                        {!!Form::select('rol',$rol, null, ['placeholder' => 'Seleccione','class'=>'browser-default','style'=>'width: 100%'])!!}
                       
                     </div>
                     <div class="input-field col s12">
                        <label class="active">Contraseña </label>
                        {!!Form::password('password')!!}
                     </div>
                     <div class="input-field col s12">
                           <label style="color: #fd2923!important">Campos requeridos (*)</label><br>
                        </div>
                     <div class="input-field col s12">
                        {!!Form::label(' ')!!}
                        {!!Form::submit('Actualizar', ['class' => 'btn waves-effect waves-light orange'])!!}
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

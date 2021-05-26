@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
         <h2 class="title-section-2">Mi CUENTA</h2>
         <h4 class="title-date-2">Administra la información de tu cuenta</h4>
         <br>
         <ul class="nav nav-pills">
            <li><a href="<?php echo url('mi-perfil');?>">General</a></li>
             @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')
            <li><a href="<?php echo url('actualizar-cuenta');?>">Datos adicionales</a></li>
            @endif
            <li class="active"><a href="<?php echo url('actualizar-clave');?>">Actualizar contraseña</a></li>
         </ul>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Actualizá tu contraseña aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <div class="form-group">
                     <label>Contraseña*</label>
                     <input name="password" type="password" value="" placeholder="Ingresá tu contraseña" id="password"  onkeypress="enter_uppassword(event)" class="form-control">
                  </div>
                  <div class="form-group">
                     <label>Confirmar contraseña*</label>
                     <input name="cpasswordr" type="password" value="" placeholder="Ingresá tu contraseña" id="cpasswordr" onkeypress="enter_uppassword(event)"  class="form-control">
                     <p class="form-desc">Las contraseñas deben tener al menos 6 caracteres. </p>
                  </div>
                  <div class="form-group">
                     <p>Campos obligatorios (*)</p>
                  </div>
                  <div class="form-group">
                     <button type="button" onclick="update_password()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
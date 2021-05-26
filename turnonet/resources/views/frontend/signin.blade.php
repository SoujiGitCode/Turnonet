@extends('layouts.template_frontend')
@section('content')
<div class="container p-4">
   <div class="row">
      <div class="col-md-4 col-sm-4 col-xs-12"></div>
      <div class="col-md-4 col-sm-4 col-xs-12 mx-auto">
         <div class="panel card-w1 text-center">
            <img src="<?php echo url('/');?>/img/logo_1.png" alt="Turnonet" class="card-img-top mx-auto m-2 img-23vw">
            <div class="panel-body">
               <p>Por favor ingresá tu correo y contraseña</p>
               <form id="form">
                  <div class="form-group ">
                     <input type="text" name="email" id="email" placeholder="Correo electrónico" class="form-control" autofocus onkeypress="enter_login(event)">
                  </div>
                   <div class="form-group ">
                     <input type="password" name="password" id="password" placeholder="Contraseña" class="form-control" onkeypress="enter_login(event)">
                  </div>
                  <div class="form-group" style="text-align: right;">
                     <p> <a href="<?php echo url('enviar-codigo');?>" style="color: #f15a24!important">¿Olvidaste la clave?</a></p>
                  </div>

                  <div class="form-group">
                     <button class="btn btn-info btn-block" type="button" onclick="sendLogin()" id="boton-1">Ingresar</button>
                     
                  </div>
               </form>
            </div>
         </div>
         <p style="text-align: center;color: #fff"> <span >Turnonet </span> © 2011 - {{date("Y")}}</p>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12"></div>
   </div>
</div>
<script type="text/javascript">
   history.pushState({foo: "/iniciar-sesion" }, "Iniciar Sesion", "/iniciar-sesion");
             
</script>
@stop
@extends('layouts.template_frontend')
@section('content')
<div class="container p-4">
   <div class="row">
      <div class="col-md-4 col-sm-4 col-xs-12"></div>
      <div class="col-md-4 col-sm-4 col-xs-12 mx-auto">
         <div class="panel card-w1 text-center">
           <img src="<?php echo url('/');?>/img/logo_1.png" alt="Turnonet" class="card-img-top mx-auto m-2 img-23vw">
            <div class="panel-body">
               <p>Ingresá el correo electrónico con el que se registró</p>
               <form   id="form">
                  <div class="form-group">
                     <input type="text" name="email" id="email" placeholder="Correo electrónico" class="form-control"  onkeypress="enter_code(event)">
                  </div>
                  <div class="form-group">
                     <button class="btn btn-info btn-block" type="button" onclick="sendCode()" id="boton-1">Solicitá tu código</button>
                  </div>
                  <div class="form-group" style="text-align: center;">
                     <p>¿Tenés un código? <a href="<?php echo url('cambiar-clave');?>" style="color: #f15a24!important">Si</a></p>
                      <p>¿Tenés cuenta?<a href="<?php echo url('iniciar-sesion');?>" style="color: #f15a24!important"> Ingresá aquí</a> </p>
                  </div>
               </form>
            </div>
         </div>
         <p style="text-align: center;color: #fff"> <span >Turnonet </span> © 2011 - {{date("Y")}}</p>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12"></div>
   </div>
</div>
@stop
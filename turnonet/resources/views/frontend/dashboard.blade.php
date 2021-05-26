@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br><br><br>
         <h4 class="title-date-2">PANEL DE CONTROL</h4>
      </div>
      <div class="col-md-3 col-sm-3 col-xs-12">
         <div class="widget" onclick="window.location='<?php echo url('agenda');?>'"  title="Ir a la agenda">
            <div class="widget-title light">
               <h3>Agenda</h3>
               <p>Mantente al tanto de los turnos solicitados</p>
            </div>
            <div class="widget-content">
               <div class="main-counter">
                  <div class="nav-icon-3 bookmark-white"></div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-3 col-sm-3 col-xs-12">
         <div class="widget"  onclick="window.location='<?php echo url('empresas');?>'" title="Ir a empresas">
            <div class="widget-title light">
               <h3>Empresas</h3>
               <p>Ingrese, actualice y elimine información de sus empresas</p>
            </div>
            <div class="widget-content">
               <div class="main-counter">
                  <div class="nav-icon-3 work-white"></div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-3 col-sm-3 col-xs-12">
         <div class="widget" onclick="window.location='<?php echo url('sucursales');?>'" title="Ir a sucursales">
            <div class="widget-title light">
               <h3>Sucursales</h3>
               <p>Ingrese, actualice y elimine información de sus sucursales</p>
            </div>
            <div class="widget-content">
               <div class="main-counter">
                  <div class="nav-icon-3 sucursal-white"></div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-3 col-sm-3 col-xs-12">
         <div class="widget" onclick="window.location='<?php echo url('prestadores');?>'" title="Ir a prestadores">
            <div class="widget-title light">
               <h3>Prestadores</h3>
               <p>Ingrese, actualice y elimine información de sus prestadores</p>
            </div>
            <div class="widget-content">
               <div class="main-counter">
                  <div class="nav-icon-3 lender-white"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
   @if($personalData==0)
     @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')
      <div class="row">
      <div class="col-md-12">
         <br>
         <div class="alert alert-warning" role="alert">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> No has completado tus datos adicionales. Hacelo <a href="<?php echo url('actualizar-cuenta');?>">ahora</a>
         </div>
      </div>
   </div>
   @endif
   @endif

   <div class="row">
      <div class="col-md-12">
         <div class="alert alert-danger" role="alert">
            Debido a la cuarentena general dispuesta por el Gobierno Nacional Argentino, sólo brindaremos soporte técnico y comercial mediante correo electrónico. Nos pueden contactar en: <a href="mailto:info@turnonet.com" target="_blank">info@turnonet.com</a>
         </div>
      </div>
   </div>



</div>
</div>
@stop
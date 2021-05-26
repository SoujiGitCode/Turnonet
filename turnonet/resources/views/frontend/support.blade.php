@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-5 col-sm-5 col-xs-12">
         <h2 class="title-section-2">SOPORTE</h2>
         <div class="panel">
            <div class="panel-heading">
               Si necesitás ayuda, mandá un mensaje.
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                  <div class="form-group">
                     {!!Form::text('subject',null,['id'=>'subject','placeholder'=>'Asunto*','class'=>'form-control'])!!}
                  </div>
                  <div class="form-group">
                     {!! Form::textarea('message',null,['id'=>'message','placeholder'=>'Mensaje*','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
                  </div>
                  <div class="form-group">
                     <p>Campos obligatorios (*)</p>
                  </div>
                  <div class="form-group">
                     <button type="button" onclick="saveBugs()" class="btn btn-success" id="boton-1">Enviar mensaje</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <div class="col-md-7 col-sm-7 col-xs-12">
         <div class="shedules">
            <h3>Días y Horarios de Atención</h3>
            <a><i class="fa fa-clock-o"></i> Lunes a Viernes de 10 a 17hs.</a>
            <a href="tel:(11) 3530.9345" target="_blank"><i class="fa fa-phone"></i> (11) 3530.9345 </a>
            <a href="mailto:info@turnonet.com" target="_blank"><i class="fa fa-envelope-o"></i> info@turnonet.com</a>
            <br>
            <div class="alert alert-danger" role="alert">
             Durante la vigencia del aislamiento obligatorio dictaminado por en DNU 297/2020 únicamente atenderemos consultas via mail. En caso de inconvenientes o consultas puede comunicarse las 24hs tambien por mail a info@turnonet.com.
          </div>
         </div>
      </div>
   </div>
</div>
@stop
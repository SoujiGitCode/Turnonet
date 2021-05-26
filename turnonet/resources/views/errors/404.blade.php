@extends('layouts.template_frontend_error')
@section('content')
   <div class="container section-contact p-top30" style="padding-bottom: 100px">
      <div class="row text-center">
          <div class="col-xs-12 text-center">
         <img src="<?php echo url('/');?>/uploads/icons/500_error_page.png" alt="No hay resultados" class="img-error">
      </div>
         <div class="col-xs-12 text-center">
            <h3 class="title-error">¡Lo sentimos! Ha ocurrido un error</h3>
            <p>No te preocupes, nuestro equipo de desarrollo está trabajando en ello. Por favor intentá nuevamente en unos minutos</p>
         </div>
         <div class="col-xs-12 text-center">
            <a href="{{url('/')}}" class="btn btn btn-info  btn-error" >Ir al inicio</a>
            <br>
            <a href="mailto:info@turnonet.com.ar" class="btn btn btn-info  btn-error" style="background: #FF5722!important; border: 1px solid #FF5722!important"><i class="fa fa-envelope-o"></i> Reportar error</a>
         </div>
        
      </div>
   </div>
  

@stop
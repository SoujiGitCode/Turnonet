@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-briefcase"></i> Empresas</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Empresas</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <input type="hidden" name="id" value="" id="id">
      <div class="row">
          <div class="col s6">
         <div class="widget z-depth-1">
           
            <div class="widget-content">
               <div class="col s12" style="clear: both;"> 
 <div class="form-group">
                  <p>Seleccioná un rango de fechas y exportá el reporte de <?php echo mb_substr(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100);?></p>

             </div>
              
                  <div class="form-group">
                         <label>Fecha Inicio</label>
                  <input type='text' id='fecha_inicial' class="form-control" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);padding: 8px;" value="01-<?php echo date('m');?>-<?php echo date("Y");?>" placeholder="Desde" />
               </div>
               </div>
               <div class="col s12">
                        <div class="form-group">
                           <label>Fecha Final</label>
                  <input type='text' id='fecha_final' class="form-control" style="background-color: #fafafa;border:1px solid rgba(0, 0, 0, .12);padding: 8px;" value="<?php echo date('d-m-Y');?>" placeholder="Hasta" />
               </div>
               </div>
               <div class="col s12">
                        <div class="form-group">
                  <a  class="btn orange" onclick="buscar_fecha()"  style="height: 45px;line-height: 50px; border-radius: 0.3rem;background-color: #33b56a!important;">Descargá  Reporte</a>
               </div>
               </div>
            </div>
         </div>
      </div>
      </div>
   </div>
</div>
</div>
<!-- Content Area -->
<script type="text/javascript">
   $(function() {
    $('#fecha_inicial').datetimepicker({
      format: 'DD-MM-YYYY'
    });
    $('#fecha_final').datetimepicker({
      format: 'DD-MM-YYYY'
    });
   });

   function buscar_fecha(){

    if ($('#fecha_inicial').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_inicial').focus();
            return (false);
        }
        if ($('#fecha_final').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_final').focus();
            return (false);
        }else{
          var fecha_inicial =document.getElementById("fecha_inicial").value;
          var fecha_final =document.getElementById("fecha_final").value;
          var url="<?php echo url('/');?>/pdf/business/<?php echo $business->em_id;?>/"+fecha_inicial+"/"+fecha_final;
          window.open(url);
        }


   }
</script>
@stop
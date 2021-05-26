@extends('layouts.template_frame')
@section('content')
<?php 
   function nameMonth($month) {
     switch ($month) {
       case 1:
       $month = 'Enero';
       break;
       case 2:
       $month = 'Febrero';
       break;
       case 3:
       $month = 'Marzo';
       break;
       case 4:
       $month = 'Abril';
       break;
       case 5:
       $month = 'Mayo';
       break;
       case 6:
       $month = 'Junio';
       break;
       case 7:
       $month = 'Julio';
       break;
       case 8:
       $month = 'Agosto';
       break;
       case 9:
       $month = 'Septiembre';
       break;
       case 10:
       $month = 'Octubre';
       break;
       case 11:
       $month = 'Noviembre';
       break;
       case 12:
       $month = 'Diciembre';
       break;
     }
     return $month;
   }
   
   function nameDay($day) {
   
           switch ($day) {
               case 0:
               $day = 'Domingo';
               break;
               case 1:
               $day = 'Lunes';
               break;
               case 2:
               $day = 'Martes';
               break;
               case 3:
               $day = 'Miércoles';
               break;
               case 4:
               $day = 'Jueves';
               break;
               case 5:
               $day = 'Viernes';
               break;
               case 6:
               $day = 'Sábado';
               break;
           }
           return $day;
    }
   
   ?>
  <?php  $frame = DB::table('frame')->where('emp_id', $get_business->em_id)->first(); ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo env('CAPTCHA_KEY_SITE');?>"></script>
<form id="form">
<input type="hidden" name="captcha" id="captcha" value="">
   <input type="hidden" id="tu_id" name="tu_id" value="<?php echo $get_shift->tu_id;?>">
   @if($frame->header==0)
   <a href="<?php echo url('/');?>/<?php echo $frame->url;?>/disponibilidad/<?php echo $lender->url;?>" class="btn-back"></a>
   @endif
   <div class="container container-single ptop">
    @if($frame->name==0)
      <div class="row">
         <div class="col-md-2 col-sm-3 col-xs-12 hidden-xs">
            <?php
               $image=url('/').'/img/emptylogo.png';
               
               $get_business=DB::table('tu_emps')->where('em_id',$lender->emp_id)->first();
               
               $sucursal=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first();
               
               if(isset($get_business)!=0 && $get_business->em_pfot!=""){
                 $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
               }
               if(isset($sucursal)!=0 && $sucursal->suc_pfot!=""){
                 $image="https://www.turnonet.com/fotos/sucursales/".$sucursal->suc_pfot;
               }
               if($lender->tmsp_pfot!=""){
                 $image="https://www.turnonet.com/fotos/prestadores/".$lender->tmsp_pfot;
               }
               
               ?>
            <img src="<?php echo $image;?>" class="img-press-1">
         </div>
         <div class="col-md-10 col-sm-9 col-xs-12 no-padding-desktop">
            <div class="tit" style="margin-bottom: 0vw;">
               <?php echo $lender->tmsp_tit;?> <?php echo mb_strtoupper($lender->tmsp_pnom);?>     
            </div>
            <?php $address=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first(); ?>
            @if(isset($address)!=0)
            <div class="subtit "> <?php echo $address->suc_dom;?> <?php echo $address->suc_domnum;?> <?php echo $address->suc_dompiso;?> </div>
            @endif
         </div>
      </div>
      @endif
      <div class="row">
         <div class="col-md-8 col-sm-8 hidden-xs">
         </div>
         <div class=" col-md-4 col-sm-4 hidden-xs text-rigth">
            <div class="volver2">
               <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
               <a href="<?php echo url('/');?>/<?php echo $frame->url;?>/disponibilidad/<?php echo $lender->url;?>">Solicitar Turno</a>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12 Datosturn" id="Datosturn">
            <div class="nom">Datos del turno</div>
            <div class="turin">
               <strong>Fecha:</strong> <?php echo nameDay(date("w", strtotime($get_shift->tu_fec))) . ' ' . date("d", strtotime($get_shift->tu_fec)) . ' de ' . NameMonth(date("m", strtotime($get_shift->tu_fec))); ?><br>
               <strong>Horario:</strong> <?php echo date("H:i", strtotime($get_shift->tu_hora));?> hs.
               <br>
                 @if(isset($address)!=0)
               <strong>Sucursal:</strong> <?php echo $address->suc_dom;?> <?php echo $address->suc_domnum;?> <?php echo $address->suc_dompiso;?> <br> 
               @endif
               @if($get_shift->tu_servid!="0" &&  $get_shift->tu_servid!="")
               <strong>Servicios Solicitados:</strong><br>
               <?php
                  if (substr_count($get_shift->tu_servid, '-') <= 0){
                  
                  
                  $get_service = DB::table('tu_emps_serv')->where('serv_id',$get_shift->tu_servid)->first();
                  
                   if (isset($get_service) != 0) {
                  
                  
                     echo trim($get_service->serv_nom).'<br>';
                  }
                  
                  }else{
                  
                  
                  for ($i = 0; $i <= substr_count($get_shift->tu_servid, '-'); $i++) {
                   $service = explode('-', $get_shift->tu_servid);
                   $get_service = DB::table('tu_emps_serv')->where('serv_id',$service[$i])->first();
                  
                   if (isset($get_service) != 0) {
                  
                  
                     echo trim($get_service->serv_nom).'<br>';
                  }
                  
                  }
                  }
                  
                  ?>
               @endif
            </div>
            <div class="row">
               <div class="col-md-12">
                <br>
                  <div class="form-group">
                     <label>Motivo de cancelación  <small style="color: #fd2923">*</small></label>
                     {!! Form::textarea('message',null,['id'=>'message','placeholder'=>'Ingresá un comentario*','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
                  </div>
                  <div class="form-group">
                     <label style="color: #fd2923!important">Campos requeridos (*)</label>
                  </div>
                  <div class="form-group text-right" >
                     <button  type="button" class="btn btn-default-1" onclick="cancelar()" style="float: right;" id="btn-create" ><i class="fa fa-check-circle" aria-hidden="true"></i> Cancelar Turno</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>
<input type="hidden" value="<?php echo env('CAPTCHA_KEY_SITE');?>" id="captcha-key">
<script type="text/javascript">
  
function cancelar() {
    grecaptcha.ready(function() {
        grecaptcha.execute($("#captcha-key").val(), {
            action: 'homepage'
        }).then(function(token) {
            $('#captcha').val(token);
            if ($("#message").val() == "") {
                launch_toast('Ingresá un comentario');
                $("#message").val();
                return false;
            } else {

                swal("Confirmá que querés cancelar el turno", {
                        buttons: {
                            cancel: "No",
                            confirm: "Si"
                        },
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {


                            var route = $("#url").val() + '/cancelar_turno';
                            $("#btn-create").prop('disabled', true);
                            $("#btn-create").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
                            $.ajax({
                                url: route,
                                type: 'POST',
                                dataType: 'json',
                                data: $("#form").serialize(),
                                success: function(data) {
                                    if (data.response == 'no-catpcha') {

                                        swal("No se pudo verificar el captcha", {
                                                allowOutsideClick: false,
                                                closeOnClickOutside: false
                                            })
                                            .then((value) => {
                                                location.reload();
                                            });

                                    } else {
                                      
                                        location.reload();
                                    }
                                },
                                error: function(msj) {
                                    $("#btn-create").prop('disabled', false);
                                    $("#btn-create").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Cancelar Turno');
                                    launch_toast('Ha ocurrido un error por favor intente más tarde');
                                }
                            });

                        }

                    });
            };
        });
    });
}
</script>
@stop
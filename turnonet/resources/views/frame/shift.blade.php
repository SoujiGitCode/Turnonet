@extends('layouts.template_frame')
@section('content')

<?php  $frame = DB::table('frame')->where('emp_id', $get_business->em_id)->first(); ?>
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
         <div class="row top-11" id="confturn">
            <div class="col-md-12 text-center">
               <i class="fa fa-calendar-check-o check-i" aria-hidden="true"></i>
               <h3 class="tit-1" style=" color: #3EAF23;" >SU TURNO HA SIDO CONFIRMADO</h3>
               <br>
            </div>
            <div class="col-md-12">
               <p class="text-date-2">Puedes tomar nota del turno:</p>
               <ul class="d-tur">
                  <?php 
                     $time = (date("H", strtotime($get_shift->tu_hora)) <= 12) ? 'AM' : 'PM';
                     
                     ?>
                  <li>
                     <i class="fa fa-clock-o ee0" aria-hidden="true"></i>
                     <?php echo nameDay(date("w", strtotime($get_shift->tu_fec))) . ' ' . date("d", strtotime($get_shift->tu_fec)) . ' de ' . NameMonth(date("m", strtotime($get_shift->tu_fec))); ?> a las <?php echo date("H:i", strtotime($get_shift->tu_hora));?> <?php echo $time;?>
                  </li>
                  <li>
                     
                     <div style="    float: left;"><i class="fa fa-calendar-plus-o ee0" aria-hidden="true"></i> Agregar a :</div>
                     <a class="btn-net btn-net-first" href="<?php echo $btn_google;?>"  target="_blank">
                        <div ><img src="<?php echo url('/');?>/uploads/icons/icon_google.png" class="img-bt"></div>
                        <div class="title-btn">Google</div>
                     </a>
                     <a class="btn-net"
                        href="<?php echo $btn_ical;?>"
                        target="_blank">
                        <div ><img src="<?php echo url('/');?>/uploads/icons/icon_ical.png" class="img-bt"></div>
                        <div class="title-btn">iCal</div>
                     </a>
                     <a class="btn-net" href="<?php echo $btn_ical;?>" target="_blank">
                        <div ><img src="<?php echo url('/');?>/uploads/icons/icon_outlook.png" class="img-bt"></div>
                        <div class="title-btn">Outlook</div>
                     </a>
                     <a class="btn-net"
                        href="<?php echo $btn_yahoo;?>"
                        target="_blank">
                        <div ><img src="<?php echo url('/');?>/uploads/icons/icon_yahoo.png" class="img-bt"></div>
                        <div class="title-btn">Yahoo</div>
                     </a>
                  </li>
                  @if($get_shift->url_zoom!="")
                  <li><p class="text-date-2"><br>Meeting:</p>
                    <p class="text-date" style="text-align: left;">URL: <?php echo $get_shift->url_zoom;?></p>
                    <button id="copy-pass"  onclick="CopyToClipboard()">Copiar <img src="https://img.icons8.com/carbon-copy/2x/copy.png"  class="copy-clipboard" alt="copiar url">
                                            </button>      
                  </li>       
                  @endif
               </ul>
            </div>
         </div>
         <div class="row top-11">
            <div class="col-md-12">
               <?php
                  $content='';
                  
                  if (isset($notifications_lender) != 0 && $notifications_lender->pc_msg != "") {
                       $content .= '<h3 class="tit" style=" color: #f15424;" >NOTIFICACIONES</h3><br>';
                       $content .= '<p>'.mb_convert_encoding($notifications_lender->pc_msg, 'UTF-8', 'UTF-8') . '</p>';
                  
                       if ($notifications_lender->pc_suc_msg == 1 && isset($notifications_branch) != 0 && $notifications_lender->pc_msg != $notifications_branch->pc_msg) {
                           $content .= '<p>'.mb_convert_encoding($notifications_branch->pc_msg, 'UTF-8', 'UTF-8') . '</p>';
                       }
                  
                       if ($notifications_lender->pc_emp_msg == 1 && isset($notifications_business) != 0 && $notifications_lender->pc_msg != $notifications_business->pc_msg) {
                           $content .= '<p>'.mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8') . '</p>';
                       }
                   }
                   if (isset($notifications_branch) != 0 && isset($notifications_branch) == 0 && $notifications_branch->pc_msg != "") {
                       $content .= '<h3 class="tit" style=" color: #f15424;" >NOTIFICACIONES</h3><br>';
                       $content .= '<p>'.mb_convert_encoding($notifications_branch->pc_msg, 'UTF-8', 'UTF-8') . '</p>';
                       if ($notifications_branch->pc_emp_msg == 1 && isset($notifications_business) != 0 && $notifications_branch->pc_msg != $notifications_business->pc_msg) {
                           $content .= '<p>'.mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8') . '</p>';
                       }
                   }
                   if (isset($notifications_business) != 0 && isset($notifications_branch) == 0 && isset($notifications_lender) == 0 && $notifications_business->pc_msg != "") {
                       $content .= '<h3 class="tit" style=" color: #f15424;" >NOTIFICACIONES</h3><br>';
                       $content .= '<p>'.mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8') . '</p>';
                   }
                  
                  
                  $content= str_replace("\n", '<br>', $content); echo $content;
                  
                  ?>
               <br><br><br>
            </div>
         </div>
      </div>
   </div>
</div>
  <?php $copy= $get_shift->url_zoom; ?>

    <input type="text" style="opacity: 0" id="myInput" name="myInput" value="<?php echo $copy;?>">
@stop
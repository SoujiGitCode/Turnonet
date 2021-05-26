@extends('layouts.template_frame')
@section('content')

<?php  $frame = DB::table('frame')->where('emp_id', $get_business->em_id)->first(); ?>

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
      <div class="row top-11">
         <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
              <?php echo $text;?>
            </div>
         </div>
      </div>
   </div>
@stop
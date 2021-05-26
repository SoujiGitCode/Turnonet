@extends('layouts.template_frame')
@section('content')
<?php  $frame = DB::table('frame')->where('emp_id', $get_business->em_id)->first(); ?>
<div class="container container-single ptop" >
    @if($frame->name==0)
   <div class="row">
      <div class="col-md-2 col-sm-2 col-xs-3">
         <?php
            $image=url('/').'/img/emptylogo.png';
            
            if($get_business->em_pfot!=""){
              $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
            }    
            ?>
         <img src="<?php echo $image;?>" class="img-press-1">
      </div>
      <div class="col-md-10 col-sm-10 col-xs-9 pno-padding-desktop">
         <div class="tit"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,100);?></div>
      </div>
   </div>
   @endif
   <div class="row top-11">
         <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
               La empresa ha sido  dada de baja. Para más información póngase en contacto con <?php echo ucwords(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'));?>.
            </div>
         </div>
      </div>
</div>
@stop
@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?> > <?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Obras sociales</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Seleccioná la obra social en la que aplica el prestador
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" id="em_id" name="em_id" value="<?php echo $get_business->em_id;?>">
                  <input type="hidden" id="suc_id" name="suc_id" value="<?php echo $get_branch->suc_id;?>">
                  <input type="hidden" id="id" name="id" value="<?php echo $get_lender->tmsp_id;?>" >
               <div class="row">
<?php $e=0; ?>
                  @foreach ($social_works as $rs)

                  <?php $social_emp=DB::table('tu_emps_ob')
                  ->where('eob_empid',$get_business->em_id)
                  ->where('eob_presid','0')
                  ->where('eob_sucid','0')
                  ->where('eob_obid',$rs->os_id)
                  ->count(); ?>

                  @if($social_emp!=0)

                  <?php $e=$e+1; ?>

                  <?php $social=DB::table('tu_emps_ob')
                  ->where('eob_presid',$get_lender->tmsp_id)
                  ->where('eob_obid',$rs->os_id)
                  ->count(); ?>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                     <div class="demoo1">
                        <input type="checkbox" name="social[]" id="social-<?php echo $rs->os_id?>" @if($social!=0) checked="checked" @endif value="<?php echo $rs->os_id?>" >
                        <label for="social-<?php echo $rs->os_id?>"><span></span><?php echo mb_strtoupper($rs->os_nomp);?></label>
                     </div>
                  </div>

                  @endif

                  @endforeach

                  @if($e==0)

                  <div class="form-group">
                     <div class="col-md-12" style="text-align: center;">
                        <img src="<?php echo url('/');?>/uploads/icons/noresult.png" alt="No hay resultados" class="img-not-res">
                        <p>Tu empresa no tiene obras sociales registradas para seleccionar <a href="<?php echo url('empresa/obras-sociales/'.$get_business->em_id);?>">Registrá una ahora</a></p>
                     </div>
                  </div>


                  @endif


               </div>
                @if($e!=0)
               <div class="form-group">
                     <br>
                   <button type="button" onclick="update_work()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                </div>
                @endif
            </form>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.lenders')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" id="select_branch" value="<?php echo $get_branch->suc_id;?>">
<input type="hidden" id="select_lender" value="<?php echo $get_lender->tmsp_id;?>">
<?php echo Html::script('frontend/js/settings_lenders.js?v='.time())?>
@stop
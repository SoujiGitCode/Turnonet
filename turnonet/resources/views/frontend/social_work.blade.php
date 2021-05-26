@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Obras sociales</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Seleccioná la obra social en la que aplica tu empresa
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" id="id" name="id" value="<?php echo $get_business->em_id;?>">
               <div class="row">
                  @foreach ($social_works as $rs)

                  <?php $social=DB::table('tu_emps_ob')
                  ->where('eob_empid',$get_business->em_id)
                  ->where('eob_presid','0')
                  ->where('eob_sucid','0')
                  ->where('eob_obid',$rs->os_id)
                  ->count(); ?>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                     <div class="demoo1">
                        <input type="checkbox" name="social[]" id="social-<?php echo $rs->os_id?>" @if($social!=0) checked="checked" @endif value="<?php echo $rs->os_id?>" >
                        <label for="social-<?php echo $rs->os_id?>"><span></span><?php echo mb_strtoupper($rs->os_nomp);?></label>
                     </div>
                  </div>

                  @endforeach
               </div>
               <div class="form-group">
                     <br>
                   <button type="button" onclick="update_work()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                </div>
            </form>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.business')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
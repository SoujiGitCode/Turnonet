@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Turnonet en mi website</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Código para su sitio
            </div>
            <div class="panel-body">
               <p>En caso de contar con su propio sitio web puede copiar este codigo en la sección del sitio que usted desee. Una vez realizado este, sus clientes podran tomar turnos desde su sitio. En caso de tener alguna consulta, no dude en contacterse con nosotros!</p>
              <div class="form-group btop" >
                  <label class="label-1">LINK DIRECTO / POPUP:</label>
                  <pre><code><?php echo "https://www.turnonet.com/e/esn/".$get_business->em_id."/".substr($get_business->em_valcod,0,4);?></code></pre>
               </div>
               <div class="form-group btop" >
                  <label class="label-1">CÓDIGO HTML5:</label>
                  <pre><code><?php echo '&lt;object type="text/html" data="https://www.turnonet.com/e/esn/'.$get_business->em_id.'/'.substr($get_business->em_valcod,0,4).'" width="100%" height="600"&gt;&lt;/object&gt;';?></code></pre>
               </div>
               <?php $speciality=DB::table('tu_emps_serv')->where('serv_empid',$get_business->em_id)->where('serv_tipo','2')->where('serv_estado','1')->get(); ?>

               @if(count($speciality)!=0)
               <br>
               <button class="btn btn-success" type="button" onclick="window.location='<?php echo url('empresa/frame/especialidad/'.$get_business->em_id);?>'">Código por especialidad</button>
               @endif
                
               <button class="btn btn-success" type="button" onclick="window.location='<?php echo url('empresa/frame/theme/'.$get_business->em_id);?>'" style="background:#FF5722!important;border:1px solid #FF5722!important;"><i class="fa fa-paint-brush" aria-hidden="true"></i> Personalizá tu Frame</button>
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
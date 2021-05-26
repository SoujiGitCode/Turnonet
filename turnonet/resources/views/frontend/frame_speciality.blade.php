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
               <?php $i=0;?>
               @foreach($speciality as $rs)
               <?php $i=$i+1;?>
               <div class="form-group btop" >
                  <label class="label-1">LINK DIRECTO / POPUP <?php echo $rs->serv_nom;?>:</label>
                  <pre><code><?php echo "https://www.turnonet.com/e/esn/esp/".$get_business->em_id."/".$rs->serv_id;?></code></pre>
               </div>
               <div class="form-group btop" >
                  <label class="label-1">CÓDIGO HTML5 <?php echo $rs->serv_nom;?>:</label>
                  <pre><code><?php echo '&lt;object type="text/html" data="https://www.turnonet.com/e/esn/esp/'.$get_business->em_id.'/'.$rs->serv_id.'" width="100%" height="600"&gt;&lt;/object&gt;';?></code></pre>
               </div>
               @if($i!=count($speciality))
               <hr>
               @endif
               @endforeach
               
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
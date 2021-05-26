<?php  $frame = DB::table('frame')->where('emp_id', $get_business->em_id)->first(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta name="mobile-web-app-capable" content="yes">
 <meta name="apple-mobile-web-app-status-bar-style" content="black">
 <meta name="theme-color" content="<?php echo $frame->color_1;?>" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
 <meta name="description" content="<?php echo $frame->description;?>">
 <meta property="og:url" content="<?php echo url('/').$_SERVER['REQUEST_URI'];?>" />
 <meta property="og:type" content="article" />
 <meta property="og:description" content="<?php echo $frame->description;?>"/>
 <meta name="keywords" content="<?php echo $frame->keywords;?>">
 <meta property="og:image" content="<?php echo url('/');?>/uploads/icons/icono.png">
 <meta property="og:image:alt" content="<?php echo $frame->title;?>" />
 <title><?php echo $frame->title?></title>
 <!-- Favicon -->
 <link rel="shortcut icon" type="image/x-icon" href="<?php echo url('/')?>/uploads/icons/favicon.png">


 <style type="text/css">

  <?php $font_1=DB::table('fonts')->where('id',$frame->font_1)->first(); ?>
  <?php $font_2=DB::table('fonts')->where('id',$frame->font_2)->first(); ?>
  <?php $font_3=DB::table('fonts')->where('id',$frame->font_3)->first(); ?>
     
@font-face {
    font-family: 'Montserrat Black';
    src: url(<?php echo url('/');?>/frame/<?php echo $font_3->url;?>);
    font-display: block;
}

@font-face {
    font-family: 'Montserrat Semi-Bold';
    src: url(<?php echo url('/');?>/frame/<?php echo $font_2->url;?>);
    font-display: block;
}

@font-face {
    font-family: 'Montserrat-Regular';
    src: url(<?php echo url('/');?>/frame/<?php echo $font_1->url;?>);
    font-display: block;
}
 </style>
 <!-- BOOTSTRAP -->
 <?php echo Html::style('frame/css/bootstrap.min.css?v='.time())?>
 <!-- FONT AWESOME -->
 <?php echo Html::style('frame/css/font-awesome.css')?>
 <!-- ALERTS CSS -->
 <?php echo Html::style('frame/css/sweetalert.css')?>
 <?php echo Html::style('frame/css/jquery.growl.css?v='.time())?>
 <!-- CUSTOM CSS -->
 <?php echo Html::style('frame/css/styles.css?v='.time())?>
 <!--Jquery-->
 <?php echo Html::script('frontend/js/jquery.min.js?v='.time())?>

 <!--Boostrap-->
 <?php echo Html::script('backend/js/angular.min.js')?>
 <?php echo Html::script('frontend/js/bootstrap_1.min.js?v='.time())?>
 <?php echo Html::script('backend/js/ui-bootstrap-tpls.min.js')?>


 <style type="text/css">
 body,.xsletter-1,.subtit,label,.form-control,.close,.inforeq b,.text-date-2,.btn-net,.inforeq span,.turin,.text-date,#addeds span {color: <?php echo $frame->color_2;?>!important; } ::-webkit-input-placeholder {color: <?php echo $frame->color_2;?>; } :-ms-input-placeholder {color: <?php echo $frame->color_2;?>; } ::placeholder {color: <?php echo $frame->color_2;?>; } #s_cal .s_day{background-color: <?php echo $frame->color_2;?>; } #s_cal .s_sd,#addeds span{background-color: <?php echo $frame->color_8;?>; } #s_cal .cal_nodia,#s_cal .cal_dia,#s_cal .cal_nodianh,.preloader,.lodcal{background-color: <?php echo $frame->color_7;?>; } .double-bounce2,.double-bounce1{background-color: <?php echo $frame->color_1;?> } .tit,.modal-header h3,.xsletter-33,.ee0{color: <?php echo $frame->color_1;?>!important; } .busqueda,.dispobutton,.volver2{background: <?php echo $frame->color_1;?>;border: 1px solid <?php echo $frame->color_1;?>; } .busqueda:hover,.dispobutton:hover,.volver2:hover,.btn-default-1:hover{background: <?php echo $frame->color_2;?>!important;border: 1px solid <?php echo $frame->color_2;?>!important } .#s_cal .s_day{background-color: <?php echo $frame->color_2;?>; } .circlegreen,.circlegreen:hover{border: 3px solid <?php echo $frame->color_5;?>; background-color: <?php echo $frame->color_5;?>; } .close:focus, .close:hover{color: <?php echo $frame->color_2;?>; } .times .hora a {color: <?php echo $frame->color_2;?>!important; border: 1px solid <?php echo $frame->color_2;?>; } .times .hora a:hover {background-color: <?php echo $frame->color_5;?>!important; border: 1px solid <?php echo $frame->color_5;?>!important; } .btn-default-1 {border: 1px solid <?php echo $frame->color_2;?>!important; color: <?php echo $frame->color_2;?>!important; } .check-i,.nom,.tit-1{color:<?php echo $frame->color_3;?>!important; } .btn-default {background-color: <?php echo $frame->color_3;?>!important; border: 1px solid <?php echo $frame->color_3;?>!important; } .btn-default:hover{background-color: <?php echo $frame->color_1;?>!important; border: 1px solid <?php echo $frame->color_1;?>!important; } [type="radio"]:checked+label:after{background: <?php echo $frame->color_1;?>!important; }.circlepink{ border: 3px solid <?php echo $frame->color_4;?>; } body {background-color: <?php echo $frame->color_9;?>; }.header-top-bar {background: <?php echo $frame->color_9;?>; }

.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
background-color: <?php echo $frame->color_1;?>;
    border-color: <?php echo $frame->color_1;?>;
}
.pagination>li>a, .pagination>li>span{
color:<?php echo $frame->color_1;?>;
}
.pagination>li>a:focus, .pagination>li>a:hover, .pagination>li>span:focus, .pagination>li>span:hover{
color:<?php echo $frame->color_2;?>;
}
  <?php echo $frame->style;?> </style> </head> 

  <body class="bg-body">
  <div class="preloader bg-2" id="mask">
   <div class="container mtop2 bg-preload">
     <div class="row">
       <div class="col-md-12 content-spinner">
         <div class="spinner">
           <div class="double-bounce1"></div>
           <div class="double-bounce2"></div>
         </div>
       </div>
     </div>
   </div>
 </div>
 <div class="wrapper">

  @if($frame->header==0)
  <header class="header-area header-wrapper hidden-lg hidden-md hidden-sm">
    <div class="header-top-bar">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="logo">
              <?php
              $image=url('/').'/img/emptylogo.png';
              
              if($get_business->em_pfot!=""){
                $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
              }    
              ?>
              <img src="<?php echo $image;?>">
            </div>
          </div>

        </div>
      </div>
    </div>
  </header>
  @endif
  <!-- start content -->
  @yield('content')

  
</div>
@if($frame->marca==0)
<div class="container sect-logo">
  <div class="row">

    <div class="col-md-4 hidden-xs"></div>
    <div class="col-md-4 col-sm-4 col-xs-12 text-center">
     <img src="<?php echo url('/').'/uploads/icons/logo_gris.png';?>" class="img-ff" alt="Turnonet">
   </div>
   <div class="col-md-4 hidden-xs"></div>

 </div>
</div>
@endif
@if($frame->footer==0)
<!--end content -->
<section id="bottom-bar">
 <div class="container">
  <div class="row">
   <!-- .copyright -->
   <div class="col-md-12 col-sm-12 col-xs-12 text-center">
    <p>v4.0 Turnonet © 2011-<?php echo date("Y")?> Todos los derechos reservados</p>
  </div>
  
</div>
</div>
</section>
@endif
<!--Boostrap-->
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frame/js/sweetalert.min.js')?>
<?php echo Html::script('frame/js/jquery.growl.js')?>
<!--Theme-->
<?php echo Html::script('frame/js/script.js?v='.time())?>

</body>
</html>
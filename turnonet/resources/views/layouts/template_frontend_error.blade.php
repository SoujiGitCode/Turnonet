<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="UTF-8">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <meta name="theme-color" content="#f05a24" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
      <link rel="manifest" href="<?php echo url('/');?>/frontend/js/manifest.json">
      <title>Turnonet</title>

      <!-- Favicon -->
      <link rel="shortcut icon" type="image/x-icon" href="<?php echo url('/')?>/uploads/icons/favicon.png">
      <link rel="apple-touch-icon" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon.png" />
      <link rel="apple-touch-icon" sizes="57x57" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon-57x57.png" />
      <link rel="apple-touch-icon" sizes="72x72" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon-72x72.png" />
      <link rel="apple-touch-icon" sizes="76x76" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon-76x76.png" />
      <link rel="apple-touch-icon" sizes="114x114" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon-114x114.png" />
      <link rel="apple-touch-icon" sizes="120x120" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon-120x120.png" />
      <link rel="apple-touch-icon" sizes="144x144" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon-144x144.png" />
      <link rel="apple-touch-icon" sizes="152x152" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon-152x152.png" />
      <link rel="apple-touch-icon" sizes="180x180" href="<?php echo url('/')?>/uploads/icons/apple-touch-icon-180x180.png" />
      <link rel="apple-touch-startup-image" href="<?php echo url('/')?>/uploads/splash/launch-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
      <link rel="apple-touch-startup-image" href="<?php echo url('/')?>/uploads/splash/launch-750x1294.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
      <link rel="apple-touch-startup-image" href="<?php echo url('/')?>/uploads/splash/launch-1242x2148.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
      <link rel="apple-touch-startup-image" href="<?php echo url('/')?>/uploads/splash/launch-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
      <link rel="apple-touch-startup-image" href="<?php echo url('/')?>/uploads/splash/launch-1536x2048.png" media="(min-device-width: 768px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">
      <link rel="apple-touch-startup-image" href="<?php echo url('/')?>/uploads/splash/launch-1668x2224.png" media="(min-device-width: 834px) and (max-device-width: 834px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">
      <link rel="apple-touch-startup-image" href="<?php echo url('/')?>/uploads/splash/launch-2048x2732.png" media="(min-device-width: 1024px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">
      <!-- BOOTSTRAP -->
      <?php echo Html::style('frontend/css/bootstrap.min.css?v='.time())?>
      <!-- FONT AWESOME -->
      <?php echo Html::style('frontend/css/font-awesome.css')?>
      <!-- ALERTS CSS -->
      <?php echo Html::style('frontend/css/sweetalert.css')?>
      <?php echo Html::style('frontend/css/jquery.growl.css?v='.time())?>
      <!-- CUSTOM CSS -->
      <?php echo Html::style('frontend/css/styles.css?v='.time())?>
      <!--Jquery-->
      <?php echo Html::script('frontend/js/jquery-2.1.1.min.js')?>
      <!--Boostrap-->
      <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
      <!--Materialize-->
      <?php echo Html::script('frontend/js/materialize.js')?>
      
   </head>
   <body>

      <!-- start content -->
      @yield('content')

      <!--end content -->

      
      

      <!--Boostrap-->
      <input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
      <?php echo Html::script('frontend/js/sweetalert.min.js')?>
      <?php echo Html::script('frontend/js/jquery.growl.js')?>
      <!--Theme-->
      <?php echo Html::script('frontend/js/script.js?v='.time())?>
   </body>
</html>
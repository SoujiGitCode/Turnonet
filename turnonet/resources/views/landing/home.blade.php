<?php $site = DB::table('site')->where('id', '1')->first();  ?> 
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="theme-color" content="#f05a24"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
      <link rel="manifest" href="<?php echo url('/');?>/frontend/js/manifest.json">
      <meta name="description" content="<?php echo $site->description;?>">
      <meta name="keywords" content="<?php echo $site->keywords;?>">
      <meta property="og:url" content="<?php echo url('/');?>/en/" />
      <meta property="og:type" content="article" />
      <meta property="og:title" content="Turnonet" />
      <meta property="og:description" content="<?php echo $site->description;?>"/>
      <meta property="og:image" content="<?php echo url('/');?>/uploads/icons/favicon.png">
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
      <link async media="all" type="text/css" rel="stylesheet" href="<?php echo url('/');?>/backend/css/font-awesome.css">
      <link async media="all" type="text/css" rel="stylesheet" href="<?php echo url('/');?>/landing/css/jquery.growl.css?v=<?php echo time();?>">
      <link async media="all" type="text/css" rel="stylesheet" href="<?php echo url('/');?>/landing/css/owl.carousel.css">
      <link async media="all" type="text/css" rel="stylesheet" href="<?php echo url('/');?>/landing/css/sweetalert.css">
      <link media="all" type="text/css" rel="stylesheet" href="<?php echo url('/');?>/landing/css/animate.css">
      <link media="all" type="text/css" rel="stylesheet" href="<?php echo url('/');?>/landing/css/bootstrap.min.css?v=<?php echo time();?>">
      <link media="all" type="text/css" rel="stylesheet" href="<?php echo url('/');?>/landing/css/style.css?v=<?php echo time();?>">
      <script src="https://www.google.com/recaptcha/api.js?render=<?php echo env('CAPTCHA_KEY_SITE');?>"></script>
      <script src="<?php echo url('/');?>/landing/js/jquery-2.1.1.min.js"></script>
      <script src="<?php echo url('/');?>/landing/js/bootstrap.min.js?v=<?php echo time();?>"></script>
      <script src="<?php echo url('/');?>/landing/js/wow.min.js"></script>
      <title><?php echo ucwords ($site->name)?></title>
       <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NW88G63');</script>
    <!-- End Google Tag Manager -->
   </head>
   <body>
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NW88G63"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <div class="wrapper">
         <section class="hidden-lg hidden-md hidden-sm" style="    background: #4c4b4b;">
            <div class="container">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                     <a class="btn button-login" href="<?php echo url('iniciar-sesion');?>">Ingresar</a>
                  </div>
               </div>
            </div>
         </section>
         <header class="header-area header-wrapper" id="sticky-header">
            <div class="header-top-bar">
               <div class="container">
                  <div class="row">
                     <div class="col-md-3 col-sm-3 col-xs-12">
                        <span id="btn-toogle" class="btn-toogle"></span>
                        <div class="logo">
                           <a href="{{url('/')}}">
                           <img src="{{url('/')}}/uploads/{{$site->image_1}}" alt="{{$site->name}}">
                           </a>
                        </div>
                     </div>
                     <div class="col-md-12 top-mmn" id="content-sidenav" style="display: none;" >
                        <ul class="main-menu">
                           <?php   $menu=DB::table('menus')->orderBy('position', 'asc')->get(); ?>
                           @foreach($menu as $rs)
                           <li class="li-nav" id="nav-m<?php echo $rs->id;?>"><a href="<?php echo $rs->url;?>"><?php echo $rs->title;?></a></li>
                           @endforeach
                        </ul>
                     </div>
                     <div class="col-md-9 col-sm-9 hidden-xs">
                        <ul class="main-menu">
                           <?php   $menu=DB::table('menus')->orderBy('position', 'asc')->get(); ?>
                           @foreach($menu as $rs)
                           <li class="li-nav" id="nav-<?php echo $rs->id;?>"><a href="<?php echo $rs->url;?>"><?php echo $rs->title;?></a></li>
                           @endforeach
                           <li><a class="btn button-login" href="<?php echo url('iniciar-sesion');?>">Ingresar</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </header>
      </div>
      <?php  
         $banner = DB::table('banners')->where('id', '1')->first(); 
         if($banner->video!="" && strpos($banner->video, '=') !== false){
               $video=explode("=",$banner->video);
               $video=$video[1];
            }
            ?>
      <section class="banner-video" id="home" data-video="<?php echo $video;?>" >
         <div class="content-video">
            <div class="container">
               <div class="area-title">
                  <h2  class="wow bold fadeInUp w-text" data-wow-delay="0.2s"><?php echo  $banner->title;?></h2>
                  <p class="lead wow bold fadeInUp w-text" data-wow-delay="0.3s"><?php echo $banner->subtitle ;?></p>
                  @if($banner->name_boton!="")
                  <a onclick="openRegister()" class="btn button-register wow bold fadeInUp w-text" data-wow-delay="0.4s"><?php echo $banner->name_boton;?></a>
                  @endif
               </div>
            </div>
         </div>
      </section>
      <?php $section = DB::table('sections')->where('id', '1')->first(); ?>
      @if($section->status==1)
      <div class="section-1">
         <div class="container">
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  @if($section->status_content==1)
                  <h2><?php echo $section->title;?></h2>
                  <?php echo $section->content;?>
                  @endif
                  @if($section->name_boton!="" )
                  <a class="btn btn-s1"  onclick="openModal('<?php echo $section->url_boton;?>')"><i class="fa fa-play-circle-o" aria-hidden="true"></i> <?php echo $section->name_boton;?></a>
                  @endif
                  @if($section->name_boton_en!="" )
                  <br>
                  <a class="btn btn-s2" onclick="openRegister()"><?php echo $section->name_boton_en;?></a>
                  @endif
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                  @if($section->image!="")
                  <div class="owl-carousel owl-theme" id="owl-carousel-2">
                     @for ($i=1;$i<=substr_count($section->image, ',');$i++)
                     <?php  $image= explode(',',$section->image);  ?>
                     <div class="item text-center">
                        <figure>
                           <img src="<?php echo url('/')?>/uploads/<?php echo $image[$i];?>" alt="<?php echo $section->title;?>" style="width: 100%; margin: 0 auto">
                        </figure>
                     </div>
                     @endfor
                  </div>
                  @endif
               </div>
            </div>
         </div>
      </div>
      @endif
      <?php $section = DB::table('sections')->where('id', '2')->first(); ?>
      @if($section->status==1)
      <section class="section-2 btop" id="funcionamiento">

         @if($section->status_content==1)
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title-header-2">
                     <h2><?php echo $section->title;?></h2>
                  </div>
               </div>
                <?php $i=0;?>
               @foreach($services as $rs)
               @if($rs->image!="")
               <?php $i=$i+1;?>
               <div class="col-md-3 col-sm-4" @if($i==5 || $i==9 ) style="clear: both;" @endif>
                  <div class="servv-wid">
                     <div class="img-servv">
                        <?php
                           $image=explode(",",$rs->image);
                           $image=$image[1];
                           ?>
                            <div class="mmicon  icc<?php echo $rs->id;?>" style="background-image: url('<?php echo url('/')?>/uploads/<?php echo $image;?>');"></div>
                   
                     </div>
                     <div class="content-servv">
                        <h3><?php echo $rs->title;?></h3>
                     </div>
                  </div>
               </div>
               @endif
               @endforeach
               <div class="col-md-12 text-center">
                  <?php echo $section->content;?>
                  @if($section->image!="")
                  <?php
                     $image=explode(",",$section->image);
                     $image=$image[1];
                     ?>
                  <img src="<?php echo url('/')?>/uploads/<?php echo $image;?>" class="about-i" alt="<?php echo $site->name;?>">
                  @endif
               </div>
            </div>
            @endif
         </div>
      </section>
      @endif
      <div class="section-5">
         <div class="container">
            <div class="row">
               <div class="col-md-6 col-sm-6">
                  <div class="owl-carousel owl-theme" id="owl-carousel-3">
                     <?php $section = DB::table('sections')->where('id', '8')->first(); ?>
                     @if($section->status==1)
                     <div class="item">
                        <div class="row">
                           @if($section->status_content==1)
                           <div class="col-md-12">
                              <div class="title-header-3">
                                 <h2><?php echo $section->title;?></h2>
                                 <?php echo $section->content;?>
                                 <div class="col-md-12 text-center">
                                    @if($section->image!="")
                                    <?php
                                    $image=explode(",",$section->image);
                                    $image=$image[1];
                                    ?>
                                    <img src="<?php echo url('/')?>/uploads/<?php echo $image;?>" class="about-i" alt="<?php echo $site->name;?>">
                                    @endif
                                 </div>
                              </div>
                           </div>
                           @endif
                        </div>
                     </div>
                     @endif
                     <?php $section = DB::table('sections')->where('id', '9')->first(); ?>
                     @if($section->status==1)
                     <div class="item">
                        <div class="row">
                           @if($section->status_content==1)
                           <div class="col-md-12">
                              <div class="title-header-3">
                                 <h2 style="color: #3EAF23!important"><?php echo $section->title;?></h2>
                                 <?php echo $section->content;?>
                                 <div class="col-md-12 text-center">
                                    @if($section->image!="")
                                    <?php
                                    $image=explode(",",$section->image);
                                    $image=$image[1];
                                    ?>
                                    <img src="<?php echo url('/')?>/uploads/<?php echo $image;?>" class="about-i" alt="<?php echo $site->name;?>">
                                    @endif
                                 </div>
                              </div>
                           </div>
                           @endif
                        </div>
                     </div>
                     @endif
                     <?php $section = DB::table('sections')->where('id', '10')->first(); ?>
                     @if($section->status==1)
                     <div class="item">
                        <div class="row">
                           @if($section->status_content==1)
                           <div class="col-md-12">
                              <div class="title-header-3">
                                 <h2 style="color: #3EAF23!important"><?php echo $section->title;?></h2>
                                 <?php echo $section->content;?>
                                 <div class="col-md-12 text-center">
                                    @if($section->image!="")
                                    <?php
                                    $image=explode(",",$section->image);
                                    $image=$image[1];
                                    ?>
                                    <img src="<?php echo url('/')?>/uploads/<?php echo $image;?>" class="about-i" alt="<?php echo $site->name;?>">
                                    @endif
                                 </div>
                              </div>
                           </div>
                           @endif
                        </div>
                     </div>
                     @endif
                  </div>
               </div>
               <div class="col-md-6 col-sm-6"></div>
               <div class="col-md-12 text-center bg-tt">

                  <a class="btn btn-s2" onclick="openRegister()">Registrate</a>
               </div>
            </div>
         </div>
      </div>
      <?php $section = DB::table('sections')->where('id', '3')->first(); ?>
      @if($section->status==1)
      <div class="section-3" id="caracteristicas">
         <div class="container">
            <div class="row">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  @if($section->status_content==1)
                  <h2><?php echo $section->title;?></h2>
                  <?php echo $section->content;?>
                  @endif
               </div>
            </div>
            <div class="row">
               <?php $i=0;?>
               @foreach($widgets as $rs)
               @if($rs->image!="")
               <?php $i=$i+1;?>
               <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class=" card thumbnail wow bold fadeInUp w-text" data-wow-delay="0.<?php echo $i;?>s">
                     <div class="thumbnail-image">
                        <?php    $image=explode(",",$rs->image) ?>
                        <img src="<?php echo url('/');?>/uploads/<?php echo $image[1];?>" alt="<?php echo $rs->title  ;?>">
                     </div>
                     <div class="transparencia caption">
                        <h3><?php echo $rs->title  ;?></h3>
                        <?php echo $rs->content  ;?>
                     </div>
                  </div>
               </div>
               @endif
               @endforeach
            </div>
         </div>
      </div>
      @endif
      
      <?php $section = DB::table('sections')->where('id', '4')->first(); ?>
      @if($section->status==1)
      <section class="section-4" id="precios">
         @if($section->status_content==1)
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title-header-3">
                     <h2><?php echo $section->title;?></h2>
                  </div>
               </div>
               <div class="col-md-3 col-sm-3 hidden-xs no-padding">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <div class="plan plan-0">
                        <div class="col-md-12">
                           <div class="form-group">
                              <small class="txt-s">Ver precios en USD</small>
                              <label class="switch-wrap">
                                 <input type="checkbox" name="usd" id="usd" value="1" onchange="setPrices()">
                                 <div class="switch"></div>
                              </label>
                           </div>
                        </div>
                        <div class="detail-plan">
                           <div class="header-plan">
                              30
                              Días de prueba
                              todos los planes
                           </div>
                           <ul>
                              <li>Configuración de la cuenta</li>
                              <li>Cant. de Clientes</li>
                              <li>Cant. Turnos por Mes</li>
                              <li>Agendas por cuenta</li>
                              <li>Turnos Simultáneos</li>
                              <li>Turnos Online 7x24</li>
                              <li>Alertas por SMS</li>
                              <li>Alertas por Email</li>
                              <li>Reportes e Informes</li>
                              <li>Soporte por Email</li>
                              <li>Soporte Telefónico</li>
                              <li>Sitio web</li>
                              <li>Pantalla Responsiva para Usuarios</li>
                              <li>App Mobile (para administradores)</li>
                              <li>Upgrades</li>
                              <li>Más turnos por cuenta</li>
                              <li>Billetera Digital (Mercado Pago)</li>
                              <li>Integraciónes</li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-9 col-sm-9">
                  <div class="row panel-plans">
                     <div class="col-md-12 hidden-lg hidden-md hidden-sm">
                        <div class="form-group">
                           <small class="txt-s">Ver precios en USD</small>
                           <label class="switch-wrap">
                              <input type="checkbox" name="usd1" id="usd1" value="1" onchange="setPrices1()">
                              <div class="switch"></div>
                           </label>
                        </div>
                     </div>
                     <h3 id="title-plan">Los precios están expresados en ARS.</h3>
                     <?php $i=0;?>
                     @foreach($plans as $rs)
                     <?php $i=$i+1;?>
                     <div class="hidden-lg hidden-md hidden-sm col-xs-6" style="clear: both;">
                        <div class="plan plan-0">
                           <h3>&nbsp;</h3>
                           <div class="detail-plan">
                              <div class="header-plan">
                                 30
                                 Días de prueba
                                 todos los planes
                              </div>
                              <ul>
                                 <li>Configuración de la cuenta</li>
                                 <li>Cant. de Clientes</li>
                                 <li>Cant. Turnos por Mes</li>
                                 <li>Agendas por cuenta</li>
                                 <li>Turnos Simultáneos</li>
                                 <li>Turnos Online 7x24</li>
                                 <li>Alertas por SMS</li>
                                 <li>Alertas por Email</li>
                                 <li>Reportes e Informes</li>
                                 <li>Soporte por Email</li>
                                 <li>Soporte Telefónico</li>
                                 <li>Sitio web</li>
                                 <li>Pantalla Responsiva para Usuarios</li>
                                 <li>App Mobile (para administradores)</li>
                                 <li>Upgrades</li>
                                 <li>Más turnos por cuenta</li>
                                 <li>Billetera Digital (Mercado Pago)</li>
                                 <li>Integraciónes</li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="plan plan-<?php echo $i;?>">
                           <div class="detail-plan">
                              <div class="header-plan">
                                 <h3><?php echo $rs->title;?></h3>
                                 <div class="par"><?php echo $rs->price;?></div>
                                 <div class="pusd" style="display: none;">
                                    <?php echo $rs->price_usd;?>
                                 </div>
                              </div>
                              <ul>
                                 <li><?php echo $rs->item_1;?></li>
                                 <li><?php echo $rs->item_2;?></li>
                                 <li><?php echo $rs->item_3;?></li>
                                 <li><?php echo $rs->item_4;?></li>
                                 <li><?php echo $rs->item_5;?></li>
                                 <li>
                                    <?php 
                                       if($rs->item_6!="Si" && $rs->item_6!="No"){
                                       
                                          echo $rs->item_6;
                                       }
                                       if($rs->item_6=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_7!="Si" && $rs->item_7!="No"){
                                       
                                          echo $rs->item_7;
                                       }
                                       if($rs->item_7=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_8!="Si" && $rs->item_8!="No"){
                                       
                                          echo $rs->item_8;
                                       }
                                       if($rs->item_8=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_9!="Si" && $rs->item_9!="No"){
                                       
                                          echo $rs->item_9;
                                       }
                                       if($rs->item_9=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_10!="Si" && $rs->item_10!="No"){
                                       
                                          echo $rs->item_10;
                                       }
                                       if($rs->item_10=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_11!="Si" && $rs->item_11!="No"){
                                       
                                          echo $rs->item_11;
                                       }
                                       if($rs->item_11=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_12!="Si" && $rs->item_12!="No"){
                                       
                                          echo $rs->item_12;
                                       }
                                       if($rs->item_12=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_13!="Si" && $rs->item_13!="No"){
                                       
                                          echo $rs->item_13;
                                       }
                                       if($rs->item_13=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_14!="Si" && $rs->item_14!="No"){
                                       
                                          echo $rs->item_14;
                                       }
                                       if($rs->item_14=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_15!="Si" && $rs->item_15!="No"){
                                       
                                          echo $rs->item_15;
                                       }
                                       if($rs->item_15=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_16!="Si" && $rs->item_16!="No"){
                                       
                                          echo $rs->item_16;
                                       }
                                       if($rs->item_16=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_17!="Si" && $rs->item_17!="No"){
                                       
                                          echo $rs->item_17;
                                       }
                                       if($rs->item_17=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                                 <li>
                                    <?php 
                                       if($rs->item_18!="Si" && $rs->item_18!="No"){
                                       
                                          echo $rs->item_18;
                                       }
                                       if($rs->item_18=="Si"){
                                       
                                          echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                       }
                                       
                                       ;?>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     <div class="col-md-12 txt-p" style="clear: both;">
                        <?php echo $section->content  ;?>
                     </div>
                  </div>
               </div>
            </div>
            @endif
         </div>
      </section>
      @endif
      <?php $section = DB::table('sections')->where('id', '5')->first(); ?>
      @if($section->status==1)
      <section class="section-faq btop" id="faq">
         @if($section->status_content==1)
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title-header-1">
                     <h2><?php echo $section->title;?></h2>
                  </div>
               </div>
            </div>
         </div>
         @endif
         <div class="container">
            <div class="row">
               <div class="col-md-4 col-sm-4 clearfix hidden-xs no-padding">
                  <nav>
                     <ul class="page-sidebar" style="width: 100%">
                        <?php $i=0; ?>
                        @foreach($faqs as $rs)
                        <?php $i=$i+1; ?>
                        <li @if($i==1) class="opt-faq active" @else class="opt-faq" @endif  id="opt-<?php echo $rs->url;?>">
                        <a data-toggle="tab" onclick="ShowTabs_1('<?php echo $rs->url;?>')">
                           <div class="numq"><?php echo $i;?></div>
                           <div class="div-question">
                              <?php  echo $rs->title  ;?>
                           </div>
                        </a>
                        </li>
                        @endforeach
                     </ul>
                  </nav>
               </div>
               <div class="col-md-12 clearfix hidden-lg hidden-md hidden-sm"  >
                  <select class="form-control" onchange="ShowTabs()" id="category" >
                     @foreach($faqs as $rs)
                     <option value="<?php echo $rs->url;?>"><?php echo $rs->title;?></option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-8  col-sm-8 col-xs-12 no-padding">
                  <div class="tab-content">
                     <?php $i=0; ?>
                     @foreach($faqs as $rs)
                     <?php $i=$i+1; ?>
                     <div id="<?php echo $rs->url ;?>" class="tab-pane fade @if($i==1) in active @endif">
                        @if($rs->image!="")
                        <div class="img-q">
                           <?php    $image=explode(",",$rs->image) ?>
                           <div class="ttm-play-icon-btn mb-35">
                              <div class="ttm-play-icon-animation" onclick="openModal('<?php echo $rs->video;?>')">
                                 <a>
                                    <div class="ttm-icon ttm-icon_element-bgcolor-skincolor ttm-icon_element-size-sm ttm-icon_element-style-round">
                                       <i class="fa fa-play"></i>
                                    </div>
                                 </a>
                              </div>
                           </div>
                           <img src="<?php echo url('/');?>/uploads/<?php echo $image[1];?>" alt="<?php echo $rs->title  ;?>">
                        </div>
                        @endif
                        <div class="resp-q">
                           <?php echo $rs->content;?>
                        </div>
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </section>
      @endif
      <?php $section = DB::table('sections')->where('id', '6')->first(); ?>
      @if($section->status==1)
      @if($section->status_content==1)
      <section class="title-porfolio">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title-header">
                     <h2><?php echo $section->title;?></h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      @endif
      <section class="porfolio btop">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="owl-carousel owl-theme" id="owl-carousel-1">
                     @foreach($customers as $rs)
                     @if($rs->image!="")
                     <div class="item text-center">
                        <figure>
                           <img src="<?php echo url('/')?>/uploads/<?php echo $rs->image;?>" alt="<?php echo $rs->name;?>" style="width: 80%; margin: 0 auto">
                        </figure>
                     </div>
                     @endif
                     @endforeach
                  </div>
               </div>
            </div>
         </div>
      </section>
      @endif
      <?php $section = DB::table('sections')->where('id', '7')->first(); ?>
      @if($section->status==1)
      <div class="section-contact" id="contacto">
         <div class="container">
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  @if($section->status_content==1)
                  <h2><?php echo $section->title;?></h2>
                  @endif
                  {!! Form::open(['id'=>'form-1']) !!}
                  <input type="hidden" id="captcha" name="captcha">
                  <div class="form-group">
                     <input type="text" name="name" id="name-1" placeholder="Nombre*" class="form-control" onkeypress="enter_name_1(event)">
                  </div>
                  <div class="form-group">
                     <input type="text" name="email" id="email-1" placeholder="Correo electrónico*" class="form-control" onkeypress="enter_email_1(event)">
                  </div>
                  <div class="form-group">
                     <input type="text" name="subject" id="subject" placeholder="Asunto*" class="form-control" onkeypress="enter_subject(event)">
                  </div>
                  <div class="form-group">
                     <textarea placeholder="Mensaje*" id="message" name="message" class="form-control" cols="5" rows="5" onkeypress="enter_message(event)" ></textarea>
                  </div>
                  <div class="form-group">
                     <a onclick="sendContact()" class="btn button white button-sb-1" id="boton-1" >Enviar</a>   
                  </div>
                  {!! Form::close() !!}
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <?php echo $section->content;?>
               </div>
            </div>
         </div>
      </div>
      @endif
      <footer id="footer" class="footer-area">
         <div class="footer-bottom">
            <div class="container">
               <div class="row">
                  <div class="col-xs-12">
                     <div class="copyright text-center">
                        <p>      <a href="https://www.turnonet.com" target="_blank" style="color: #fff!important"><span >{{$site->name}} © 2011-{{date("Y")}} </span></a></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <div id="myModal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close closed-ab" onclick="closeModal()"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
                  <div class="row">
                     <div class="col-md-12 col-sm-12 col-xs-12 video">
                        <iframe src="" id="iframe-video"  allow="autoplay;"></iframe>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="myModalRegister" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content modal-content-white">
               <div class="modal-header">
                  <h3 id="title-modal">REGÍSTRESE AHORA</h3>
                  <button type="button" class="close closed-ab" onclick="closeModalRegister()"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>
               </div>
               <div class="modal-body modal-body-2">
                  <div class="row">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                        <form id="form-2">
                           <div class="form-group">
                              <label>Nombre completo: <small style="color: #fd2923">*</small></label>
                              <input type="text" name="name" id="name" class="form-control" placeholder="Ingresá tu nombre">
                           </div>
                           <div class="form-group">
                              <label>Correo electrónico: <small style="color: #fd2923">*</small></label>
                              <input type="email" name="email" id="email" class="form-control"  placeholder="Ingresá tu correo electrónico">
                           </div>
                           <div class="form-group">
                              <label>Contraseña <small style="color: #fd2923">*</small></label>
                              <input name="password" type="password" value="" placeholder="Ingresá tu contraseña" id="password"  class="form-control">
                           </div>
                           <div class="form-group">
                              <label>Confirmar contraseña <small style="color: #fd2923">*</small></label>
                              <input name="cpasswordr" type="password" value="" placeholder="Ingresá tu contraseña" id="cpasswordr"   class="form-control">
                              <p class="form-desc">Las contraseñas deben tener al menos 6 caracteres. </p>
                           </div>
                           <div class="form-group">
                              <label style="color: #fd2923!important">Campos requeridos (*)</label>
                           </div>
                           <div class="col-md-3 col-sm-3 hidden-xs">
                           </div>
                           <div class="col-md-6 col-sm-6 col-xs-12 ">
                              <button class="btn btn-s2" name="button" id="boton-2" style="width: 100%" type="button" onclick="guardar()"><i class="fa fa-check-circle" aria-hidden="true"></i> Registrate</button>
                           </div>
                           <div class="col-md-6 col-sm-6 col-xs-12 ">
                           </div>
                     </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <section id="bottom-bar-1">
         <div class="container">
            <div class="row">
            
   <div class="col-md-7 col-sm-7 col-xs-12 pull-left">
         <p>Debido a la cuarentena general dispuesta por el Gobierno Nacional Argentino, sólo brindaremos soporte técnico y comercial mediante correo electrónico. Nos pueden contactar en: <a href="mailto:info@turnonet.com" target="_blank">info@turnonet.com</a></p>
         </div>
    

    <div class="col-md-5 col-sm-5 col-xs-12 pull-right">
         <ul class="menu-footer-1">
            <li><a class="active" onclick="hideWidget()">ACEPTAR</a></li>
            <li><a  onclick="hideWidget()">CERRAR</a></li>
         </ul>
         </div>
  
   </div>
         </div>
      </section>
      <span id="top-button" class="top-button">
      <i class="fa fa-chevron-up"></i>
      </span>
      <input type="hidden" value="<?php echo env('CAPTCHA_KEY_SITE');?>" id="captcha-key">
      <input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
      <script async src="<?php echo url('/');?>/landing/js/sweetalert.min.js"></script>
      <script async src="<?php echo url('/');?>/landing/js/jquery.growl.js"></script>
      <script src="<?php echo url('/');?>/landing/js/owl.carousel.min.js"></script>
      <?php echo Html::script('landing/js/jquery.mb.YTplayer.js')?>
      <script src="<?php echo url('/');?>/landing/js/scripts.js?v=<?php echo time();?>"></script>
   </body>
</html>
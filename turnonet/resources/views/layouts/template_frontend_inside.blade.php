<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="UTF-8">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <meta name="theme-color" content="#f05a24" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
      <meta name="description" content="Una eficiente forma de agendar turnos, citas o reservas 24/7. Nuestro sistema lleva otorgados cientos de miles de turnos online y es la opción preferida de médicos, profesionales y empresas de todas las envergaduras.">
      <meta property="og:url" content="<?php echo url('/').$_SERVER['REQUEST_URI'];?>" />
      <meta property="og:type" content="article" />
      <meta property="og:description" content="Una eficiente forma de agendar turnos, citas o reservas 24/7. Nuestro sistema lleva otorgados cientos de miles de turnos online y es la opción preferida de médicos, profesionales y empresas de todas las envergaduras."/>
      <meta name="keywords" content="Gestión de turnos por internet, Turnos online, e turnos, Plataforma de turnos online, Turnos en tu web">
      <meta property="og:image" content="<?php echo url('/');?>/uploads/icons/icono.png">
      <meta property="og:image:alt" content="Turnonet" />
      <title>Turnonet <?php echo $subtitle?></title>
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
      <?php echo Html::style('frontend/css/materialize.css?v='.time())?>
      <?php echo Html::style('backend/css/bootstrap-datetimepicker.css?v='.time())?>
      <!-- FONT AWESOME -->
      <?php echo Html::style('frontend/css/font-awesome.css')?>
      <!-- ALERTS CSS -->
      <?php echo Html::style('frontend/css/sweetalert.css?v='.time())?>
      <?php echo Html::style('frontend/css/jquery.growl.css?v='.time())?>
      <!-- CUSTOM CSS -->
      <?php echo Html::style('frontend/css/styles.css?v='.time())?>
      <!--Jquery-->
      <?php echo Html::script('frontend/js/jquery.min.js?v='.time())?>
      <!--Materialize-->
      <?php echo Html::script('frontend/js/materialize.js?v='.time())?>
      <!--Boostrap-->
      <?php echo Html::script('backend/js/angular.min.js')?>
      <?php echo Html::script('frontend/js/bootstrap_1.min.js?v='.time())?>
      <?php echo Html::script('backend/js/ui-bootstrap-tpls.min.js')?>
      <?php echo Html::script('backend/js/moment.min.js?v='.time())?>
      <?php echo Html::script('backend/js/es.js?v='.time())?>
      <?php echo Html::script('backend/js/bootstrap-datetimepicker.min.js?v='.time())?>
   </head>
   <body 
   @if($_SERVER["REQUEST_URI"]=="/actualizar-cuenta" || $_SERVER["REQUEST_URI"]=="/actualizar-clave" || $_SERVER["REQUEST_URI"]=="/soporte" )
   class="bg-body-1"
   @endif>
   <div class="preloader bg-2" id="mask">
      <div class="container mtop2 bg-preload">
         <div class="row">
            <div class="col-md-12  col-sm-12 col-xs-12 content-spinner">
               <div class="spinner">
                  <div class="double-bounce1"></div>
                  <div class="double-bounce2"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="content-noty" id="content-noty" style="display: none;">
      <div class="panel-noty">
         <?php $notifications=DB::table('tu_notificaciones')->where('us_id',Auth::guard('user')->User()->us_id)->offset(0)->limit(8)->orderby('id','desc')->get(); ?>
         <ul>
            @if(count($notifications)!=0)
            <?php $type_1=DB::table('tu_notificaciones')->where('us_id',Auth::guard('user')->User()->us_id)->where('tipo','1')->get(); ?>
            @if(count($type_1)!=0)
            <li onclick="goToNoty_type()"><span class="green-w book_white"></span> Tienes <?php echo count($type_1);?> turnos solicitados </li>
            @endif
            @foreach ($notifications as $rs)
            @if($rs->tipo !='1')
            <li onclick="goToNoty('<?php echo $rs->id;?>','<?php echo $rs->url;?>')">
               @if($rs->tipo=='2')
               <span class="red-w book_white"></span>
               @endif
               @if($rs->tipo=='3')
               <span class="blue-w warning_white"></span>
               @endif
               <?php echo $rs->description;?>
            </li>
            @endif
            @endforeach
            @else
            <li>No tienes notificaciones en este momento</li>
            @endif
         </ul>
      </div>
   </div>
   <div class="content-sidenav hidden-xs">
      <div class="sidenav-right">
         <div class="menu-box">
            <div class="box-2">
               <ul class="menu-movil">
                  <li @if($_SERVER["REQUEST_URI"]=="/escritorio") class="active" @endif onclick="window.location='<?php echo url('escritorio');?>'">
                  <a href="<?php echo url('escritorio');?>" title="Ver Escritorio">
                     <div class="nav-icon-3 dashboard"></div>
                     Escritorio
                  </a>
                  </li>
                  <li @if($_SERVER["REQUEST_URI"]=="/agenda" || isset($act_diary)) class="active" @endif onclick="window.location='<?php echo url('agenda');?>'">

                  <a href="<?php echo url('agenda');?>" title="Ver Agenda">
                  <div class="nav-icon-3 bookmark"></div>
                  Agenda</a>
                  </li>
                  <li @if($_SERVER["REQUEST_URI"]=="/empresas" || isset($act_business) ) class="active" @endif  onclick="window.location='<?php echo url('empresas');?>'">
                  <a href="<?php echo url('empresas');?>" title="Ver Empresas">
                     <div class="nav-icon-3 work"></div>
                     Empresas
                  </a>
                  </li>
                  <li @if($_SERVER["REQUEST_URI"]=="/sucursales" || isset($act_branch)) class="active" @endif onclick="window.location='<?php echo url('sucursales');?>'">
                  <a href="<?php echo url('sucursales');?>" title="Ver Sucursales">
                     <div class="nav-icon-3 sucursal"></div>
                     Sucursales
                  </a>
                  </li>
                  <li  @if($_SERVER["REQUEST_URI"]=="/prestadores" || isset($act_lender)) class="active" @endif onclick="window.location='<?php echo url('prestadores');?>'">
                  <a href="<?php echo url('prestadores');?>" title="Ver Prestadores">
                     <div class="nav-icon-3 lender"></div>
                     Prestadores
                  </a>
                  </li>
                  <li @if($_SERVER["REQUEST_URI"]=="/soporte") class="active" @endif onclick="window.location='<?php echo url('soporte');?>'">
                  <a href="<?php echo url('soporte');?>" title="Ver Soporte">
                     <div class="nav-icon-3 support"></div>
                     Soporte
                  </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
   <div class="content-sidenav2 content-sidenav hidden-lg hidden-md hidden-sm" id="content-sidenav" style="display: none;">
      <div class="sidenav-right">
         <div class="menu-box">
            <div class="box-2">
               <ul class="menu-movil">
                  <li @if($_SERVER["REQUEST_URI"]=="/escritorio") class="active" @endif onclick="window.location='<?php echo url('escritorio');?>'">
                  <a href="<?php echo url('escritorio');?>" title="Ver Escritorio">
                     <div class="nav-icon-3 dashboard"></div>
                     Escritorio
                  </a>
                  </li>
                  <li @if($_SERVER["REQUEST_URI"]=="/agenda" || isset($act_diary)) class="active" @endif onclick="window.location='<?php echo url('agenda');?>'" >
                  <a href="<?php echo url('agenda');?>" title="Ver Agenda">
                                       <div class="nav-icon-3 bookmark"></div>
                  Agenda</a>
                  </li>
                  <li @if($_SERVER["REQUEST_URI"]=="/empresas" || isset($act_business) ) class="active" @endif  onclick="window.location='<?php echo url('empresas');?>'">
                  <a href="<?php echo url('empresas');?>" title="Ver Empresas">
                     <div class="nav-icon-3 work"></div>
                     Empresas
                  </a>
                  </li>
                  <li @if($_SERVER["REQUEST_URI"]=="/sucursales" || isset($act_branch)) class="active" @endif onclick="window.location='<?php echo url('sucursales');?>'">
                  <a href="<?php echo url('sucursales');?>" title="Ver Sucursales">
                     <div class="nav-icon-3 sucursal"></div>
                     Sucursales
                  </a>
                  </li>
                  <li  @if($_SERVER["REQUEST_URI"]=="/prestadores" || isset($act_lender)) class="active" @endif onclick="window.location='<?php echo url('prestadores');?>'">
                  <a href="<?php echo url('prestadores');?>"  title="Ver Prestadores">
                     <div class="nav-icon-3 lender"></div>
                     Prestadores
                  </a>
                  </li>
                  <li @if($_SERVER["REQUEST_URI"]=="/soporte") class="active" @endif onclick="window.location='<?php echo url('soporte');?>'">
                  <a href="<?php echo url('soporte');?>"  title="Ver Soporte">
                     <div class="nav-icon-3 support"></div>
                     Soporte
                  </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
   <header class="header-area header-wrapper">
      <div class="row">
         <div class="col-md-3 col-sm-3 col-xs-6">
            <div class="logo-header">
               <span id="btn-toogle" class="btn-toogle"></span>
               <a href="<?php echo url('escritorio');?>">
               <img src="<?php echo url('/');?>/img/logo_2.png">
               </a>
            </div>
         </div>
         <div class="col-md-9 col-sm-9 col-xs-6">


            <ul class="menu-opt hidden-lg hidden-md hidden-sm">
               <li>
                  <span id="btn-bell-1" class="btn-toogle-1">
                  	<?php $notifications=DB::table('tu_notificaciones')->where('us_id',Auth::guard('user')->User()->us_id)->where('status','1')->get(); ?>
@if(count($notifications)!=0)
                  <div  class="points-noty"></div>
                  @endif
                  </span>
               </li>
               <li>
               <li class="dropdown dropdown-user" onclick="hideNotys()">
                  <a class="dropdown-toggle" data-toggle="dropdown">
                  <span style="   color:  #808080; cursor: pointer;"> <img src="<?php echo url('uploads/icons/user.png');?>" alt="<?php echo Auth::guard('user')->User()->us_nom;?>" class="icon-avatar"></span>
                  <i class="caret" style="   color:  #808080"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                     <li style="    width: 100%; margin-left: 0px"><a href="{{url('mi-perfil')}}">Mi cuenta</a></li>
                     <li style="    width: 100%; margin-left: 0px"><a  onclick="logout()">Cerrar sesión</a></li>
                  </ul>
               </li>
            </ul>

            <ul class="menu-opt hidden-xs">
             
               <li>
                  <span id="btn-bell" class="btn-toogle-1" title="Notificaciones">
                  	<?php $notifications=DB::table('tu_notificaciones')->where('us_id',Auth::guard('user')->User()->us_id)->where('status','1')->get(); ?>
@if(count($notifications)!=0)
                  <div  class="points-noty"></div>
                  @endif
                  </span>
               </li>
               <li>
               <li class="dropdown dropdown-user">
                  <a class="dropdown-toggle" data-toggle="dropdown">
                  <span style="   color:  #808080; cursor: pointer;"> <img src="<?php echo url('uploads/icons/user.png');?>" alt="<?php echo Auth::guard('user')->User()->us_nom;?>" class="icon-avatar"> 
                  {{Auth::guard('user')->User()->us_nom}}</span>
                  <i class="caret" style="   color:  #808080"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                     <li style="    width: 100%; margin-left: 0px"><a href="{{url('mi-perfil')}}">Mi cuenta</a></li>
                    
                     <li style="    width: 100%; margin-left: 0px"><a  onclick="logout()">Cerrar sesión</a></li>
                  </ul>
               </li>
            </ul>
         </div>
      </div>
      </div>
   </header>
   <!-- start content -->
   <div class="content-wrapper">
      @yield('content')
   </div>
   <section id="bottom-bar">
      <div class="container">
         <div class="row">
            <!-- .copyright -->
            <div class="col-md-6 col-sm-6 col-xs-12 text-pp">
               <p>v4.0 Turnonet © 2011-<?php echo date("Y");?></p>
            </div>
            <div class="col-md-6 col-sm-6 hidden-xs" style="padding: 0px;">
               <ul>
                  <li><a href="{{url('terminos-y-condiciones')}}">Términos y Condiciones</a></li>
                  <li><a href="{{url('politicas-de-privacidad')}}">Política de Privacidad</a></li>
               </ul> 
            </div>
            <!-- /.copyright -->
         </div>
      </div>
   </section>
   <!--end content -->
   <div id="myModalp" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content-1">
            <div class="modal-header-1">
               <h3 id="title-modal">Datos del cliente</h3>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body-1">
               <div class="row">
                  <div class="col-xs-12">
                     <div id="lodp"></div>
                  </div>
                  <div class="col-xs-12">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--Boostrap-->
   <input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
   <?php echo Html::script('frontend/js/sweetalert.min.js')?>
   <?php echo Html::script('frontend/js/jquery.growl.js')?>
   <!--Theme-->
   <?php echo Html::script('frontend/js/script.js?v='.time())?>
   @if(Session::has('no-results'))
   <script type="text/javascript">
      swal("No se encontraron resultados en el rango de fechas seleccionado", {
         allowOutsideClick: false,
         closeOnClickOutside: false
      })
      .then((value) => {
         location.reload();
      });
   </script>
   @endif
   @if(Session::has('mp-create'))
   <script type="text/javascript">
      swal("Su cuenta de mercado pago ha sido vinculada con con éxito", {
         allowOutsideClick: false,
         closeOnClickOutside: false
      })
      .then((value) => {
         location.reload();
      });
   </script>
   @endif
   @if(Session::has('zoom-create'))
   <script type="text/javascript">
      swal("Su cuenta de zoom ha sido vinculada con con éxito", {
         allowOutsideClick: false,
         closeOnClickOutside: false
      })
      .then((value) => {
         location.reload();
      });
   </script>
   @endif
   </body>
   <div id="logg"></div>
</html>
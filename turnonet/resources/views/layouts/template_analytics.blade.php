
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
      <title>Área Administrativa - Turnonet</title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="theme-color" content="#f05a24"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
      <link rel="shortcut icon" type="image/x-icon" href="<?php echo url('/')?>/uploads/icons/favicon.png">
      <!-- Styles -->
       <!-- Styles -->
      <?php echo Html::style('backend/css/font-awesome.css')?>
      <?php echo Html::style('backend/css/materialize.min.css?v='.time())?>
      <?php echo Html::style('backend/css/icons.css')?>
     <?php echo Html::style('backend/css/bootstrap.min.css?v='.time())?>
      <?php echo Html::style('backend/css/style.css?v='.time())?>
      <?php echo Html::style('backend/css/responsive.css')?>
      <?php echo Html::style('backend/css/color.css?v='.time())?>
      <?php echo Html::style('backend/css/jquery.growl.css?v='.time())?>
      <?php echo Html::style('backend/css/sweetalert.css?v='.time())?>
      <?php echo Html::style('backend/css/bootstrap-datetimepicker.css?v='.time())?>
      <!-- Scripts -->
       <!-- Scripts -->
      <?php echo Html::script('backend/js/jquery.min.js')?>

      <?php echo Html::script('backend/js/materialize.min.js')?>

      <?php echo Html::script('backend/js/angular.min.js')?>
      <?php echo Html::script('backend/js/bootstrap.min.js')?>
      <?php echo Html::script('backend/js/ui-bootstrap-tpls.min.js')?>

      <?php echo Html::script('backend/js/moment.min.js?v='.time())?>
      <?php echo Html::script('backend/js/es.js?v='.time())?>
      <?php echo Html::script('backend/js/bootstrap-datetimepicker.min.js?v='.time())?>

      <?php echo Html::script('backend/js/morris.js')?>

      <?php echo Html::script('backend/js/sparkline.js')?>

      <?php echo Html::script('backend/js/chat.js')?>

      <?php echo Html::script('backend/js/jquery.hideseek.min.js')?>
   <body>
      <!--<div id="mask">
         <div class="loader-p">
         <div class="col-md-12 text-center">
            <img src="<?php echo url('/')?>/uploads/icons/logo.png">
            <img src="<?php echo url('/')?>/uploads/icons/logo.png">
            </div>
         </div>
         </div>-->
      <div class="topbar">
         <a class="sidemenu-btn" href="#" title=""><i class="ti-menu"></i></a><!-- Sidemenu Button -->
         <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
         <!-- Logo -->
         <!-- Sidemenu Button -->
         <div class="topbar-links">
            <div class="launcher">
              
               <a class="click-btn" href="#" title=""><img src="<?php echo url('/')?>/backend/images/avatar.png" alt="" style="
                  float: none;
                  width: 30px;
                  margin-right: 10px;
                  vertical-align: middle;" />{{Auth::guard('admin')->User()->name}} <i class="fa fa-chevron-down"></i></a>
               <div class="launcher-dropdown z-depth-2">
                <a href="<?php echo url('/')?>" title="" class="launch-btn" target="_blank"><i class="ti-world purple-text"></i> <span>Ver sitio</span></a>
                  <a href="<?php echo url('my-account')?>" title="" class="launch-btn"><i class="ti-user green-text"></i> <span>Mi cuenta</span></a>
                  <a href="<?php echo url('logout')?>" title="" class="launch-btn"><i class="ti-lock orange-text"></i> <span>Cerrar sesión</span></a>
               </div>
            </div>
            <!-- Launcher -->
         </div>
             
         <!-- Topbar Links -->
      </div>
      <!-- Top Bar -->
      <div class="sidemenu">
         <div class="sidemenu-inner scroll">
            <!-- Admin -->
            <a href="<?php echo url('dashboard')?>" class="sSidebar_hide sidebar_logo_large ">
            <img class="logo_regular" src="<?php echo url('/')?>/uploads/backend/logo.png" style="    width: 150px;
    float: left;
    margin-left: 10%;" />
            </a>
            <nav class="admin-nav">
               <h6>Área Administrativa</h6>
               <ul>
             
               @if (Auth::guard('admin')->User()->level=='1' || Auth::guard('admin')->User()->rol=='1')
               <li>
                  <a class="waves-effect" href="<?php echo url('dashboard')?>" title="Escritorio"><i class="ti-desktop red lighten-1"></i> Escritorio</a>
                   <ul>
                     <li><a href="<?php echo url('dashboard')?>" title="General">General</a></li>
                      <li><a href="<?php echo url('analytics')?>" title="Analytics">Analytics</a></li>
                  </ul>
               </li>
               @else
               <li>
                  <a class="waves-effect" href="<?php echo url('dashboard')?>" title="Escritorio"><i class="ti-desktop red lighten-1"></i> Escritorio</a>
               </li>

               @endif
             
               <!-- /Dashboard-->
              @if (Auth::guard('admin')->User()->level=='1' || Auth::guard('admin')->User()->rol=='1')
               @foreach(DB::table('modules')->get() as $rs)
               <!--module-->
               <li class="waves-effect" title="{{$rs->name}}">
                  <a href="#"><i class="{{$rs->class}}"></i>  {{$rs->name}} </a>
                  <ul>
                     @foreach(DB::table('submodules')->where('id_module','=',$rs->id)->get() as $rs_s)
                     <li><a href="<?php echo url($rs_s->url)?>" title="{{$rs_s->name}}">{{$rs_s->name}}</a></li>
                     @endforeach
                  </ul>
               </li>
               @endforeach
               @else
               @foreach(DB::table('permissions')->where('role','=',Auth::guard('admin')->User()->rol)->where('type','=','1')->where('status','=','1')->get() as $rs)
               <?php $module=DB::table('modules')->where('id','=',$rs->module)->first(); ?>
               <li class="waves-effect" title="{{$module->name}}">
                  <a href="#"><i class="{{$module->class}}"></i> {{$module->name}} </a>
                  <ul>
                     @foreach( DB::table('permissions')->where('role','=',Auth::guard('admin')->User()->rol)->where('module','=',$rs->module)->where('type','=','2')->where('status','=','1')->get()  as $rs_s)
                     <?php $submodule=DB::table('submodules')->where('id','=',$rs_s->submodule)->first(); ?>
                     <li><a href="<?php echo url($submodule->url)?>" title="{{$submodule->name}}">{{$submodule->name}}</a></li>
                     @endforeach
                  </ul>
               </li>
               @endforeach
               @endif
            </nav>
         </div>
      </div>
       <!-- Sidemenu -->
      <!--=================================
         Content -->
      @yield('content')
      <!--=================================
         Content -->            
      <!--Scripts-->
      <?php echo Html::script('backend/js/enscroll-0.5.2.min.js')?>
      <?php echo Html::script('backend/js/animate-headline.js')?>
      <?php echo Html::script('backend/js/impromptu.js')?>
      <?php echo Html::script('backend/js/slick.min.js')?>
      <?php echo Html::script('backend/js/sweetalert-dev.js')?>
      <?php echo Html::script('backend/js/script.js')?>
      <?php echo Html::script('backend/js/isotope.js')?>
      <script type="text/javascript">
            function confirmarCierre() {
    var cerrar = setTimeout(cerrarSesion, 10000); //5 segs de prueba
    swal({
      title: "Confirmá que querés cerrar sesión",
      text: "Su sesión expiró, presione cancelar para extender la sesión ",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#fd2923',
      confirmButtonText: "Si, Cerrar sesión",
      cancelButtonText: "Cancelar",
      closeOnConfirm: false,
      closeOnCancel: false
    }, function(isConfirm) {
      if (isConfirm) {
        cerrarSesion();
      } else {
        swal.close();
            //si presiona OK
            clearTimeout(cerrar); //elimino el tiempo a la funcion cerrarSesion
            clearTimeout(temp); //elimino el tiempo a la funcion confirmarCierre
            temp = setTimeout(confirmarCierre, 900000); //y aca le doy un nuevo tiempo a la funcion confirmarCierre (5 segs)
          }
        });
  }

  function cerrarSesion() {
      var stateObj = { foo: "cms" };
      history.pushState(stateObj, "Área Administrativa", "cms")
      window.location = '<?php echo url("/");?>/logout';
  }
  // se llamará a la función que confirmar Cierre después de 10 segundos
  var temp = setTimeout(confirmarCierre, 900000);
  // cuando se detecte actividad en cualquier parte de la app
  $(document).on('click keyup keypress keydown blur change', function(e) {
      // borrar el temporizador de la funcion confirmarCierre
      clearTimeout(temp);
      // y volver a iniciarlo con 10segs
      temp = setTimeout(confirmarCierre, 900000);
  });
         
      </script>
   </body>
</html>
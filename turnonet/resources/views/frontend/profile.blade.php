@extends('layouts.template_frontend_inside')
@section('content')
<?php 
   function nameMonth($month) {
     switch ($month) {
       case 1:
       $month = 'Enero';
       break;
       case 2:
       $month = 'Febrero';
       break;
       case 3:
       $month = 'Marzo';
       break;
       case 4:
       $month = 'Abril';
       break;
       case 5:
       $month = 'Mayo';
       break;
       case 6:
       $month = 'Junio';
       break;
       case 7:
       $month = 'Julio';
       break;
       case 8:
       $month = 'Agosto';
       break;
       case 9:
       $month = 'Septiembre';
       break;
       case 10:
       $month = 'Octubre';
       break;
       case 11:
       $month = 'Noviembre';
       break;
       case 12:
       $month = 'Diciembre';
       break;
     }
     return $month;
   }
   
   function nameDay($day) {
   
           switch ($day) {
               case 0:
               $day = 'Domingo';
               break;
               case 1:
               $day = 'Lunes';
               break;
               case 2:
               $day = 'Martes';
               break;
               case 3:
               $day = 'Miércoles';
               break;
               case 4:
               $day = 'Jueves';
               break;
               case 5:
               $day = 'Viernes';
               break;
               case 6:
               $day = 'Sábado';
               break;
           }
           return $day;
    }
   
   ?>
<div class="container">
   <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
         <h2 class="title-section-2">Mi CUENTA</h2>
         <h4 class="title-date-2">Administra la información de tu cuenta</h4>
         <br>
         <ul class="nav nav-pills">
            <li class="active"><a href="<?php echo url('mi-perfil');?>">General</a></li>
              @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')
            <li><a href="<?php echo url('actualizar-cuenta');?>">Datos adicionales</a></li>
            @endif
            <li><a href="<?php echo url('actualizar-clave');?>">Actualizar contraseña</a></li>
         </ul>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Información General
            </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-3 col-sm-3 col-xs-12 text-center">
                     <img src="<?php echo url('img/empty.jpg');?>" alt="<?php echo Auth::guard('user')->User()->us_nom;?>" class="avatar-user">
                     <?php 
                        $color="#e7891c";
                         $text='CUENTA GRATUITA';
                        
                        if(Auth::guard('user')->User()->us_membresia=='1') {
                        
                         $color="#e7891c";
                         $text='CUENTA GRATUITA';
                        
                        }
                       
                        if(Auth::guard('user')->User()->us_membresia=='2') {
                        
                         $color="#662d91";
                         $text='CUENTA PREMIUM';
                        
                        }
                        
                        
                        
                        
                        
                        ?>
                           @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')
                     <h3 class="tt" style="background:<?php echo $color;?>"><?php echo $text;?></h3>
                     @endif
                  </div>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                     <div class="user-nav">
                        <?php $created_at = (Auth::guard('user')->User()->us_altfec == null) ? '' : date("Y-m-d", strtotime(Auth::guard('user')->User()->us_altfec)); ?>
                        <ul>
                           <li><strong>Nombre de usuario:</strong> <?php echo Auth::guard('user')->User()->us_nom;?></li>
                           <li><strong>Correo electrónico:</strong> <?php echo Auth::guard('user')->User()->us_mail?></li>


                            @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')

                           @if($created_at!="")
                           <li><strong>Fecha de Registro:</strong> <?php echo nameDay(date("w",strtotime($created_at))).', '.date("d",strtotime($created_at)).' de '.nameMonth(date("m",strtotime($created_at))).' del '.date("Y",strtotime($created_at));?></li>
                           @endif

                           @endif
                          
                        </ul>
                     </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">  
                    @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')


                     <p><br>Para actualizar el tipo de cuenta comunicate con nosotros.</p>
                     @endif
                  </div>
               </div>
            </div>
         </div>
         @if($personalData==0)
           @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')
         <br>
         <div class="alert alert-warning" role="alert">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> No has completado tus datos adicionales. Hacelo <a href="<?php echo url('actualizar-cuenta');?>">ahora</a>
         </div>
         <br>
         @endif
         @endif
      </div>
   </div>
</div>
@stop
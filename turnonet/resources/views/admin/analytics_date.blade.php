@extends('layouts.template_analytics')
@section('content')
<?php
   function month($mes) {
           setlocale(LC_TIME, 'spanish');
           $nombre = strftime("%B", mktime(0, 0, 0, $mes, 1, 2000));
           return ucwords($nombre);
       }
      function getDay($day){
      
              if($day=='0'){
                  return 'Domingo';
              }
              if($day=='1'){
                  return 'Lunes';
              }
              if($day=='2'){  
                  return 'Martes';
              }
              if($day=='3'){
                  return 'Miércoles';
              }
              if($day=='4'){
                  return 'Jueves';
              }
              if($day=='5'){
                  return 'Viernes';
              }
              if($day=='6'){
                  return 'Sábado';  
              }
          }
      ?>
<div class="content-area" ng-app="myApp"  ng-controller="paginado">
<div class="breadcrumb-bar">
   <div class="page-title">
      <h1><i class="fa fa-line-chart" aria-hidden="true"></i> Analytics</h1>
   </div>
   <ul class="admin-breadcrumb">
      <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
      <li>Analytics</li>
   </ul>
   </div
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper" ng-app="myApp" ng-controller="paginado">
      <div class="row" style="    height: 40px;">
         <div class="col s12" >
            <ul class="nav nav-tabs" style="border-bottom: none; padding-left: 0px!important">
               <li><a  href="{{url('/')}}/dashboard"><i class="ti-settings"></i> General</a></li>
               <li class="active"><a href="{{url('/')}}/analytics"><i class="fa fa-line-chart" aria-hidden="true"></i> Analytics</a></li>
            </ul>
         </div>
         <br><br>
      </div>
      <div class="row">
         <div class="masonary">
            <div class="col s3">
               <br>
               <input type='text' id='fecha_inicial' class="form-control"  value="<?php echo date('d-m-Y',strtotime($init));?>" />
            </div>
            <div class="col s3">
               <br>
               <input type='text' id='fecha_final' class="form-control"  value="<?php echo date('d-m-Y',strtotime($end));?>" />
            </div>
            <div class="col s3">
               <br>
               <a  class="btn orange" ng-click="buscar_fecha()"  style="height: 47px;line-height: 50px;"> Buscar</a>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col s12">
            <br><br>
         </div>
         <div class="col s3">
            <div class="widget z-depth-1">
               <div class="widget-title">
                  <h3 style="font-weight:300;font-size:15px"><i class="ti-mobile"></i> Sesiones por dispositivo</h3>
               </div>
               <div class="widget-content">
                  <div id="donut"></div>
                  <p style="margin: 0px"><strong>Computadoras:</strong> ------------------------{{$desktop}} Visitas</p>
                  <p style="margin: 0px"><strong>Móviles:</strong> ------------------------- {{$mobiles}} Visitas</p>
                  <p style="margin: 0px"><strong>Tablets:</strong> -----------------------  {{$tablets}} Visitas</p>
               </div>
            </div>
         </div>
         <div class="col s9">
            <div class="col s6">
               <div class="widget z-depth-1">
                  <div class="widget-title">
                     <h3 style="font-weight:300;font-size:15px"><i class="ti-world"></i> Sesiones por ciudad</h3>
                  </div>
                  <div class="widget-content">
                     <div class="country-map" >
                        <div class="projects-table" style="height: 100px; overflow-y: auto;">
                           <table  class="table">
                              <thead>
                                 <tr>
                                    <th>
                                       <div >Ciudad</div>
                                    </th>
                                    <th>
                                       <div >Visitas</div>
                                    </th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($code as $rs)
                                 <tr >
                                    <td style="font-size: 12px; vertical-align: middle;">@if($rs->name!='') {{$rs->name}}@if($rs->code!=''),@endif @endif @if($rs->code!='') {{$rs->code}} @endif  @if($rs->name=='' && $rs->code=='') United States @endif</td>
                                    <td style="font-size: 12px; vertical-align: middle;"> {{$rs->total}}  </td>
                                 </tr>
                                 @endforeach
                                 @if(count($code)==0)
                                 <tr  >
                                    <td colspan="4">
                                       <p style="font-style: italic;color: #666;"><i class="ti-info-alt"></i> No hay resultados disponibles en este momento</p>
                                    </td>
                                 </tr>
                                 @endif
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="widget z-depth-1">
                  <div class="widget-title">
                     <h3 style="font-weight:300;font-size:15px">Total  de Visitas</h3>
                  </div>
                  <div class="widget-content">
                     <div class="main-counter dark">
                        <strong>
                        <?php $visitors=DB::table('visits')->whereBetween('date', [date("Y-m-d",strtotime($init)),date("Y-m-d",strtotime($end))])->get(); ?> 
                        {{count($visitors)}}
                        </strong>
                        <span><i class="ti-bar-chart blue-text"></i></span>
                     </div>
                  </div>
               </div>
               <div class="widget z-depth-1">
                  <div class="widget-title">
                     <h3 style="font-weight:300;font-size:15px">Total de SMS</h3>
                  </div>
                  <div class="widget-content">
                     <div class="main-counter dark">
                        <strong>
                        {{$sms}}
                        </strong>
                        <span><i class="ti-mobile red-text"></i></span>
                     </div>
                  </div>
               </div>
               <div class="widget z-depth-1">
                  <div class="widget-title">
                     <h3 style="font-weight:300;font-size:15px">Total comisiones</h3>
                  </div>
                  <div class="widget-content">
                     <div class="main-counter dark">
                        <strong>
                        {{number_format($commissions, 2, ".", ",")}} $
                        </strong>
                        <span><i class="ti-credit-card green-text"></i></span>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col s6">
               <div class="widget z-depth-1">
                  <div class="widget-title">
                     <h3 style="font-weight:300;font-size:15px"><i class="ti-alarm-clock"></i> Usuarios por hora del día</h3>
                  </div>
                  <div class="widget-content">
                     <div class="projects-table" style="height: 600px; overflow-y: auto;">
                        <table  class="table">
                           <thead>
                              <tr>
                                 <th>
                                    <div >Hora</div>
                                 </th>
                                 <th>
                                    <div >Visitas</div>
                                 </th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $i=0; ?> 
                              @foreach($hours as $rs)
                              <?php $i=$i+1; ?> 
                              <tr >
                                 <td style="font-size: 12px; vertical-align: middle;"> <?php 
                                    echo date("H",strtotime($rs->hour)).':00'; 
                                    
                                     if(date("H",strtotime($rs->hour))<12){ 
                                    
                                       echo ' am';
                                    
                                        } else{  
                                    
                                         echo ' pm';
                                      }
                                    
                                     ?>
                                 </td>
                                 <td style="font-size: 12px; vertical-align: middle;"> {{$rs->total}}  </td>
                              </tr>
                              @endforeach
                              @if(count($hours)==0)
                              <tr  >
                                 <td colspan="4">
                                    <p style="font-style: italic;color: #666;"><i class="ti-info-alt"></i> No hay resultados disponibles en este momento</p>
                                 </td>
                              </tr>
                              @endif
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col s12" style="clear: both;"></div>
         <div class="col s4" style="clear: both;">
            <div class="widget z-depth-1">
               <div class="widget-title">
                  <h3 style="font-weight:300;font-size:15px"><i class="ti-layers"></i> 
                     ¿Qué páginas visitan sus usuarios?
                  </h3>
               </div>
               <div class="widget-content">
                  <div class="projects-table" style="height: 750px; overflow-y: auto;">
                     <table width="100%" class="table">
                        <thead>
                           <tr>
                              <th>
                                 <strong>Página</strong>
                              </th>
                              <th>
                                 <strong>Visitas</strong>
                              </th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr ng-repeat="row in list_views" class="text-center">
                              <td style="font-size: 12px; vertical-align: middle;text-transform: uppercase;"> @{{row.page}}</td>
                              <td style="font-size: 12px; vertical-align: middle;text-align: center;">@{{row.views}} </td>
                           </tr>
                           <tr ng-show="filteredItems == 0" >
                              <td colspan="2">
                                 <p style="font-style: italic;text-align: center;color: #666;"><i class="ti-info-alt"></i> No hay resultados disponibles en este momento</p>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <div class="col s8">
            <div class="widget z-depth-1">
               <div class="widget-title">
                  <h3 style="font-weight:300;font-size:15px"><i class="ti-desktop"></i> Sesión por dirección IP </h3>
               </div>
               <div class="widget-content">
                  <div class="projects-table" style="height: 750px; overflow-y: auto;">
                     <table  class="table">
                        <thead>
                           <tr>
                              <th style="width: 15%">
                                 <div >Dirección IP</div>
                              </th>
                              <th style="width: 30%">
                                 <div >Ciudad</div>
                              </th>
                              <th style="width: 30%">
                                 <div >Página</div>
                              </th>
                              <th style="width: 25%">
                                 <div >Fecha</div>
                              </th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $i=0; ?> 
                           @foreach($ip as $rs)
                           <?php $i=$i+1; ?> 
                           <tr >
                              <td style="font-size: 12px; vertical-align: middle;"> {{$rs->ip}}</td>
                              <td style="font-size: 12px; vertical-align: middle;"> @if($rs->name!='') {{$rs->name}}@if($rs->code!=''),@endif @endif @if($rs->code!='') {{$rs->code}} @endif  @if($rs->name=='' && $rs->code=='') United States @endif</td>
                              <td style="font-size: 12px; vertical-align: middle;text-transform: uppercase;"> {{$rs->page}} </td>
                              <td style="font-size: 12px; vertical-align: middle;"> {{date("m-d-Y H:i:s",strtotime($rs->created_at))}} </td>
                           </tr>
                           @endforeach
                           @if(count($ip)==0)
                           <tr  >
                              <td colspan="4">
                                 <p style="font-style: italic;color: #666;"><i class="ti-info-alt"></i> No hay resultados disponibles en este momento</p>
                              </td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <div class="col s6">
            <div class="widget z-depth-1">
               <div class="widget-title">
                  <h3 style="font-weight:300;font-size:15px"><i class="ti-user"></i> Usuarios activos por día</h3>
               </div>
               <div class="widget-content">
                  <div id="bar-chart" style="height: 250px; margin-bottom: 30px;" ></div>
                  <br>
                  <table  class="table">
                     <thead>
                        <tr>
                           <th>
                              <div >Día</div>
                           </th>
                           <th>
                              <div >Visitas</div>
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($days as $rs)
                        <tr >
                           <td style="font-size: 12px; vertical-align: middle;">{{getDay($rs->day)}}</td>
                           <td style="font-size: 12px; vertical-align: middle;"> {{$rs->total}} </td>
                        </tr>
                        @endforeach
                        @if(count($days)==0)
                        <tr >
                           <td colspan="4">
                              <p style="font-style: italic;color: #666;"><i class="ti-info-alt"></i> No hay resultados disponibles en este momento</p>
                           </td>
                        </tr>
                        @endif
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         <div class="col s6">
            <div class="widget z-depth-1">
               <div class="widget-title">
                  <h3 style="font-weight:300;font-size:15px"><i class="ti-user"></i> 
                     Usuarios activos en el mes
                  </h3>
               </div>
               <div class="widget-content">
                  @if(count($users_month)!=1)
                  <span id="sparkline2"></span>
                  @endif
                  <div class="projects-table" style="height: 300px; overflow-y: auto;">
                     <table  class="table">
                        <thead>
                           <tr>
                              <th>
                                 <div >Fecha</div>
                              </th>
                              <th>
                                 <div >Visitas</div>
                              </th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $i=0; ?> 
                           @foreach($users_month as $rs)
                           <?php $i=$i+1; ?> 
                           <tr >
                              <td style="font-size: 12px; vertical-align: middle;"> 
                                 {{month(date("m",strtotime($rs->date)))}}  {{date("d",strtotime($rs->date))}}, {{date("Y",strtotime($rs->date))}} 
                              </td>
                              <td style="font-size: 12px; vertical-align: middle;"> {{$rs->total}} </td>
                           </tr>
                           @endforeach
                           @if(count($users)==0)
                           <tr >
                              <td colspan="4">
                                 <p style="font-style: italic;color: #666;"><i class="ti-info-alt"></i> No hay resultados disponibles en este momento</p>
                              </td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   $(function() {
       $('#fecha_inicial').datetimepicker({
         format: 'DD-MM-YYYY'
       });
       $('#fecha_final').datetimepicker({
         format: 'DD-MM-YYYY'
       });
     });
      var app = angular.module('myApp', ['ui.bootstrap']);
        app.controller('paginado', function($scope, $http, $window) {
      
           $scope.rows_views = function() {
             $scope.list_views=[];
              $http.get("{{url('/')}}/views_pages_listing_date/{{$init}}/{{$end}}").then(function successCallback(response)  {
                 $scope.list_views = response.data;
                 $scope.filteredItems = $scope.list_views.length;
              });
           }
           $scope.rows_views();
      
           $scope.buscar_fecha= function(){
          if ($('#fecha_inicial').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_inicial').focus();
            return (false);
        }
        if ($('#fecha_final').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_final').focus();
            return (false);
        }else{
          var fecha_inicial =document.getElementById("fecha_inicial").value;
          var fecha_final =document.getElementById("fecha_final").value;
          var url="<?php echo url('/');?>/analytics-date/"+fecha_inicial+"/"+fecha_final;
          if(fecha_final!="" && fecha_inicial!=""){
            var a = document.createElement("a");
            a.href = url;
            a.click();
          }
        }
      }
      
      });
       $(function () {
   <?php 
      $total=$desktop+$mobiles+$tablets; 
      if($desktop!=0){
      $desktop=round($desktop*100/$total);
      }
      if($mobiles!=0){
      $mobiles=round($mobiles*100/$total);
      }
      if($tablets!=0){
      $tablets=round($tablets*100/$total);
      }
      ?>
   
    // Donut Chart Initialization 
    Morris.Donut({
      element: 'donut',
      data: [
      {value: <?php echo $desktop;?>, label: 'Computadoras'},
      {value: <?php echo $mobiles;?>, label: 'Móviles'},
      {value: <?php echo $tablets;?>, label: 'Tablets'}
      ],
      backgroundColor: '#e5e5e5',
      labelColor: '#131313',
      colors: ['#5E65AE','#666669','#FDD306'],
      formatter: function (x) { return x + "%"}
    }); 
   
    <?php 
      $Objdays=array();
      foreach($days as $rs): 
        $Objdays[]=array(
           'y'=>getDay($rs->day),
           'a'=>$rs->total
        );
      endforeach;
      ?> 
   
    Morris.Bar({
   element: 'bar-chart',
   data: <?php echo json_encode($Objdays);?>,
   xkey: 'y',
   ykeys: ['a'],
   labels: ['Nº visitas'],
   barColors: ['#5E65AE']
   }); 
   
   <?php
      $Objvisits='';
       $i=0; 
         foreach($users_month as $rs): $i=$i+1; 
         $Objvisits.=$rs->total; if(count($users_month)!=$i): $Objvisits.=','; endif;
         endforeach; 
      ?>
   <?php if(count($users_month)!=1):?>
   $("#sparkline2").sparkline([<?php echo $Objvisits ?>], {
        type: 'line',
        width: '400',
        height: '73',
        lineColor: '#5E65AE',
        fillColor: '#5E65AE',
        spotColor: '#5E65AE',
        minSpotColor: '#5E65AE',
        maxSpotColor: '#5E65AE',
        highlightSpotColor: '#5E65AE',
        spotRadius: 1.7,
        drawNormalOnTop: false
    });
   
   <?php endif; ?>
   
   });
      
</script>
@stop
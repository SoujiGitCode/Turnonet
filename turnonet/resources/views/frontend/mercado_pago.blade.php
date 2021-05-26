<?php 
$get_country=DB::table('tu_pais')->where('pa_id', $get_business->em_pais)->first();
date_default_timezone_set($get_country->time_zone);
?>



@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="posts">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Mercado Pago</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               Configura la pasarela de pagos de tu empresa aquí:
            </div>
            <div class="panel-body">
               <p>Puedes realizar cobros a tus clientes desde la plataforma, el uso de mercado pago tiene un fee adicional de <?php echo $get_business->commission;?> % sobre el valor de la transacción.</p>
               @if($get_business->em_mp=='1')
               @if($get_business->access_token=="")
               <p>- Asocia tu cuenta de mercado pago con nuestro marketplace.</p>
               <button class="btn btn-success" onclick="createMp()"> Asociar Cuenta</button> <br>
               @else
               <pre>Public key: <?php echo $get_business->public_key;?><br>Access token: <?php echo $get_business->access_token;?></pre>
               @if(date("Y-m-d")>date("Y-m-d",strtotime($get_business->expired_mp)))
               <p><br>Para comenzar a recibir pagos, tienes que actualizar tus credenciales </p>
               <button class="btn btn-success" onclick="updateMp()"> Renovar Credenciales</button>
               @endif
               <button class="btn btn-success" onclick="removeMp()" style="background:#FF5722!important;border:1px solid #FF5722!important;"> Desvincular Cuenta</button>

@if(date("Y-m-d")>date("Y-m-d",strtotime($get_business->expired_mp)))
<div class="alert alert-danger" role="alert">
               Renueva las credenciales para asegurar que estén vigentes, dado que son válidas por 6 meses.
            </div>

               @endif


               @endif
               @else
               <button class="btn btn-success" onclick="window.location='<?php echo url('soporte');?>'"> Solicitar Activación</button>
               @endif
            </div>
         </div>
         @if($get_business->em_mp=='1')
         <div class="panel">
            <div class="panel-heading">
               Pagos realizados por tus clientes
            </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="mtop2 bg-preload" style="width: 100%">
                        <div class="row">
                           <div class="col-xs-12 content-spinner">
                              <div class="spinner">
                                 <div class="double-bounce1"></div>
                                 <div class="double-bounce2"></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="bg-list"  style="display: none; width: 100%" >
                        <div class="row">
                           <div class="col-md-4 col-sm-4 col-xs-12" style="clear: both;"> 
                              <input type='text' id='fecha_inicial' class="form-control" value="01-<?php echo date('m');?>-<?php echo date("Y");?>" placeholder="Desde" />
                           </div>
                           <div class="col-md-4 col-sm-4 col-xs-12">
                              <input type='text' id='fecha_final' class="form-control"  value="<?php echo date('d-m-Y');?>" placeholder="Hasta" />
                           </div>
                           <div class="col-md-4 col-sm-4 col-xs-12">
                              <button  class="btn btn-success btn-block btn-excel" ng-click="buscar_fecha()" ><i class="fa fa-search" aria-hidden="true"></i> Filtrar</button>
                           </div>
                        </div>
                        <div class="table-responsive">
                           <table width="100%" class="table" id="sample-table-1" >
                              <thead>
                                 <tr>
                                    <th style="width: 20%">ID de pago</th>
                                    <th style="width: 20%; text-align: center;">ID de turno</th>
                                    <th style="width: 20%;text-align: center">Monto</th>
                                    <th style="width: 35%;text-align: center">Fecha</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                                    <td style="vertical-align: middle; text-transform: capitalize;">@{{row.id_payment}}</td>
                                    <td style="vertical-align: middle;text-align: center; color: #f44336; cursor: pointer;" ng-click="goTurno(row.code)" title="Ver detalle del turno">@{{row.code}}</td>
                                    <td style="vertical-align: middle;text-align: center">$ @{{row.amount}}</td>
                                    <td style="vertical-align: middle;text-align: center">@{{row.created_at}}</td>
                                 </tr>
                                 <tr ng-if="totalItems <= 0" >
                                    <td colspan="4">
                                       <p class="text-italic"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No tienes pagos registrados en este momento</p>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <ul class="pagination" class="col-md-12" ng-show="totalItems > 0">
                              <li  pagination="" page="currentPage" on-select-page="setPage(page)"  total-items="totalItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
                           </ul>
                           <p ng-show="totalItems != 0" class="results-table" ><br>@{{totalItems}} Registro(s) encontrado(s)</p>
                           <br>
                        </div>
                        <div class="row" ng-show="btn_excel != 0">
                           <div class="col-md-12 col-sm-12 col-xs-12">
                              <hr>
                              <br>
                              <button  class="btn btn-success btn-excel" title="Descargar reporte en PDF" ng-click="download_excel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargá  Excel</button>
                              <p class="form-desc" style="color: #ec0e08!important; margin-top: -10px"><br>Se exportarán los primeros 2000 registros entrados.</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @endif
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.business')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/payment.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
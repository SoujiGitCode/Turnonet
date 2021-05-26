@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="posts">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?> > <?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Servicios</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
         <div class="panel">
            <div class="panel-heading">
               @{{titulo}}
            </div>
            <div class="panel-body">
               <form id="form">
                  <div class="row">
                     <input type="hidden" id="em_id" name="em_id" value="<?php echo $get_business->em_id;?>">
                     <input type="hidden" id="suc_id" name="suc_id" value="<?php echo $get_branch->suc_id;?>">
                     <input type="hidden" id="pres_id" name="pres_id" value="<?php echo $get_lender->tmsp_id;?>" >
                     <input type="hidden" id="id" name="">
                     <div class="col-md-12 col-sm-12 col-xs-6 form-group">
                        <label>Nombre *</label>
                        <input type="text" id="name" name="name" class="form-control"
                           placeholder="Ingresá el nombre" value="">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12  form-group">
                        <div class="row">
                           <div class="col-md-6 col-sm-6 col-xs-12">
                              <label>Duración *</label>
                              <select name="hours" class="form-control" id="hours">
                                 <option value="00">0 Horas</option>
                                 <option value="01">1 Hora</option>
                                 <option value="02">2 Horas</option>
                                 <option value="03">3 Horas</option>
                                 <option value="04">4 Horas</option>
                                 <option value="05">5 Horas</option>
                                 <option value="06">6 Hora</option>
                                 <option value="07">7 Horas</option>
                                 <option value="08">8 Horas</option>
                                 <option value="09">9 Horas</option>
                                 <option value="10">10 Horas</option>
                                 <option value="11">11 Horas</option>
                                 <option value="12">12 Horas</option>
                              </select>
                              <br>
                           </div>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                              <label>&nbsp;</label>
                              <select name="minutes" class="form-control" id="minutes">
                                 <option value="00">0 Minutos</option>
                                 <option value="05">5 Minutos</option>
                                 <option value="10">10 Minutos</option>
                                 <option value="12">12 Minutos</option>
                                 <option value="15">15 Minutos</option>
                                 <option value="20">20 Minutos</option>
                                 <option value="25">25 Minutos</option>
                                 <option value="30">30 Minutos</option>
                                 <option value="35">35 Minutos</option>
                                 <option value="40">40 Minutos</option>
                                 <option value="45">45 Minutos</option>
                                 <option value="50">50 Minutos</option>
                                 <option value="55">55 Minutos</option>
                              </select>
                           </div>
                        </div>
                     </div>


                     @if($get_business->em_mp==1 && $get_business->access_token!="")
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Precio *</label>
                        <input type="text" id="price" name="price" class="form-control"
                           placeholder="Ingresá el precio" value="">
                     </div>
                     @endif
                     <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <p>Campos obligatorios (*)
                        </p>
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <br>
                        <button type="button" class="btn btn-success" id="boton-1" ng-click="guardar()">@{{btn}}</button>
                     </div>
               </form>
               </div>
            </div>
         </div>
         <div class="panel">
            <div class="panel-heading">
               Listado de servicios
            </div>
            <div class="panel-body">
               <div class="row">
                   <div class="col-md-3 col-sm-3 col-xs-12">
                    <select class="form-control select-1" id="type" name="type" onchange="angular.element(this).scope().rows()">
                     <option value="1">ALTA</option>
                     <option value="0">BAJA</option>
                  </select>
                  <br>
               </div>
               <div class="col-md-9 col-sm-9 col-xs-12">
               </div>
                  <div class="col-md-6 col-sm-6 col-xs-12" style="clear: both;">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 hidden-xs">
                        <div class="form-group">
                           <div class="input-group">
                              <span class="input-group-addon" style="border-top-left-radius: 3rem;
                                 border-bottom-left-radius: 3rem;"><i class="fa fa-search"></i></span>
                              <input type="text" ng-model="search" id="search" ng-change="filter()" placeholder="Ingresá el nombre del servicio" class="form-control" style="border-top-right-radius: 3rem; border-bottom-right-radius: 3rem" />
                           </div>
                        </div>
                     </div>
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
                        <div class="table-responsive">
                           <table width="100%" class="table" id="sample-table-1" >
                              <thead>
                                 <tr>
                                    <th></th>
                                    <th style="width: 25%">Nombre</th>
                                    <th @if($get_business->em_mp==1 && $get_business->access_token!="") style="width: 20%" @else style="width: 30%" @endif>Duración</th>
                                    @if($get_business->em_mp==1 && $get_business->access_token!="")
                                    <th style="width: 10%">Precio</th>
                                    @endif
                                    <th style="width: 40%"></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                                    <td style="vertical-align: middle;"><input type="checkbox" id="item_@{{row.id}}" value="@{{row.id}}" onchange="set_items(this.id)" ></td>
                                    <td style="vertical-align: middle; text-transform: capitalize;">@{{row.name}}</td>
                                    <td style="vertical-align: middle; text-transform: capitalize;">@{{row.hours}}:@{{row.minutes}}:00</td>
                                    @if($get_business->em_mp==1 && $get_business->access_token!="")
                                    <td style="vertical-align: middle; text-transform: capitalize;">@{{row.price}}</td>
                                    @endif
                                    <td style="vertical-align: middle;">
                                       <span class="controls" ng-show="row.status=='1'">
                                       <a ng-click="asignarValor(row.id,row.name,row.hours,row.minutes,row.price)" class="edituser blue" title="Editar servicio"><i class="ti-pencil-alt" ></i> Editar</a>
                                       <a  ng-click="optCan(row.id)" class="deleteuser red" title="Dar de baja el  servicio"><i class="ti-trash"></i> Dar de baja</a>
                                       </span>

                                         <span class="controls" ng-show="row.status=='0'">
                                          <a ng-click="optAlta(row.id)" class="deleteuser red"  title="Dar de alta"><i class="fa fa-check"></i> Dar de Alta</a>
                                       </span>
                                    </td>
                                 </tr>
                                 <tr ng-if="filteredItems <= 0 || (list | filter:search).length === 0" >
                                    <td colspan="3">
                                       <p class="text-italic"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No tienes servicios disponibles en este momento</p>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <div class="col s12" style="margin-bottom: 2vw;padding-left: 0px;display: none;" id="baja-selec">
                              <span class="controls" ng-show="filteredItems > 0">
                                <a  ng-click="borrar_all();" class="deleteuser red"><i class="ti-trash"></i> Dar de baja la seleccion</a>
                             </span>
                              <br>
                          </div>

                          <div class="col s12" style="margin-bottom: 2vw;padding-left: 0px;display: none;" id="alta-selec">
                              <span class="controls" ng-show="filteredItems > 0">
                                <a  ng-click="alta_all();" class="deleteuser red"><i class="ti-trash"></i> Dar de alta la seleccion</a>
                             </span>
                              <br>
                          </div>

                           <ul class="pagination" class="col-md-12" ng-show="totalItems > 0">
                              <li  pagination="" page="currentPage" on-select-page="setPage(page)"  total-items="totalItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
                           </ul>
                           <p ng-if="filteredItems != 0 && (list | filter:search).length !== 0" class="results-table" ><br>@{{totalItems}} Registro(s) encontrado(s)</p>
                           <br>
                        </div>
                     </div>
@if($get_business->em_mp==1 && $get_business->access_token!="")
                     <div class="row">
                        <div class="col-xs-12 col-md-12">
                           <div class="form-group btop bdashed">
                              <label class="label-1">PLATAFORMA INTEGRADA CON MERCADO PAGO <img src="<?php echo url('img/mplogo.png');?>" class="mpago"></label>
                              <p>Te aseguras que se toma el turno sólo si se realiza el pago previamente. Para Activar/Desactivar tu pasarela de pagos haga <strong style="color: #f15424; cursor: pointer;" onclick="window.location='<?php echo url('prestador/configuracion/'.$get_lender->tmsp_id);?>'">click aquí</strong></p>

                              
                           </div> 
                        </div>
                     </div>
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.lenders')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" id="select_branch" value="<?php echo $get_branch->suc_id;?>">
<input type="hidden" id="select_lender" value="<?php echo $get_lender->tmsp_id;?>">
<?php echo Html::script('frontend/js/settings_lenders.js?v='.time())?>
<?php echo Html::script('frontend/js/service.js?v='.time())?>
@if($get_business->em_mp==1 && $get_business->access_token!="")
<script type="text/javascript">
   document.getElementById("price").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890".indexOf(chr) < 0)
        return false;
   };
</script>
@endif
@stop
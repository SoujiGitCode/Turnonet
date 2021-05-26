@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="post">
   <div class="row">
      <div class="col-md-8 col-sm-8 12 col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">DIRECTORIO</h4>
         <div class="panel">
            <div class="panel-heading panel-heading-2">
               <button class="btn btn-success"  onclick="window.location='<?php echo url('directorio/nuevo/'.$get_business->em_id);?>'"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo Cliente</button>
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
                  <div class="col-md-4 col-sm-4 col-xs-12 hidden-xs">
                  </div>
                  <div class="col-mg-8 col-sm-8 col-xs-12">
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon" style="border-top-left-radius: 3rem;
                              border-bottom-left-radius: 3rem;"><i class="fa fa-search"></i></span>
                           <input type="text" id="search" onkeypress="enter_filtro(event)" onchange="filtro()"  placeholder="Ingresá el nombre o correo del cliente" class="form-control" style="border-top-right-radius: 3rem; border-bottom-right-radius: 3rem" />
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
                                    <th style="width: 5%"></th>
                                    <th style="width: 25%">Cliente</th>
                                    <th style="width: 15%; text-align: center;">Cant Turnos</th>
                                    <th style="width: 55%"></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                                     <td style="vertical-align: middle;"><input type="checkbox" id="item_@{{row.id}}" value="@{{row.id}}" onchange="set_items(this.id)" ></td>
                                    <td style="vertical-align: middle;">
                                   <div style="text-transform: capitalize;"> @{{row.name}}</div>
                                 <div>@{{row.email}}</div></td>
                                    <td style="vertical-align: middle; text-align: center;    color: #FF5722; cursor: pointer;" title="Ver Turnos del cliente" ng-click="goToShift(row.business,row.us_id)"



                                    >@{{row.shift}}</td>
                                    <td style="vertical-align: middle;">
                                       <span class="controls" ng-show="row.status=='1'">
                                          <a ng-click="viewData(row.id)" class="edituser purple lighten-2" title="Editar cliente"><i class="ti-pencilt-al"></i> Ver Datos</a>

                                       <a ng-click="goTo(row.id)" class="edituser blue" title="Editar cliente"><i class="ti-pencil-alt"></i> Editar</a>
                                       <a  ng-click="optCan(row.id)" class="deleteuser red" title="Dar de baja al cliente"><i class="ti-trash"></i>Dar Baja</a>
                                       </span>
                                       <span class="controls" ng-show="row.status=='0'">
                                          <a ng-click="optAlta(row.id)" class="deleteuser red"  title="Dar de alta"><i class="fa fa-check"></i> Dar de Alta</a>
                                          <a ng-click="goLists(row.email)" ng-show="row.email!=''" class="edituser blue" title="Agregar a la lista negra"><i class="fa fa-lock"></i> Agregar a lista negra</a>
                                       </span>
                                    </td>
                                 </tr>
                                 <tr ng-if="totalItems <= 0" >
                                    <td colspan="5">
                                       <p class="text-italic"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No hay clientes disponibles en este momento</p>
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
                           <p ng-show="totalItems != 0" class="results-table" ><br>@{{totalItems}} Registro(s) encontrado(s)</p>
                           <br>
                        </div>
                     </div>
                  </div>

               </div>
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

               <div class="row" ng-show="totalItems > 0">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                       <hr>
                           <br>
                           <button  class="btn btn-success btn-excel" title="Descargar reporte en PDF" onclick="window.open('<?php echo url('excel/clientes/'.$get_business->em_id);?>')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargá  Excel</button>
                            <p class="form-desc" style="color: #ec0e08!important"><br>Se exportarán los primeros 2000 registros encontrados.</p>
                        </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12 n-ppding">
         @include('includes.business')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/directory.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
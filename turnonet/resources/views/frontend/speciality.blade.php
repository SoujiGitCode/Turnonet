@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="posts">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?> > <?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Especialidades</h4>
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
                     <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <label>Nombre *</label>
                        <input type="text" id="name" name="name" class="form-control"
                           placeholder="Ingresá el nombre" value="">
                     </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <p>Campos obligatorios (*)</p>
                     </div>
                  <div class="form-group">
                     <br>
                     <button type="button" class="btn btn-success" id="boton-1" ng-click="guardar()">@{{btn}}</button>
                  </div>
               </form>
            </div>
         </div>
         <div class="panel">
            <div class="panel-heading">
               Listado de Especialidades 
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
                              <input type="text" ng-model="search" id="search" ng-change="filter()" placeholder="Ingresá el nombre de la especialidad" class="form-control" style="border-top-right-radius: 3rem; border-bottom-right-radius: 3rem" />
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
                                    <th style="width: 55%">Nombre</th>
                                    <th style="width: 40%"></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                                    <td style="vertical-align: middle;"><input type="checkbox" id="item_@{{row.id}}" value="@{{row.id}}" onchange="set_items(this.id)" ></td>
                                    <td style="vertical-align: middle; text-transform: capitalize;">@{{row.name}}</td>
                                    <td style="vertical-align: middle;">
                                       <span class="controls" ng-show="row.status=='1'">
                                           <a ng-click="asignarValor(row.id,row.name)" class="edituser blue" title="Editar especialidad"><i class="ti-pencil-alt"></i> Editar</a>
                                       <a  ng-click="optCan(row.id)" class="deleteuser red" title="Dar de alta la especialidad"><i class="ti-trash"></i> Dar de Baja</a>
                                       </span>

                                         <span class="controls" ng-show="row.status=='0'">
                                          <a ng-click="optAlta(row.id)" class="deleteuser red"  title="Dar de alta"><i class="fa fa-check"></i> Dar de Alta</a>
                                       </span>
                                    </td>
                                 </tr>
                                 <tr ng-if="filteredItems <= 0 || (list | filter:search).length === 0" >
                                    <td colspan="3">
                                       <p class="text-italic"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No tienes especialidades disponibles en este momento</p>
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
<?php echo Html::script('frontend/js/speciality.js?v='.time())?>
@stop
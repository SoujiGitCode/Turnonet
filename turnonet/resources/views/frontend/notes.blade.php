@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="posts">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?> > <?php echo mb_convert_encoding($get_lender->tmsp_pnom, 'UTF-8', 'UTF-8');?></h2>
         <h4 class="title-date-2">Observaciones </h4>
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
                        <label>Cliente *</label>
                        <select class="form-control" id="us_id" name="us_id">
                           <option value="">Seleccionar cliente</option>

                
                            @foreach($lists_customer as $rs)

                      <option value="<?php echo $rs['id'];?>"><?php echo mb_strtoupper($rs['name']);?> - <?php echo $rs['email'];?></option>

                        @endforeach
                        </select>
                       

                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-6 form-group">
                        <label>Fecha *</label>
                        <input type="text" id="fecha_inicial" name="fecha_inicial" class="form-control"
                           placeholder="Seleccioná una fecha" value="">
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-6 form-group">
                        <label>Nota *</label>
                          {!! Form::textarea('message',null,['id'=>'message','placeholder'=>'Ingresá un Mensaje*','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <p>Campos obligatorios (*)</p>
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
               Listado de observaciones
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
               <div class="col-md-5 col-sm-5 col-xs-12">

                 <select class="form-control" id="select_customer" >
                           <option value="">Seleccionar cliente</option>
                            @foreach($lists_customer as $rs)
                      <option value="<?php echo $rs['id'];?>"><?php echo mb_strtoupper($rs['name']);?> - <?php echo $rs['email'];?></option>
                        @endforeach
                        </select>
               </div>
                <div class="col-md-4 col-sm-4 col-xs-12"></div>
                   <div class="col-md-6 col-sm-6 col-xs-12" style="clear: both;">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 hidden-xs">
                        <div class="form-group">
                           <div class="input-group">
                              <span class="input-group-addon" style="border-top-left-radius: 3rem;
                                 border-bottom-left-radius: 3rem;"><i class="fa fa-search"></i></span>
                              <input type="text" ng-model="search" id="search" ng-change="filter()" placeholder="Ingresá una descripción" class="form-control" style="border-top-right-radius: 3rem; border-bottom-right-radius: 3rem" />
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
                                    <th style="width:5%"></th>
                                    <th style="width: 25%">Cliente</th>
                                    <th style="width: 10%">Fecha</th>
                                    <th style="width: 40%"></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                                    <td style="vertical-align: middle;"><input type="checkbox" id="item_@{{row.id}}" value="@{{row.id}}" onchange="set_items(this.id)" ></td>
                                    <td style="vertical-align: middle;"><div>@{{row.customer}}</div>
                                      <div>@{{row.email}}</div></td>
                                    <td style="vertical-align: middle; text-transform: capitalize;">@{{row.date_format}}</td>
                                    <td style="vertical-align: middle;">
                                       <span class="controls" ng-show="row.status=='1'">
                                       <a ng-click="asignarValor(row.id,row.note,row.date,row.us_id)" class="edituser blue" title="Editar Nota"><i class="ti-pencil-alt"></i> Editar</a>


                                        <a  ng-click="showModal(row.note,row.customer,row.date)" class="edituser purple lighten-2" title="Ver Nota">Ver Nota</a>


                                       <a  ng-click="optCan(row.id)" class="deleteuser red" title="Dar de baja nota"><i class="ti-trash"></i> Dar de baja</a>
                                       </span>
                                       <span class="controls" ng-show="row.status=='0'">


                                      <a  ng-click="showModal(row.note,row.customer,row.date)" class="edituser purple lighten-2" title="Ver Nota">Ver Nota</a>


                                          <a ng-click="optAlta(row.id)" class="deleteuser red"  title="Dar de alta"><i class="fa fa-check"></i> Dar de Alta</a>
                                       </span>




                                    </td>
                                 </tr>
                                 <tr ng-if="filteredItems <= 0 || (list | filter:search).length === 0" >
                                    <td colspan="3">
                                       <p class="text-italic"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No tienes observaciones disponibles en este momento</p>
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
<input type="hidden" name="emp_id" id="empid" value="<?php echo $get_lender->emp_id;?>">
<?php echo Html::script('frontend/js/settings_lenders.js?v='.time())?>
<?php echo Html::script('frontend/js/note.js?v='.time())?>

@stop
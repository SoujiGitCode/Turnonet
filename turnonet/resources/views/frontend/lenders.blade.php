@extends('layouts.template_frontend_inside')
@section('content')
<div class="container" ng-app="myApp" ng-controller="post">
   <div class="row">
      <div class="col-md-8 col-sm-8 col-xs-12">
         <h2 class="title-section-2">AGENDA <?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Seleccioná un prestador</h4>
         <div class="col-md-12 col-sm-12 col-xs-12">
            <br>
         </div>
         <div class="panel">
            <div class="panel-heading">
               Listado de prestadores
            </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-4 col-sm-4 col-xs-12 hidden-xs">
                  </div>
                  <div class="col-md-8 col-sm-8 col-xs-12">
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon" style="border-top-left-radius: 3rem;
                              border-bottom-left-radius: 3rem;"><i class="fa fa-search"></i></span>
                           <input type="text" id="search" ng-model="search" ng-change="filter()" placeholder="Ingresá el nombre o correo del prestador" class="form-control" style="border-top-right-radius: 3rem; border-bottom-right-radius: 3rem" />
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
                                    <th style="width: 10%">Foto</th>
                                    <th style="width: 45%">Nombre</th>
                                    <th style="width: 20%; text-align: center;">Cant Turnos</th>
                                    <th style="width: 15%"></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" >
                                    <td style="vertical-align: middle; text-align: center;">
                                         <img src="@{{row.image}}" class="img-pres-table" ng-click="goTo(row.id)">
                                    </td>
                                    <td style="vertical-align: middle;">
                                   <div style="text-transform: capitalize;" ng-click="goTo(row.id)">@{{row.title}} @{{row.name}}</div>
                                 <div> @{{row.services}}</div></td>
                                    <td style="vertical-align: middle; text-align: center;">@{{row.shift}}</td>
                                    <td style="vertical-align: middle;">
                                       <span class="controls">
                                       <a ng-click="goTo(row.id)" class="deleteuser red" title="Ver disponibilidad del prestador"><i class="ti-pencil-alt"></i> Disponibilidad</a>
                                       </span>
                                    </td>
                                 </tr>
                                 <tr ng-if="totalItems <= 0" >
                                    <td colspan="5">
                                       <p class="text-italic"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No hay prestadores disponibles en este momento</p>
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
                     </div>
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
<input type="hidden" name="business" id="business" value="<?php echo $get_business->em_id;?>">
<?php echo Html::script('frontend/js/lenders.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
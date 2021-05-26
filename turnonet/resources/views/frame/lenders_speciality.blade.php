@extends('layouts.template_frame')
@section('content')
<?php  $frame = DB::table('frame')->where('emp_id', $get_business->em_id)->first(); ?>
<div class="container container-single ptop" ng-app="myApp" ng-controller="post">

   @if($frame->name==0)
   <div class="row">
      <div class="col-md-2 col-sm-3 col-xs-3 hidden-xs">
         <?php
            $image=url('/').'/img/emptylogo.png';
            
            if($get_business->em_pfot!=""){
              $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
            }    
            ?>
         <img src="<?php echo $image;?>" class="img-press-1">
      </div>
      <div class="col-md-10 col-sm-9 col-xs-12 pno-padding-desktop">
         <div class="tit"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,100);?></div>
      </div>
   </div>
   @endif

   @if(count($branch)>1)
   <div class="row" style="clear: both; @if($frame->searchbar=='1') display: none; @endif">
      <div class="col-xs-12">
         <div class="xsletter"><br>Búsqueda: </div>
         <div class="row row-search">
        
            <div  class="col-md-8 col-sm-8 col-xs-12">
               <select name="sucid" id="sucid" class="f_sel form-control">
                  <option value="ALL">Lugar de Atención</option>
                  @foreach($branch as $rs)
                  <option value="<? echo $rs->suc_id; ?>"><? echo mb_convert_encoding($rs->suc_nom, 'UTF-8', 'UTF-8'); ?></option>
                  @endforeach
               </select>
            </div>
         
      
            
            <div class="col-md-4 col-sm-4 col-xs-12">
               <button name="Submit" type="submit" class="btn busqueda" ng-click="rows()" ><i class="fa fa-search" aria-hidden="true"></i> BUSCAR</button>
            </div>
         </div>
      </div>
   </div>
   @endif
   <div class="row" @if($get_business->em_id==637) style="display: none;" @endif>
      <div class="col-md-12">
         <div class="xsletter btop-movil"><br>Listado de Prestadores:<br><br></div>
      </div>
   </div>
   <div class="row">
      <div class="col-xs-12 content-spinner bg-preload">
         <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
         </div>
      </div>
      <div class="col-md-12 bg-list"  style="display: none;" >
         <div class="row">
            <div class="col-md-12" style="text-align: center;" ng-if="filteredItems <= 0">
               <img src="<?php echo url('/');?>/uploads/icons/noresult.png" alt="No hay resultados" class="img-not-res">
               <p>No hay prestadores disponibles para esta especialidad en este momento</p>
            </div>
         </div>
         <div class="bg-list prestadorc" ng-repeat="row in filtered = (list | filter:search  | orderBy : propertyName :reverse) | orderBy : propertyName :reverse | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit" ng-click="goTo(row.url)"  >
            <div class="row">
               <div class="col-md-3 col-sm-3 col-xs-3 col-panel text-center" ng-click="goTo(row.url)" style="padding: 0px;" >
                  <img src="@{{row.image}}" class="img-pres" ng-click="goTo(row.url)" >
               </div>
               <div class="col-md-9 col-sm-9 col-xs-9 resp1 col-panel ">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="bold xsletter" ng-click="goTo(row.url)" >@{{row.title}} @{{row.name}}</div>
                        <div class="xsletter-33" ng-click="goTo(row.url)" style="    color: #f15b26;" ng-show="row.services!=''" >@{{row.services}}</div>
                        <div class="xsletter-1 hidden-xs" ng-click="goTo(row.url)" ng-show="row.days!=''" ><br>@{{row.days}}</div>
                     </div>
                  </div>
                  <img src="https://www.turnonet.com/frame/imagenes/mplogo.png" class="img-pay-1" ng-show="row.mp=='ALTA'">
                  <img src="<?php echo url('img/video-player.png');?>" class="img-zoom" ng-show="row.zoom!='0'">
                  <a class='btn dispobutton'ng-click="goTo(row.url)"  >Disponibilidad <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
               </div>
            </div>
         </div>
         <div class="row" style="clear: both; @if($get_business->em_id==637) display: none;@endif">
            <div class="col-md-12">
               <ul class="pagination" class="col-md-12 results-table" ng-show="filteredItems > 0">
                  <li  pagination="" page="currentPage" on-select-page="setPage(page)"  total-items="filteredItems" items-per-page="entryLimit" class="page-item" previous-text="&laquo;" next-text="&raquo;"></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
<?php 
$limit=20;
if($get_business->em_presxag!="" && $get_business->em_presxag>0){
   $limit=$get_business->em_presxag;
}
?>

<input type="hidden" name="limit" id="limit" value="<?php echo $limit;?>">
<input type="hidden" id="url_business" name="url_business" value="<?php echo $frame->url;?>">
<input type="hidden" name="espid" id="espid" value="<?php echo $get_speciality->serv_id;?>">
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" name="business" id="business" value="<?php echo $get_business->em_id;?>">
<input type="hidden" name="code" id="code" value="<?php echo substr($get_business->em_valcod,0,4);?>">
<?php echo Html::script('frame/js/lenders.js?v='.time())?>
@stop

@if(count($business)!=0)
<div class="form-group form-empresa">
   <h4 class="title-date-2">SELECCIONAR EMPRESA:</h4>
   <select class="form-control select-1 select-11" id="business" name="business">
   <?php $i=0; ?>
   @foreach($business as $rs)
   <?php $i=$i+1; ?>
   <option value="<?php echo $rs->em_id;?>"
   @if(isset($get_business))
   @if($get_business->em_id==$rs->em_id) selected="selected"  @endif
   @else
   @if($i==1) selected="selected" @endif
   @endif
   ><?php echo mb_substr(mb_strtoupper(mb_convert_encoding($rs->em_nomfan, 'UTF-8', 'UTF-8')),0,40);?></option>
   @endforeach
   </select>
    <h4 class="title-date-2 mtpp21">SELECCIONAR SUCURSAL:</h4>
   <select class="form-control select-1 select-11" id="branch" name="branch">
   </select>
</div>
<div class="bklst2" id="panel-control">
   <div class="bklst2-header">
      <h4>PANEL DE CONTROL</h4>
   </div>
   <div class="bklst2-body">
      <ul>
         <li onclick="goToEmp('agenda/sucursal')" @if(isset($menu_diary)) class="active" @endif><a>Agenda</a></li>
         <li onclick="goToEmp('sucursal/editar')" @if(isset($menu_editar)) class="active" @endif><a>Editar</a></li>
         <li id="item-sb" @if(isset($menu_settings) || isset($menu_shedules) || isset($menu_reports)) class="active" @endif>
            <a>Configuración <i id="angle" class="fa fa-angle-down" aria-hidden="true"></i></a>
            <ul class="submenu-pp" id="submenu" style="display: none;">
               <li onclick="goToEmp('sucursal/configuracion')" @if(isset($menu_settings)) class="active" @endif><a>Sucursal</a></li>
               <li onclick="goToEmp('sucursal/horarios')" @if(isset($menu_shedules)) class="active" @endif><a>Días y horarios de atención</a></li>
               <li onclick="goToEmp('sucursal/generar-reportes')" @if(isset($menu_reports)) class="active" @endif ><a>Generar Reportes </a></li>
            </ul>
         </li>
         <li onclick="goToEmp('sucursal/notificaciones')"  @if(isset($menu_notifications)) class="active" @endif><a>Notificaciones</a></li>
         <li onclick="goToEmp('sucursal/dias-no-laborables')" @if(isset($menu_not_working)) class="active" @endif> <a>Días no laborables / Feriados</a></li>
         <li onclick="goToEmp('prestador/nuevo')" @if(isset($menu_new_lender)) class="active" @endif><a>Nuevo Prestador</a></li>
         <li onclick="goToEmp('sucursal/prestadores')" @if(isset($menu_lenders)) class="active" @endif><a>Ver Prestadores</a></li>
         <li onclick="goToEmp('sucursal/baja')" @if(isset($menu_delete)) class="active" @endif><a>Eliminar sucursal</a></li>
      </ul>
   </div>
</div>
@endif
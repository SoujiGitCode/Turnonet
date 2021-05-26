
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
     <h4 class="title-date-2 mtpp21">SELECCIONAR PRESTADOR:</h4>
   <select class="form-control select-1 select-11" id="lender" name="lender">
   </select>
</div>
<div class="bklst2" id="panel-control">
   <div class="bklst2-header">
      <h4>PANEL DE CONTROL</h4>
   </div>
   <div class="bklst2-body">
      <ul>
         <li onclick="goToEmp('agenda/prestador')" @if(isset($menu_diary)) class="active" @endif><a>Agenda</a></li>
         <li onclick="goToEmp('agenda/disponibilidad')"><a>Agendar turno </a></li>
         <li onclick="goToEmp('prestador/editar')" @if(isset($menu_editar)) class="active" @endif><a>Editar</a></li>
         <li id="item-sb" @if(isset($menu_settings) || isset($menu_shedules) ||  isset($menu_notes) || isset($menu_reports)) class="active" @endif>
            <a>Configuración <i id="angle" class="fa fa-angle-down" aria-hidden="true"></i></a>
            <ul class="submenu-pp" id="submenu" style="display: none;">
               <li onclick="goToEmp('prestador/configuracion')" @if(isset($menu_settings)) class="active" @endif><a>Prestador</a></li>
               <li onclick="goToEmp('prestador/horarios')" @if(isset($menu_shedules)) class="active" @endif><a>Días y horarios de atención</a></li>
               <li onclick="goToEmp('prestador/observaciones')" @if(isset($menu_notes)) class="active" @endif ><a>Observaciones</a></li>
               <li onclick="goToEmp('prestador/generar-reportes')" @if(isset($menu_reports)) class="active" @endif ><a>Generar Reportes </a></li>
            </ul>
         </li>
         <li onclick="goToEmp('prestador/obras-sociales')"  @if(isset($menu_social_works)) class="active" @endif ><a>Obras sociales</a></li>
         <li onclick="goToEmp('prestador/servicios')"  @if(isset($menu_servicios)) class="active" @endif ><a>Servicios</a></li>
         <li onclick="goToEmp('prestador/especialidades')"  @if(isset($menu_especialidades)) class="active" @endif ><a>Especialidades</a></li>
         <li onclick="goToEmp('prestador/notificaciones')" @if(isset($menu_notifications)) class="active" @endif><a>Notificaciones</a></li>
         <li onclick="goToEmp('prestador/dias-no-laborables')" @if(isset($menu_not_working)) class="active" @endif><a>Días no laborables / Feriados</a></li>
         <li onclick="goToEmp('prestador/baja')" @if(isset($menu_delete)) class="active" @endif><a>Eliminar prestador</a></li>
      </ul>
   </div>
</div>
@endif
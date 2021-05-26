<div class="form-group form-empresa">
   @if(isset($act_business))
   <button class="btn btn-success btn-block" title="Registra una nueva empresa" type="button" onclick="window.location='<?php echo url('empresa/nueva');?>'"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nueva Empresa</button>
   @endif
   @if(isset($act_diary))
   <button class="btn btn-success btn-block" title="Registra un nuevo turno" type="button" onclick="window.location='<?php echo url('agenda/prestadores/'.$get_business->em_id);?>'"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo turno</button>
   @endif
   <br>
   @if(count($business)!=0)
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
   @endif
</div>
@if(count($business)!=0)
<div class="bklst2">
   <div class="bklst2-header">
      <h4>PANEL DE CONTROL</h4>
   </div>
   <div class="bklst2-body">
      <ul>
         @if(isset($get_business))
         <li onclick="goToEmp('agenda/empresa')" @if(isset($menu_agenda)) class="active" @endif><a>Agenda</a></li>
         <li onclick="goToEmp('directorio/empresa')" @if(isset($menu_directorio)) class="active" @endif><a>Directorio</a></li>
         @endif

         @if(Auth::guard('user')->User()->level=='2' && Auth::guard('user')->User()->rol=='1' && isset(Auth::guard('user')->User()->pres_id) && Auth::guard('user')->User()->pres_id != "" && Auth::guard('user')->User()->pres_id != "0")

 <li onclick="goToEmp('empresa/generar-reportes')" @if(isset($send_report)) class="active" @endif ><a>Generar Reportes </a></li>
         @endif

         @if(Auth::guard('user')->User()->level=='1' )
         <li onclick="goToEmp('empresa/editar')" @if(isset($menu_editar)) class="active" @endif><a>Editar</a></li>
         <li id="item-sb" @if(isset($menu_settings) || isset($menu_shedules) ||  isset($menu_required) || isset($menu_report) || isset($menu_mercadopago) || isset($send_report)) class="active" @endif >
            <a>Configuración <i id="angle" class="fa fa-angle-down" aria-hidden="true"></i></a>
            <ul class="submenu-pp" id="submenu" style="display: none;">
               <li onclick="goToEmp('empresa/configuracion')" @if(isset($menu_settings)) class="active" @endif><a>Empresa</a></li>
               <li onclick="goToEmp('empresa/mercado-pago')" @if(isset($menu_mercadopago)) class="active" @endif><a>Mercado pago </a></li>
               <li onclick="goToEmp('empresa/horarios')" @if(isset($menu_shedules)) class="active" @endif><a>Días y horarios de atención</a></li>
               <li onclick="goToEmp('empresa/datos-requeridos')" @if(isset($menu_required)) class="active" @endif><a>Datos requeridos</a></li>
               <li onclick="goToEmp('empresa/reportes')" @if(isset($menu_report)) class="active" @endif><a>Reportes</a></li>
               <li onclick="goToEmp('empresa/generar-reportes')" @if(isset($send_report)) class="active" @endif ><a>Generar Reportes </a></li>
               <li onclick="goToEmp('empresa/lista-negra')" @if(isset($blacklist)) class="active" @endif ><a>Lista Negra </a></li>
               
            </ul>
         </li>
         <li onclick="goToEmp('empresa/obras-sociales')" @if(isset($menu_social_work)) class="active" @endif><a>Obras sociales</a></li>
         <li onclick="goToEmp('empresa/notificaciones')"  @if(isset($menu_notifications)) class="active" @endif><a>Notificaciones</a></li>
         <li onclick="goToEmp('empresa/dias-no-laborables')"  @if(isset($menu_not_working)) class="active" @endif><a>Días no laborables / Feriados</a></li>
         <li onclick="goToEmp('empresa/frame')" @if(isset($menu_frame)) class="active" @endif ><a>Turnonet en mi website</a></li>
         <li onclick="goToEmp('sucursal/nueva')" @if(isset($menu_sucursal)) class="active" @endif><a>Nueva sucursal</a></li>
         <li onclick="goToEmp('empresa/estadisticas')" @if(isset($menu_stadistica)) class="active" @endif><a>Estadisticas </a></li>
         <li onclick="goToEmp('empresa/administradores')" @if(isset($menu_admin)) class="active" @endif><a>Administradores</a></li>
         <li onclick="goToEmp('empresa/baja')" @if(isset($menu_delete)) class="active" @endif><a>Eliminar empresa</a></li>
         @endif
      </ul>
   </div>
</div>
@endif
 <div id="myModalA" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content-1">
            <div class="modal-header-1">
               <h3 id="title-modal">Agenda de Turnos</h3>
               <button type="button" class="close" onclick="closeModalAgenda()">&times;</button>
            </div>
            <div class="modal-body-1">
               <form id="form">
                   <div class="row">
                     <div class="col-md-12 col-xs-12 text-center">
                        <br>
                        <p>Revisá la disponibilidad y registrá de forma rápida un turno a tu agenda. Esta opción no permite el registro de datos adicionales en el turno, para registrar el turno con datos adicionales  haga <strong style="color: #f15424; cursor: pointer;" onclick="window.location='<?php echo url('agenda/prestadores/'.$get_business->em_id);?>'">click aquí</strong></p>
                        <br>
                     </div>
                  </div>
               <div class="row" id="agenda">
                  @if(Auth::guard('user')->User()->level=='1' || Auth::guard('user')->User()->rol!='1')
                  <div class="col-md-6 col-sm-6 col-xs-12">
                     <label>SUCURSAL</label>
                     <select class="form-control select-1" id="branch-2" name="branch-2" onchange="changeSucursal2()" >
                        <option value="">Seleccionar sucursal</option>
                        @foreach($branch as $rs)
                        <option value="<?php echo $rs->suc_id;?>"><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                     <label>PRESTADOR</label>
                     <select class="form-control select-1" id="lenders-2" name="lenders-2" onchange="changePrestador2()">
                        <option value="">Seleccionar Prestador</option>
                        @foreach($lenders as $rs)
                        <option value="<?php echo $rs->tmsp_id;?>"><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                        @endforeach
                     </select>
                  </div>
                  @else
                  <div class="col-md-6 col-sm-6 col-xs-12">
                     <label>SUCURSAL</label>
                     @if(isset(Auth::guard('user')->User()->suc_id) && Auth::guard('user')->User()->suc_id!="" && Auth::guard('user')->User()->suc_id!="0" )
                     <select class="form-control select-1" id="branch-2" name="branch-2" disabled="disabled"  onchange="changeSucursal2()" >
                        <option value="">Seleccionar sucursal</option>
                        @foreach($branch as $rs)
                        <option value="<?php echo $rs->suc_id;?>" @if($rs->suc_id==Auth::guard('user')->User()->suc_id) selected="selected" @endif><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                        @endforeach
                     </select>
                     @else
                     <select class="form-control select-1" id="branch-2" name="branch-2"  onchange="changeSucursal2()">
                        <option value="">Seleccionar sucursal</option>
                        @foreach($branch as $rs)
                        <option value="<?php echo $rs->suc_id;?>"><?php echo ucwords(mb_strtolower($rs->suc_nom));?></option>
                        @endforeach
                     </select>
                     @endif
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                     <label>PRESTADOR</label>
                     @if(isset(Auth::guard('user')->User()->pres_id) && Auth::guard('user')->User()->pres_id!="" && Auth::guard('user')->User()->pres_id!="0")
                     <select class="form-control select-1" id="lenders-2" name="lenders-2" disabled="disabled" onchange="changePrestador2()">
                        <option value="">Seleccionar Prestador</option>
                        @foreach($lenders as $rs)
                        <option value="<?php echo $rs->tmsp_id;?>" @if($rs->tmsp_id==Auth::guard('user')->User()->pres_id) selected="selected" @endif ><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                        @endforeach
                     </select>
                     @else
                     <select class="form-control select-1" id="lenders-2" name="lenders-2" onchange="changePrestador2()">
                        <option value="">Seleecionar Prestador</option>
                        @foreach($lenders as $rs)
                        <option value="<?php echo $rs->tmsp_id;?>"  ><?php echo ucwords(mb_strtolower($rs->tmsp_pnom));?></option>
                        @endforeach
                     </select>
                     @endif
                  </div>
                  @endif
                  <div class="col-md-12 col-sm-12 col-xs-12" id="reps-servs">
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <br>
                     <label>FECHA</label>
                     <input type='text' id='fecha_turno' name="tu_fec" class="form-control" value="<?php echo date('Y-m-d');?>" placeholder="Fecha del Turno" />
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <br>
                     <div class="demoo1">
                        <input type="checkbox" name="tu_st" id="tu_st" value="1">
                        <label for="tu_st"><span></span>Sobreturno</label>
                     </div>
                     <br>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                     <br>
                     <button  class="btn btn-primary" name="button" type="button" ng-click="disponibilidad()">VER DISPONIBILIDAD</button>
                  </div>
               </div>
               <div class="row" id="horas" style="display: none;">
                  <br><br>
                  <div class="col-md-12  col-sm-12 col-xs-12 content-spinner" id="prevloadertimes" style="display: none;">
                     <div class="spinner">
                        <div class="double-bounce1"></div>
                        <div class="double-bounce2"></div>
                     </div>
                  </div>
                  <div class="col-md-12 col-xs-12" style="display: none; padding: 0px" id="prevtimes">
                     <div class="col-md-12 col-xs-12 text-center" ng-show="totalItems_times==0">
                              <img src="<?php echo url('/');?>/uploads/icons/noresult.png" class="img-not-res">
                              <p class="text-date text-center">@{{text_not}}<br><br></p>
                              <div class="row">
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button  class="btn btn-block btn-primary" name="button" type="button"  onclick="closeHoras()">Seleccioná otra fecha</button>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                              </div>
                           </div>
                     <div class="col-md-12" ng-show="totalItems_times!=0">
                        <p class="text-date-33 text-center">@{{name_day}}</p>
                        <br>
                        <div class="times" ng-repeat="row in list_times">
                           <div class="hora" >
                              <a ng-click="selectTime(row.time,row.id)" id="time-@{{row.id}}" title="@{{row.title}}" class="cturno">@{{row.time_format}}</a>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12 col-xs-12" style="clear: both; padding: 0px"  ng-show="totalItems_times!=0">
                        <p class="text-date-33 text-center">Toca para seleccioná un horario</p>
                        <div class="row">
                           <div class="col-md-3 col-sm-3 col-xs-12">
                           </div>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                              <button  class="btn btn-block btn-primary" name="button" type="button" onclick="closeHoras()">Volver Atrás</button>
                              <button class="btn btn-block btn-info" id="btn-create" type="button" disabled name="button" onclick="cliente()">Agenda Rápida</button>
                           </div>
                           <div class="col-md-3 col-sm-3 col-xs-12">
                           </div>
                        </div>
                     </div>
                  </div>
                  
               </div>
               <div class="row" id="datos" style="display: none;">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group-1 ss-i">
                           <p class="form-desc"> Seleccioná un cliente  existente o registrá uno nuevo haciendo <strong style="color: #f15424; cursor: pointer;" onclick="window.location='<?php echo url('directorio/nuevo/'.$get_business->em_id);?>'">click aquí</strong> </p>
                        </div>
                        <div class="name-search ss-i">
                           <div class="input-group">
                              <span class="input-group-addon" style="border-top-left-radius: 3rem;
                                 border-bottom-left-radius: 3rem;" onclick="filtro()"><i class="fa fa-search"></i></span>
                              <input type="text" id="searchc" onkeypress="enter_filtro(event)" onchange="filtro()"  type="text" placeholder="Ingresá el nombre o correo del cliente" class="form-control" style="border-top-right-radius: 3rem; border-bottom-right-radius: 3rem" />
                           </div>
                           <ul class="search-items" style="display: none;">
                              <li ng-repeat="row in list_customers | limitTo:5" ng-click="asignarValor(row)">@{{row.name}} <span class="turr">@{{row.shift}}</span>
                              </li>
                              <li ng-show="totalItems_customers == 0">No hay resultados para mostrar</li>
                           </ul>
                        </div>
                        <div class="form-group btop"  id="panel-user" style="display: none;">
                           <label class="label-1">DATOS DEL CLIENTE:</label>
                           <div id="data-user"></div>
                           <label class="edit-profile-1" onclick="removeUser()" title="Eliminar cliente"><i class="fa fa-times"></i></label>

                           <br>
                        
                        </div>
                     </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="demoo1">
                              <input type="checkbox" name="aviso" id="aviso" value="1" checked="checked">
                              <label for="aviso"><span></span>Enviar email de aviso al cliente</label>
                            </div>
                            <br>
                         </div>
                           <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button class="btn btn- btn-info"  id="btn-create-2" type="button" name="button" onclick="guardar()">Agendar Turno</button>
                         </div>
                  </div>
            </div>
         </form>
         </div>
      </div>
   </div>
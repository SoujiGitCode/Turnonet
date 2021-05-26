@extends('layouts.template_frame')
@section('content')
    <?php  $frame = DB::table('frame')->where('emp_id', $get_business->em_id)->first(); ?>
    @if($lenders>1)
        @if($frame->header==0)
            <a href="<?php echo url('/').'/'.$frame->url;?>" class="btn-back"></a>
        @endif
    @endif
    <form id="form">
        <div class="container container-single ptop" ng-app="myApp" ng-controller="post">


            @if($frame->name==0)
                <div class="col-md-2 col-sm-3 col-xs-12 hidden-xs">
                    <?php
                    $image=url('/').'/img/emptylogo.png';

                    $get_business=DB::table('tu_emps')->where('em_id',$lender->emp_id)->first();

                    $sucursal=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first();

                    if(isset($get_business)!=0 && $get_business->em_pfot!=""){
                        $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
                    }
                    if(isset($sucursal)!=0 && $sucursal->suc_pfot!=""){
                        $image="https://www.turnonet.com/fotos/sucursales/".$sucursal->suc_pfot;
                    }
                    if($lender->tmsp_pfot!=""){
                        $image="https://www.turnonet.com/fotos/prestadores/".$lender->tmsp_pfot;
                    }

                    ?>
                    <img src="<?php echo $image;?>" class="img-press-1">
                </div>
                <div class="col-md-10 col-sm-9 col-xs-12 no-padding-desktop">
                    <div class="tit" style="margin-bottom: 0vw;">


                        <?php echo $lender->tmsp_tit;?> <?php echo mb_strtoupper($lender->tmsp_pnom);?>
                    </div>
                    <?php $address=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first(); ?>
                    @if(isset($address)!=0)
                        <div class="subtit "> <?php echo $address->suc_dom;?> <?php echo $address->suc_domnum;?> <?php echo $address->suc_dompiso;?> </div>
                    @endif
                </div>
            @endif
            <div class="row">
                

                @if($lender->activate_zoom!=0)

                <div class="col-xs-12" style="padding-left:30px; padding-right: 30px">
                    <br>
                    <div class="alert alert-primary" role="alert">
                        Su cita se realizará a través de videoconferencia. Los detalles serán enviados por correo electrónico y podrás revisarlo en el detalle del turno. <strong>Al Ingresar a la sala debe hacerlo con el nombre con el que se reservó el turno</strong>
                  </div>
              </div>
              @endif
              

               
                <div class="col-xs-12">
                    <div id="capa">
                        <div class="col-xs-12">
                            <div class="mustbehide" id="relocal">
                                @if(count($services)>=1)
                                    <input type="hidden" id="service_select" name="tu_servid" value="">
                                    <div class="inbtw">
                                        <div class="servis">
                                            <div class="selserv">
                                                <select name="service" id="service"  class="f_sel form-control">
                                                    <option value="">Seleccioná un servicio</option>
                                                    @foreach($services as $rs)
                                                        <option value="<?php echo $rs->serv_id;?>"><?php echo trim($rs->serv_nom);?></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div id="addeds">
                                                <span id="text-services"></span>
                                                <ul class="submenu" id="submenu">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 content-spinner" id="prevloader">
                            <div class="spinner">
                                <div class="double-bounce1"></div>
                                <div class="double-bounce2"></div>
                            </div>
                        </div>
                        <div class="col-xs-12" id="prevcalendario" style="display: none;">
                            <div class="lodcal">
                                <div id="s_cal">
                                    <div class="row s_day" style="margin-left:0; margin-right:0  ">
                                        <div class="col-xs-2">
                                            <a ng-click="prevMonth()" ng-show="prevmonth!=month_act || month_act!=month_select"  class="relcal"><img src="https://i.imgur.com/FGybL6Z.png" class="arrow-prev" id="arrowprev"></a>
                                        </div>
                                        <div class="col-xs-8">
                                            <div class="">@{{name_month}}</div>
                                        </div>
                                        <div class="col-xs-2">
                                            <a class="relcal" ng-click="nextMonth()"><img src="https://i.imgur.com/R8865BH.png" class="arrow-next"></a>
                                        </div>
                                    </div>
                                    <div class="s_sd">L</div>
                                    <div class="s_sd">M</div>
                                    <div class="s_sd">M</div>
                                    <div class="s_sd">J</div>
                                    <div class="s_sd">V</div>
                                    <div class="s_sd">S</div>
                                    <div class="s_sd" style="border-right: none!important;">D</div>
                                    <div ng-repeat="row in list_calendar" class="@{{row.class}}">
                                        <div ng-show="row.date!='' && row.active=='1'" class="circlegreen" ng-click="selectDay(row.date)" title="@{{row.title}}" >@{{row.day}}</div>
                                        <div ng-show="row.date!='' && row.active=='0'" class="circlepink">@{{row.day}}</div>
                                    </div>
                                </div>
                                </script>
                                </div>
                                </div>
                                </div>
                                @if($lenders>1)
                                <div class="col-xs-12">
                                    <div class="row hidden-xs">
                                    <div class="col-md-8 col-sm-6">
                                    </div>
                                    <div class="col-md-4  col-sm-6 hidden-xs">
                                    <div class="volver2">
                                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                                    <a href="<?php echo url('/').'/'.$frame->url;?>">Ver Prestadores</a>
                                </div>
                                </div>
                                </div>
                                </div>
                                @endif
                                </div>
                                </div>
                                <div id="myModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header">
                                    <h3 id="title-modal">Horarios</h3>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row"  id="horarios">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12  col-sm-12 col-xs-12 content-spinner" id="prevloadertimes">
                                    <div class="spinner">
                                    <div class="double-bounce1"></div>
                                    <div class="double-bounce2"></div>
                                    </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12" style="display: none; padding: 0px" id="prevtimes">
                                    <p class="text-date-33 text-center">@{{name_day}}</p>
                                <div class="col-md-12 col-xs-12 text-center" ng-show="totalItems_times==0">
                                    <img src="<?php echo url('/');?>/uploads/icons/noresult.png" class="img-not-res">
                                    <p class="text-date text-center">No quedan turnos disponibles para la fecha seleccionada.<br><br></p>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button  class="btn btn-block btn-default-1" name="button" type="button" onclick="closeModal()">Seleccioná otra fecha</button>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    </div>
                                    </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 text-center" style="clear: both; padding: 0px"  ng-show="totalItems_times!=0">
                                    <div class="times" ng-repeat="row in list_times">
                                    <div class="hora" >
                                    <a ng-click="selectTime(row.time,row.text,row.id)" id="time-@{{row.id}}" title="@{{row.title}}" class="cturno">@{{row.time_format}}</a>
                                </div>
                                </div>
                                </div>
                                <div class="col-md-12 col-xs-12" style="clear: both; padding: 0px"  ng-show="totalItems_times!=0">
                                    <p class="text-date-33 text-center">Toca para seleccioná un horario</p>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button  class="btn btn-block btn-default-1" name="button" type="button" onclick="closeModal()">Seleccioná otra fecha</button>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="row" id="datos" style="display: none;">
                                    <div class="col-md-12">
                                    <div class="form-group">
                                    <p class="text-date-33 text-center" id="fecha"></p>
                                    </div>
                                    <div class="form-group">
                                    <label>Nombre completo: <small style="color: #fd2923">*</small></label>
                                <input type="text" name="name" id="nombrec" class="form-control" placeholder="Ingresá tu nombre">
                                    </div>
                                    <?php
                                    //confinguracion de la empresa
                                    $gmt = DB::table('tu_emps_con')->where('emp_id', $lender->emp_id)->where('suc_id', '0')->where('pres_id', '0')->first();
                                    //confinguracion de la sucursal
                                    $gmt = DB::table('tu_emps_con')->where('suc_id', $lender->suc_id)->where('pres_id', '0')->first();
                                    //confinguracion del prestador
                                    $gmt = DB::table('tu_emps_con')->where('pres_id', $lender->tmsp_id)->first();
                                    ?>
                                        @if(isset($gmt)!=0 && ($gmt->cf_tipval=='5' || $gmt->cf_tipval=='10'))
                                    <div class="form-group">
                                    <label>DNI: <small style="color: #fd2923">*</small></label>
                                <input type="text" name="dni" id="dni" class="form-control"  placeholder="Ingresá tu DNI">
                                    </div>
                                        @endif
                                    <div class="form-group">
                                    <label>Correo electrónico: <small style="color: #fd2923">*</small></label>
                                <input type="email" name="email" id="emailrec" class="form-control"  placeholder="Ingresá tu correo electrónico">
                                    </div>
                                    <div class="form-group">
                                    <label>Confirmá tu correo electrónico: <small style="color: #fd2923">*</small></label>
                                <input type="email" name="conemailrec" id="conemailrec" autocomplete="off" class="form-control" value="" placeholder="Ingresá tu correo electrónico">
                                    </div>
                                    <div class="form-group">
                                    <label style="color: #fd2923!important">Campos requeridos (*)</label>
                                </div>
                                </div>
                                <div class="col-md-3 col-sm-3 hidden-xs">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    <button class="btn btn-block btn-default" name="button" id="boton-1" type="button" onclick="guardar()"><i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Turno</button>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    </div>
                                    <div class="col-md-3 col-sm-3 hidden-xs">
                                    </div>
                                    <div class="col-md-3 col-sm-3 hidden-xs" style="clear: both;">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 text-center">
                                    <a class="btn btn-block btn-default-1" onclick="closeModal()">Seleccioná otra fecha</a>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 ">
                                    </div>
                                    <div class="col-md-3 col-sm-3 hidden-xs">
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    <input type="hidden" name="emp_id" id="empid" value="<?php echo $lender->emp_id;?>">
                                    <input type="hidden" name="suc_id" id="sucid" value="<?php echo $lender->suc_id;?>">
                                    <input type="hidden" name="pres_id" id="presid" value="<?php echo $lender->tmsp_id;?>">
                                    <input type="hidden" id="url_business" name="url_business" value="<?php echo $frame->url;?>">
                                    <input type="hidden" id="url_lender" name="url_lender" value="<?php echo $lender->url;?>">
                                    <input type="hidden" name="vadcod" id="vadcod" value="<?php echo substr($get_business->em_valcod,0,4);?>">
                                    <input type="hidden" name="tu_fec" id="date">
                                    <input type="hidden" name="tu_hora"  id="times">
                                    <input type="hidden" id="inputs_add" name="inputs_add" value="<?php echo count($inputs_add);?>">
                                    <input type="hidden" id="month" value="<?php echo date("m");?>">
                                    <input type="hidden" id="year" value="<?php echo date("Y");?>">
                                    </form>
    <?php echo Html::script('frame/js/create_shift.js?v='.time())?>
@stop
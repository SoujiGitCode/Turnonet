    @extends('layouts.template_frontend_inside')
    @section('content')
    <?php
    function nameMonth($month) {
        switch ($month) {
            case 1:
                $month = 'Enero';
                break;
            case 2:
                $month = 'Febrero';
                break;
            case 3:
                $month = 'Marzo';
                break;
            case 4:
                $month = 'Abril';
                break;
            case 5:
                $month = 'Mayo';
                break;
            case 6:
                $month = 'Junio';
                break;
            case 7:
                $month = 'Julio';
                break;
            case 8:
                $month = 'Agosto';
                break;
            case 9:
                $month = 'Septiembre';
                break;
            case 10:
                $month = 'Octubre';
                break;
            case 11:
                $month = 'Noviembre';
                break;
            case 12:
                $month = 'Diciembre';
                break;
        }
        return $month;
    }

    function nameDay($day) {

        switch ($day) {
            case 0:
                $day = 'Domingo';
                break;
            case 1:
                $day = 'Lunes';
                break;
            case 2:
                $day = 'Martes';
                break;
            case 3:
                $day = 'Miércoles';
                break;
            case 4:
                $day = 'Jueves';
                break;
            case 5:
                $day = 'Viernes';
                break;
            case 6:
                $day = 'Sábado';
                break;
        }
        return $day;
    }

    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
                <h4 class="title-date-2">AGENDA</h4>
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px">
                    <div class="panel">
                        <div class="panel-heading">
                            Detalle del Turno
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel-pres">
                                        <div class="col-xs-1 no-padding-desktop">
                                            <?php
                                            $image=url('/').'/img/empty.jpg';

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
                                            <img src="<?php echo $image;?>" class="img-pres">
                                        </div>
                                        <div class="col-xs-11 no-padding-desktop">
                                            <div class="tit" style="margin-bottom: 0vw;">
                                                <?php echo mb_strtoupper($lender->tmsp_pnom);?>
                                            </div>
                                            <?php $address=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first(); ?>
                                            @if(isset($address)!=0)
                                            <div class="subtit "> <?php echo $address->suc_dom;?> <?php echo $address->suc_domnum;?> <?php echo $address->suc_dompiso;?> </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group btop" >
                                        <label class="label-1">Turno:</label>
                                        <p class="p-1"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo $shift->tu_code;?></p>
                                        <?php
                                        $time = (date("H", strtotime($shift->tu_hora)) <= 11) ? 'AM' : 'PM';

                                        ?>
                                        <p class="p-1"><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo nameDay(date("w", strtotime($shift->tu_fec))) . ' ' . date("d", strtotime($shift->tu_fec)) . ' de ' . NameMonth(date("m", strtotime($shift->tu_fec))); ?> a las <?php echo date("H:i", strtotime($shift->tu_hora));?> <?php echo $time;?></p>
                                    </div>
                                    <div class="form-group btop" >
                                        <label class="label-1">DATOS DEL CLIENTE:</label>
                                        <p class="p-1" title="Ver datos del cliente" onclick="actModalPac('<?php echo $user->id;?>','<?php echo $shift->tu_id;?>')" style="cursor: pointer;"><i class="fa fa-search"></i> <?php echo mb_strtoupper($user->name);?></p>
                                        @if($user->email!="")
                                        <p class="p-1" onclick="window.open('mailto:<?php echo strtolower($user->email);?>')"><i class="fa fa-envelope-o"></i> <?php echo strtolower($user->email);?></p>
                                        @endif
                                    </div>
                                    @if($shift->tu_servid!="0" &&  $shift->tu_servid!="")
                                    <div class="form-group btop" >
                                        <label class="label-1">SERVICIOS SOLICITADOS:</label>
                                        <?php
                                        if (substr_count($shift->tu_servid, '-') <= 0){


                                            $get_service = DB::table('tu_emps_serv')->where('serv_id',$shift->tu_servid)->first();

                                            if (isset($get_service) != 0) {
                                                $services .= trim($get_service->serv_nom);

                                                echo '<p class="p-1 text-uppercase">'.trim($get_service->serv_nom).'</p>';
                                            }

                                        }else{


                                            for ($i = 0; $i <= substr_count($shift->tu_servid, '-'); $i++) {
                                                $service = explode('-', $shift->tu_servid);
                                                $get_service = DB::table('tu_emps_serv')->where('serv_id',$service[$i])->first();

                                                if (isset($get_service) != 0) {
                                                    $services .= trim($get_service->serv_nom);

                                                    echo '<p class="p-1">'.trim($get_service->serv_nom).'</p>';
                                                }

                                            }
                                        }

                                        ?>
                                    </div>
                                    @endif
                                    @if($shift->tu_durac!="00:00:00")
                                    <div class="form-group btop" >
                                        <label class="label-1">Duración:</label>
                                        @if(date("H", strtotime($shift->tu_durac)) <01)
                                        <p class="p-1"><i class="fa fa-clock-o"></i> <?php echo date("i", strtotime($shift->tu_durac));?> min</p>
                                        @else
                                        <p class="p-1"><i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($shift->tu_durac));?> Hr</p>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="form-group btop" >
                                        <label class="label-1">Estado:</label>
                                        <p class="p-1"> <?php echo $shift->tu_estado;?></p>
                                    </div>


                                    @if($shift->url_zoom!="")

                                    <div class="form-group btop" >
                                        <label class="label-1">MEETING:</label>
                                        <div class="alert alert-warning" role="alert" style="word-wrap: break-word cursor: pointer;">
                                            <a href="<?php echo $shift->url_zoom;?>" target="_blank" title="Abrir sala" style="cursor: pointer;"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> URL: <?php echo $shift->url_zoom;?> </a><br>                                          
                                            <div class="b-copy">
                                            <button id="copy-pass"  onclick="CopyToClipboard()">Copiar <img src="https://img.icons8.com/carbon-copy/2x/copy.png"  class="copy-clipboard" alt="copiar url">
                                            </button>
                                        </div>

                                        </div>
                                    </div>

                                    @endif

                                    @if($shift->tu_estado=="BAJA")

                                    <div class="form-group btop" >
                                        <label class="label-1">Cancelado por:</label>

                                        <?php $motivo=DB::table('tu_tucan')->where('tucan_turid',$shift->tu_id)->first(); ?>
                                        @if(isset($motivo)!=0)
                                        <?php $person=DB::table('tu_users')->where('us_id',$motivo->tucan_usid)->first(); ?>
                                        @if(isset($person)!=0)
                                        <p class="p-1"> <?php echo $person->us_nom;?></p>
                                        @endif
                                        @endif
                                    </div>

                                    <div class="form-group btop" >
                                        <label class="label-1">Motivo de cancelación:</label>

                                        @if(isset($motivo)!=0)
                                        <p class="p-1"> <?php echo $motivo->tucan_mot;?></p>
                                        @endif
                                    </div>
                                    @endif

                                    <div class="form-group">
                                        <div class="caret-op9" title="Ver en PDF" onclick="window.open('<?php echo url('pdf/cliente/'.$shift->tu_code.'/'.$get_business->em_id.'/'.$user->id);?>')"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Datos del cliente</div>
                                        <div class="caret-op10" title="Ver en PDF" onclick="window.open('<?php echo url('pdf/turno/'.$shift->tu_code);?>')"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Detalle del turno</div>


                                        <?php $BlockedShedules=DB::table('blocked_schedules')->where('tur_id',$shift->tu_id)->count(); ?>


                                        @if($shift->tu_estado=="BAJA" && $BlockedShedules!=0)

                                        <div class="caret-op10" style="background: #ff2222;
        width: auto;
        border: 1px solid #ff2222;" title="Desbloquear Horario del turno" onclick="desbloquearHorario('<?php echo $shift->tu_id;?>')"><i class="fa fa-clock-o" aria-hidden="true"></i> Desbloquear Horario</div>


                                        @endif
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

    <?php $copy= $shift->url_zoom?>

    <input type="text" style="opacity: 0" id="myInput" name="myInput" value="<?php echo $copy;?>">
    <input type="hidden" id="redirect" value="/agenda/empresa/<?php echo $get_business->em_id;?>">
    <?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>


   

    @stop
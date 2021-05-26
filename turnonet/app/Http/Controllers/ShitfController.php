<?php

namespace App\Http\Controllers;

use verifyEmail;
use App\BlackListMail;
use App\Mail\TuShiftMail;
use App\Mail\TuMailBusiness;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Activities;
use App\UsersApp;
use App\Directory;
use App\LenderNotifications;
use App\ClientsCustomization;
use App\SettingsBusiness;
use App\Lenders;
use App\BlockedShedules;
use App\Business;
use App\Services;
use App\Shift;
use App\SMS;
use DB;
use Mail;
use Cache;
use Session;
use Auth;
use URL;
use DateTime;
use DateInterval;
use DatePeriod;

class ShitfController extends Controller {

    /**
     * Validate access
     */
    public function __construct() {
        $this->middleware('AuthApi');
        include('ValidateMailExistance.php');
    }

    public function reasing(Request $request) {
        try {
            $tu_st = 0;
            if (isset($request['tu_st'])) {
                $tu_st = 1;
            }
            $aviso = 0;
            if (isset($request['aviso'])) {
                $aviso = 1;
            }
            if (Cache::has('settings_business_' . $request['emp_id'])) {
                $gmt = Cache::get('settings_business_' . $request['emp_id']);
            }
            if (Cache::has('settings_branch_' . $request['suc_id'])) {
                $gmt = Cache::get('settings_branch_' . $request['suc_id']);
            }
            if (Cache::has('settings_lender_' . $request['pres_id'])) {
                $gmt = Cache::get('settings_lender_' . $request['pres_id']);
            }
            if (isset($gmt) != 0) {
                $simultaneo = $gmt->cf_simtu;
            } else {
                $simultaneo = 1;
            }
            $date = $request['tu_fec'];
            $bloqued_shift = BlockedShedules::
            where('pres_id', $request['pres_id'])
            ->where('tur_date', date("Y-m-d", strtotime($date)))
            ->where('tur_time', date("H:i:s", strtotime($request['tu_hora'])))
            ->count();
            if ($bloqued_shift >= $simultaneo && $tu_st == 0) {
                return response()->json(["response" => "false"]);
            }
            $shift = Shift::
            where('pres_id', $request['pres_id'])
            ->where('tu_fec', date("Y-m-d", strtotime($date)))
            ->where('tu_estado', '!=', 'BAJA')
            ->where('tu_hora', date("H:i:s", strtotime($request['tu_hora'])))
            ->count();
            if ($shift >= $simultaneo && $tu_st == 0) {
                return response()->json(["msg" => "false"]);
            } else {

                $cpres = DB::table('tu_emps_con')
                ->where('pres_id', $request['pres_id'])
                ->where('emp_id', $request['emp_id'])
                ->count();
                $csuc = DB::table('tu_emps_con')
                ->where('pres_id', $request['pres_id'])
                ->where('suc_id', $request['suc_id'])
                ->where('emp_id', $request['emp_id'])
                ->count();
                if ($csuc == 0 && $cpres == 0) {
                    $gmt = DB::table('tu_emps_con')
                    ->where('emp_id', $request['emp_id'])
                    ->where('suc_id', 0)
                    ->where('pres_id', 0)
                    ->first();
                }
                if ($cpres != 0) {
                    $gmt = DB::table('tu_emps_con')
                    ->where('emp_id', $request['emp_id'])
                    ->where('pres_id', $request['pres_id'])
                    ->first();
                }
                if ($csuc != 0) {
                    $gmt = DB::table('tu_emps_con')
                    ->where('emp_id', $request['emp_id'])
                    ->where('suc_id', $request['suc_id'])
                    ->where('pres_id', $request['pres_id'])
                    ->first();
                }
                $services = '';
                $serv_totime = 0;
                $set_turtime = "00:00:00";
                if (isset($gmt) != 0) {
                    $set_turtime = $gmt->cf_turt;
                }
                BlockedShedules::where('tur_id', $request['tu_id'])->delete();


                $lender = Lenders::find($request['pres_id']);
                if ($lender->activate_zoom == 1) {
                    $this->generate_meeting($request['tu_id']);
                }

                $get_shift = Shift::find($request['tu_id']);
                if ($request['service_select'] != "") {
                    if (substr_count($request['service_select'], '-') <= 0) {
                        $get_service = Services::find($request['service_select']);
                        if (isset($get_service) != 0) {
                            $services .= $get_service->serv_nom;
                            $serv_time = $get_service->serv_tudur;
                            $set_horamt = explode(":", $serv_time);
                            if ($set_horamt[0] != '') {
                                $set_horamt2 = $set_horamt[0] * 60;
                                $max_time = $set_horamt2 + $set_horamt[1];
                                $serv_totime += $max_time;
                            }
                        }
                    } else {
                        for ($i = 0; $i <= substr_count($request['service_select'], '-'); $i++) {
                            $service = explode('-', $request['service_select']);
                            $get_service = Services::find($service[$i]);
                            if (isset($get_service) != 0) {
                                $services .= trim($get_service->serv_nom);
                                $serv_time = $get_service->serv_tudur;
                                $set_horamt = explode(":", $serv_time);
                                if ($set_horamt[0] != '') {
                                    $set_horamt2 = $set_horamt[0] * 60;
                                    $max_time = $set_horamt2 + $set_horamt[1];
                                    $serv_totime += $max_time;
                                }
                            }
                            if ($i != substr_count($request['service_select'], '-')) {
                                $services .= ", ";
                            }
                        }
                    }
                }
                if ($serv_totime > 0 && isset($gmt) != 0) {
                    $def_time = $gmt->cf_turt;
                    $set_extim = explode(":", $def_time);
                    $set_estim2 = $set_extim[0] * 60;
                    $max_deftime = $set_estim2 + $set_extim[1];
                    if ($serv_totime > $max_deftime) {
                        $set_turtime = date("H:i:s", mktime(0, 0 + $serv_totime, 0, 1, 2, 2013));
                    }
                }
                $shift = Shift::find($request['tu_id']);
                $shift->fill([
                    'tu_servid' => $request['service_select'],
                    'tu_st' => $tu_st,
                    'tu_fec' => date("Y-m-d", strtotime($date)),
                    'tu_hora' => date("H:i:s", strtotime($request['tu_hora'])),
                    'tu_durac' => date("H:i:s", strtotime($set_turtime)),
                    'tu_horaf' => $this->sumarHoras(date("H:i:s", strtotime($request['tu_hora'])), date("H:i:s", strtotime($set_turtime))),
                    'tu_estado' => 'ALTA',
                ]);
                $shift->save();
                $dinit = date("H:i:s", strtotime($request['tu_hora']));
                $dend = $this->sumarHoras(date("H:i:s", strtotime($request['tu_hora'])), date("H:i:s", strtotime($set_turtime)));
                $get_shift = Shift::find($request['tu_id']);
                if (date("H", strtotime($dinit)) <= 24 && date("H", strtotime($dend)) <= 24) {
                    $interval = new DateInterval('PT5M');
                    $period_empty = new DatePeriod(new DateTime($dinit), $interval, new DateTime($dend));
                    foreach ($period_empty as $hour) {
                        if ($dend > $hour->format('H:i:s')) {
                            BlockedShedules::create([
                                'tur_id' => $request['tu_id'],
                                'us_id' => $get_shift->us_id,
                                'pres_id' => $get_shift->pres_id,
                                'tur_date' => date("Y-m-d", strtotime($date)),
                                'tu_hora' => $request['tu_hora'],
                                'tur_time' => $hour->format('H:i:s'),
                                'tur_status' => 'ALTA',
                                'tu_bloqhor' => date("H:i:s"),
                            ]);
                        }
                    }


                    
                }
                $this->audit('Reasignación  de turno ID #' . $get_shift->tu_code);
                $lender = Lenders::find($request['pres_id']);

                

                $business = Business::find($get_shift->emp_id);
                $address = DB::table('tu_emps_suc')->where('suc_id', $request['suc_id'])->first();
                $user = Directory::where('us_id', $get_shift->us_id)->where('emp_id', $get_shift->emp_id)->first();
                $time = (date("H", strtotime($request['tu_hora'])) <= 12) ? 'AM' : 'PM';
                $create = $this->nameDay(date("w", strtotime($date))) . ', ' . date("d", strtotime($date)) . ' de ' . $this->NameMonth(date("m", strtotime($date))) . ' del  ' . date("Y", strtotime($date));
                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))) . '.<br/>
                <br/>
                Te informamos a través de este correo electrónico que el  turno Nº ' . $get_shift->tu_code . ' ha sido reasignado para el  ' . $create . ' a las ' . date("H:i", strtotime($request['tu_hora'])) . ' ' . $time . '.<br><br>
                <strong style="color:#FF5722">DATOS DEL TURNO:</strong><br/>
                <strong>Cliente:</strong>  ' . mb_strtoupper(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8')) . '<br/>
                <strong>Código:</strong> ' . $get_shift->tu_code . '<br/>
                <strong>Empresa:</strong> ' . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8')) . '<br/>
                <strong>Prestador:</strong>  ' . mb_strtoupper(mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8')) . '<br/>';
                if (isset($address) != 0) {
                    $content .= '<strong>Dirección:</strong> ' . $address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso . '<br/>';
                }
                if ($services != '') {
                    $content .= '<strong>Servicios solicitados:</strong> ' . $services . '<br/>';
                }
                if ($get_shift->url_zoom != '') {
                    $content .= '<strong>Url Meeting:</strong> ' . $get_shift->url_zoom . '<br/>';
                }
                $content .= '<strong>Motivo de reasignación:</strong> ' . $request['message'] . '<br/>';
                $content .= '<br>Para más información comunicate con ' . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8')) . '.';
                $notifications_lender = LenderNotifications::
                where('pc_presid', $lender->tmsp_id)
                ->first();
                $notifications_branch = LenderNotifications::
                where('pc_sucid', $get_shift->suc_id)
                ->first();
                $notifications_business = LenderNotifications::
                where('pc_empid', $business->em_id)
                ->first();
                if (isset($notifications_lender) != 0 && $notifications_lender->pc_msg != "") {
                    $content .= '<br><strong>Notificaciones:</strong><br>';
                    $content .= $this->setNoty(mb_convert_encoding($notifications_lender->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    if ($notifications_lender->pc_suc_msg == 1 && isset($notifications_branch) != 0 && $notifications_lender->pc_msg != $notifications_branch->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_branch->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                    if ($notifications_lender->pc_emp_msg == 1 && isset($notifications_business) != 0 && $notifications_lender->pc_msg != $notifications_business->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                }
                if (isset($notifications_branch) != 0 && isset($notifications_branch) == 0 && $notifications_branch->pc_msg != "") {
                    $content .= '<br><strong>Notificaciones:</strong><br>';
                    $content .= $this->setNoty(mb_convert_encoding($notifications_branch->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    if ($notifications_branch->pc_emp_msg == 1 && isset($notifications_business) != 0 && $notifications_branch->pc_msg != $notifications_business->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                }
                if (isset($notifications_business) != 0 && isset($notifications_branch) == 0 && isset($notifications_lender) == 0 && $notifications_business->pc_msg != "") {
                    $content .= '<br><strong>Notificaciones:</strong><br>';
                    $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                }
                $replyto = mb_convert_encoding($business->em_email, 'UTF-8', 'UTF-8');
                if ($replyto == "" || false == filter_var($replyto, FILTER_VALIDATE_EMAIL) || $this->validateMailExistance($user->email)) {
                    $replyto = env('MAIL_FROM_ADDRESS');
                }
                $title = 'Turno Reasignado';
                if ($user->email != "" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL) && $this->validateMailExistance($user->email) && $aviso == 1) {
                    Mail::to($user->email, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuMailBusiness($replyto, 'email_single', $title, $content));
                }
                if (isset($notifications_business) != 0 && $notifications_business->pc_mailn == '1') {
                    Mail::to(Auth::guard('user')->User()->us_mail, mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))
                    ->send(
                        new TuMailBusiness(
                            $replyto,
                            'email_single',
                            $title,
                            str_replace('Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))), 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))), $content)
                        ));
                    $content_2 = str_replace(
                        'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))),
                        'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8')))
                        , $content);
                    $email = $lender->tmsp_pmail;
                    $name = mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8');
                    if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL) && $this->validateMailExistance($user->email)) {
                        Mail::to($email, mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuMailBusiness($replyto, 'email_single', $title, $content_2));
                    }
                }
                if ($business->em_smscontrol == 'ALTA') {
                    $data = ClientsCustomization::where('usm_turid', $get_shift->tu_id)->first();
                    if (isset($data) && isset($user) != 0) {
                        if ($data->usm_cel != "" && $data->usm_cel != null) {
                            SMS::create([
                                'tusms_turid' => $get_shift->tu_id,
                                'tusms_empid' => $get_shift->emp_id,
                                'tusms_sucid' => $get_shift->suc_id,
                                'tusms_preid' => $get_shift->pres_id,
                                'tusms_usuid' => $get_shift->us_id,
                                'tusms_pacnom' => $user->name,
                                'tusms_celenv' => trim($data->usm_cel),
                                "tusms_msg" => "El turno para el " . date("d/m/Y", strtotime($get_shift->tu_fec)) . " a las " . date("H:i", strtotime($get_shift->tu_hora)) . " hs ha sido agendado por " . ucwords(mb_strtolower(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . '. Responda NO para cancelar el turno',
                                'tusms_tipo' => '5',
                                'tusms_para' => '2',
                                'tusms_pass' => '1',
                                'tusms_priori' => '1',
                                'tusms_envfec' => date("Y-m-d"),
                                'tusms_envtime' => date("H:i:s")
                            ]);
                        }
                    }
                }
                return response()->json(["msg" => "true", "code" => $get_shift->tu_code]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function store(Request $request) {
        try {
            $date = $request['tu_fec'];
            $tu_st = 0;
            if (isset($request['tu_st'])) {
                $tu_st = 1;
            }
            $aviso = 0;
            if (isset($request['aviso'])) {
                $aviso = 1;
            }
            if (Cache::has('settings_business_' . $request['emp_id'])) {
                $gmt = Cache::get('settings_business_' . $request['emp_id']);
            }
            if (Cache::has('settings_branch_' . $request['suc_id'])) {
                $gmt = Cache::get('settings_branch_' . $request['suc_id']);
            }
            if (Cache::has('settings_lender_' . $request['pres_id'])) {
                $gmt = Cache::get('settings_lender_' . $request['pres_id']);
            }
            if (isset($gmt) != 0) {
                $simultaneo = $gmt->cf_simtu;
            } else {
                $simultaneo = 1;
            }
            $bloqued_shift = BlockedShedules::
            where('pres_id', $request['pres_id'])
            ->where('tur_date', date("Y-m-d", strtotime($date)))
            ->where('tur_time', date("H:i:s", strtotime($request['tu_hora'])))
            ->count();
            if ($bloqued_shift >= $simultaneo && $tu_st == 0) {
                return response()->json(["response" => "false"]);
            }
            $shift = Shift::
            where('pres_id', $request['pres_id'])
            ->where('tu_fec', date("Y-m-d", strtotime($date)))
            ->where('tu_estado', '!=', 'BAJA')
            ->where('tu_hora', date("H:i:s", strtotime($request['tu_hora'])))
            ->count();
            if ($shift >= $simultaneo && $tu_st == 0) {
                return response()->json(["msg" => "false"]);
            } else {
//consulta de csuc y cpres
                $cpres = DB::table('tu_emps_con')
                ->where('pres_id', $request['pres_id'])
                ->where('emp_id', $request['emp_id'])
                ->count();
                $csuc = DB::table('tu_emps_con')
                ->where('pres_id', $request['pres_id'])
                ->where('suc_id', $request['suc_id'])
                ->where('emp_id', $request['emp_id'])
                ->count();
                if ($csuc == 0 && $cpres == 0) {
                    $gmt = DB::table('tu_emps_con')
                    ->where('emp_id', $request['emp_id'])
                    ->where('suc_id', 0)
                    ->where('pres_id', 0)
                    ->first();
                }
                if ($cpres != 0) {
                    $gmt = DB::table('tu_emps_con')
                    ->where('emp_id', $request['emp_id'])
                    ->where('pres_id', $request['pres_id'])
                    ->first();
                }
                if ($csuc != 0) {
                    $gmt = DB::table('tu_emps_con')
                    ->where('emp_id', $request['emp_id'])
                    ->where('suc_id', $request['suc_id'])
                    ->where('pres_id', $request['pres_id'])
                    ->first();
                }
                $services = '';
                $serv_totime = 0;
                $set_turtime = "00:00:00";
                if (isset($gmt) != 0) {
                    $set_turtime = $gmt->cf_turt;
                }
// get services
                if ($request['service_select'] != "") {
                    if (substr_count($request['service_select'], '-') <= 0) {
                        $get_service = Services::find($request['service_select']);
                        if (isset($get_service) != 0) {
                            $services .= $get_service->serv_nom;
                            $serv_time = $get_service->serv_tudur;
                            $set_horamt = explode(":", $serv_time);
                            if ($set_horamt[0] != '') {
                                $set_horamt2 = $set_horamt[0] * 60;
                                $max_time = $set_horamt2 + $set_horamt[1];
                                $serv_totime += $max_time;
                            }
                        }
                    } else {
                        for ($i = 0; $i <= substr_count($request['service_select'], '-'); $i++) {
                            $service = explode('-', $request['service_select']);
                            $get_service = Services::find($service[$i]);
                            if (isset($get_service) != 0) {
                                $services .= trim($get_service->serv_nom);
                                $serv_time = $get_service->serv_tudur;
                                $set_horamt = explode(":", $serv_time);
                                if ($set_horamt[0] != '') {
                                    $set_horamt2 = $set_horamt[0] * 60;
                                    $max_time = $set_horamt2 + $set_horamt[1];
                                    $serv_totime += $max_time;
                                }
                            }
                            if ($i != substr_count($request['service_select'], '-')) {
                                $services .= ", ";
                            }
                        }
                    }
                }
                if ($serv_totime > 0 && isset($gmt) != 0) {
                    $def_time = $gmt->cf_turt;
                    $set_extim = explode(":", $def_time);
                    $set_estim2 = $set_extim[0] * 60;
                    $max_deftime = $set_estim2 + $set_extim[1];
                    if ($serv_totime > $max_deftime) {
                        $set_turtime = date("H:i:s", mktime(0, 0 + $serv_totime, 0, 1, 2, 2013));
                    }
                }
                $code_can = $this->creacancod();
                $tu_st = 0;
                if (isset($request['tu_st'])) {
                    $tu_st = 1;
                }
                Shift::create([
                    'suc_id' => $request['suc_id'],
                    'us_id' => $request['us_id'],
                    'tu_st' => $tu_st,
                    'emp_id' => $request['emp_id'],
                    'pres_id' => $request['pres_id'],
                    'tur_ipfrom' => $this->getIp(),
                    'tu_fec' => date("Y-m-d", strtotime($date)),
                    'tu_hora' => date("H:i:s", strtotime($request['tu_hora'])),
                    'tu_servid' => $request['service_select'],
                    'tu_durac' => date("H:i:s", strtotime($set_turtime)),
                    'tu_estado' => 'ALTA',
                    'tur_canid' => $code_can,
                    "tu_bloqfec" => date("Y-m-d"),
                    "tu_bloqhor" => date("H:i:s"),
                    'tu_horaf' => $this->sumarHoras(date("H:i:s", strtotime($request['tu_hora'])), date("H:i:s", strtotime($set_turtime))),
                    'tu_usadm' => $this->getIdBusiness(),
                    'tu_carga' => 0
                ]);
                $get_shift = Shift::
                where('suc_id', $request['suc_id'])
                ->where('emp_id', $request['emp_id'])
                ->where('pres_id', $request['pres_id'])
                ->where('us_id', $request['us_id'])
                ->where('tu_fec', date("Y-m-d", strtotime($date)))
                ->where('tu_hora', date("H:i:s", strtotime($request['tu_hora'])))
                ->orderBy('tu_id', 'desc')
                ->first();
                ClientsCustomization::insert([
                    'usm_usid' => $request['us_id'],
                    'usm_empid' => $request['emp_id'],
                    'usm_turid' => $get_shift->tu_id
                ]);
                $dinit = date("H:i:s", strtotime($request['tu_hora']));
                $dend = $this->sumarHoras(date("H:i:s", strtotime($request['tu_hora'])), date("H:i:s", strtotime($set_turtime)));
                if (date("H", strtotime($dinit)) <= 24 && date("H", strtotime($dend)) <= 24) {
                    $interval = new DateInterval('PT5M');
                    $period_empty = new DatePeriod(new DateTime($dinit), $interval, new DateTime($dend));
                    foreach ($period_empty as $hour) {
                        if ($dend > $hour->format('H:i:s')) {
                            BlockedShedules::create([
                                'tur_id' => $get_shift->tu_id,
                                'us_id' => $request['us_id'],
                                'pres_id' => $request['pres_id'],
                                'tur_date' => date("Y-m-d", strtotime($date)),
                                'tur_time' => $hour->format('H:i:s'),
                                'tu_hora' => date("H:i:s", strtotime($request['tu_hora'])),
                                'tu_bloqhor' => date("H:i:s"),
                                'tur_status' => 'ALTA',
                            ]);
                        }
                    }


                }
                if (isset($request['date_1_dd']) && isset($request['date_1_mm']) && isset($request['date_1'])) {
                    $date_nac = $request['date_1'] . "-" . $request['date_1_mm'] . "-" . $request['date_1_dd'];
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_fecnac' => $date_nac,
                    ]);
                }
                if (isset($request['f_2'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_tipdoc' => $request['f_2'],
                    ]);
                }
                if (isset($request['f_3'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_obsoc' => $request['f_3'],
                    ]);
                }
                if (isset($request['f_4'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_obsocpla' => $request['f_4'],
                    ]);
                }
                if (isset($request['f_5'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_numdoc' => $request['f_5'],
                    ]);
                }
                if (isset($request['f_6'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_afilnum' => $request['f_6'],
                    ]);
                }
                if (isset($request['f_7'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_tel' => $request['f_7'],
                    ]);
                }
                if (isset($request['f_8'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_cel' => $request['f_8'],
                    ]);
                }
                if (isset($request['f_9'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_gen1' => $request['f_9'],
                    ]);
                }
                if (isset($request['f_10'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_gen2' => $request['f_10'],
                    ]);
                }
                if (isset($request['f_11'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_gen3' => $request['f_11'],
                    ]);
                }
                if (isset($request['f_12'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_gen4' => $request['f_12'],
                    ]);
                }

                if (isset($request['f_13'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_gen5' => $request['f_13'],
                    ]);
                }

                if (isset($request['f_14'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_gen6' => $request['f_14'],
                    ]);
                }

                if (isset($request['f_15'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_gen7' => $request['f_15'],
                    ]);
                }

                if (isset($request['f_16'])) {
                    ClientsCustomization::where('usm_turid', $get_shift->tu_id)->update([
                        'usm_gen8' => $request['f_16'],
                    ]);
                }




                $code = $request['emp_id'] . $request['suc_id'] . $request['pres_id'] . $get_shift->tu_id;
                $this->audit('Registro de turno ID #' . $code);
                $shift = Shift::find($get_shift->tu_id);
                $shift->fill(['tu_code' => $code]);
                $shift->save();
                $lender = Lenders::find($request['pres_id']);



                if ($lender->activate_zoom == 1) {
                    $this->generate_meeting($get_shift->tu_id);
                }


                $get_shift = Shift::find($get_shift->tu_id);


                $business = Business::find($request['emp_id']);
                $address = DB::table('tu_emps_suc')->where('suc_id', $request['suc_id'])->first();
                $user = Directory::where('us_id', $request['us_id'])->where('emp_id', $request['emp_id'])->first();
                $time = (date("H", strtotime($request['tu_hora'])) <= 12) ? 'AM' : 'PM';
                $create = $this->nameDay(date("w", strtotime($date))) . ' ' . date("d", strtotime($date)) . ' de ' . $this->NameMonth(date("m", strtotime($date))) . ' del ' . date("Y", strtotime($date));
                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))) . '.<br/>
                <br/>
                Te informamos a través de este correo electrónico que el turno que has solicitado para el ' . $create . ' a las  ' . date("H:i", strtotime($request['tu_hora'])) . ' ' . $time . ' ha sido agendado con éxito.<br /><br><br>
                <strong style="color:#FF5722">DATOS DEL TURNO:</strong><br/>
                <strong>Cliente:</strong>  ' . mb_strtoupper(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8')) . '<br/>
                <strong>Código:</strong> ' . $code . '<br/>
                <strong>Empresa:</strong> ' . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8')) . '<br/>
                <strong>Prestador:</strong>  ' . mb_strtoupper(mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8')) . '<br/>';
                if (isset($address) != 0) {
                    $content .= '<strong>Dirección:</strong> ' . $address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso . '<br/>';
                }
                if ($services != '') {
                    $content .= '<strong>Servicios solicitados:</strong> ' . $services . '<br/>';
                }
                if ($get_shift->url_zoom != '') {
                    $content .= '<strong>Url Meeting:</strong> ' . $get_shift->url_zoom . '<br/>';
                }
                $notifications_lender = LenderNotifications::
                where('pc_presid', $lender->tmsp_id)
                ->first();
                $notifications_branch = LenderNotifications::
                where('pc_sucid', $get_shift->suc_id)
                ->first();
                $notifications_business = LenderNotifications::
                where('pc_empid', $business->em_id)
                ->first();
                if (isset($notifications_lender) != 0 && $notifications_lender->pc_msg != "") {
                    $content .= '<br><strong>Notificaciones:</strong><br>';
                    $content .= $this->setNoty(mb_convert_encoding($notifications_lender->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    if ($notifications_lender->pc_suc_msg == 1 && isset($notifications_branch) != 0 && $notifications_lender->pc_msg != $notifications_branch->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_branch->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                    if ($notifications_lender->pc_emp_msg == 1 && isset($notifications_business) != 0 && $notifications_lender->pc_msg != $notifications_business->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                }
                if (isset($notifications_branch) != 0 && isset($notifications_branch) == 0 && $notifications_branch->pc_msg != "") {
                    $content .= '<br><strong>Notificaciones:</strong><br>';
                    $content .= $this->setNoty(mb_convert_encoding($notifications_branch->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    if ($notifications_branch->pc_emp_msg == 1 && isset($notifications_business) != 0 && $notifications_branch->pc_msg != $notifications_business->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                }
                if (isset($notifications_business) != 0 && isset($notifications_branch) == 0 && isset($notifications_lender) == 0 && $notifications_business->pc_msg != "") {
                    $content .= '<br><strong>Notificaciones:</strong><br>';
                    $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                }
                $title = 'Turno Agendado';
//confinguracion de la empresa
                $gmt = SettingsBusiness::where('emp_id', $get_shift->emp_id)->where('suc_id', '0')->where('pres_id', '0')->first();
//confinguracion de la sucursal
                $gmt = SettingsBusiness::where('suc_id', $get_shift->suc_id)->where('pres_id', '0')->first();
//confinguracion de la sucursal
                $gmt = SettingsBusiness::where('pres_id', $get_shift->pres_id)->first();
                $date_shift = $get_shift->tu_fec . ' ' . $get_shift->tu_hora;
                $date_turno = date('Y-m-d H:i:s', strtotime($date_shift));
                $btn = "https://www.turnonet.com/cancelar/" . $get_shift->tu_id . "/" . $get_shift->us_id . "/" . $get_shift->pres_id . "/" . $get_shift->suc_id . "/" . $get_shift->emp_id;
                $sumaHoras = $this->sumarHoras(date("H:i:s", strtotime($request['tu_hora'])), date("H:i:s", strtotime($set_turtime)));
                $btn_google = "https://calendar.google.com/calendar/r/eventedit?text=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&dates=" . $date . "T" . date("H:i:s", strtotime($request['tu_hora'])) . "/" . $date . "T" . date("H:i:s", strtotime($sumaHoras)) . "&details=" . urlencode($services) . "&location=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso) . "&sf=true&output=xml";
                $btn_ical = "https://www.turnonet.com/frame/calendario2/download-ics.php?summary=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&description=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&date_start=" . urlencode($date) . "&date_end=" . urlencode($date) . "&summary=" . urlencode($services) . "&location=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso);
                $btn_yahoo = "https://calendar.yahoo.com/?v=60&view=d&title=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&st=" . $date . "T" . date("H:i:s", strtotime($request['tu_hora'])) . "&dur=00000&desc=" . urlencode($services) . "&in_loc=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso);
                $replyto = mb_convert_encoding($business->em_email, 'UTF-8', 'UTF-8');
                if ($replyto == "" || false == filter_var($replyto, FILTER_VALIDATE_EMAIL) || $this->validateMailExistance($user->email)) {
                    $replyto = env('MAIL_FROM_ADDRESS');
                }
                if ($user->email != "" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL) && $this->validateMailExistance($user->email) && $aviso == 1) {
                    Mail::to($user->email, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuShiftMail($replyto, 'email_shitf', $title, $content, $code_can, $btn, $btn_google, $btn_ical, $btn_ical, $btn_yahoo));
                }
                if ($business->em_smscontrol == 'ALTA') {
                    $data = ClientsCustomization::where('usm_turid', $get_shift->tu_id)->first();
                    if (isset($data) && isset($user) != 0) {
                        if ($data->usm_cel != "" && $data->usm_cel != null) {
                            SMS::create([
                                'tusms_turid' => $get_shift->tu_id,
                                'tusms_empid' => $get_shift->emp_id,
                                'tusms_sucid' => $get_shift->suc_id,
                                'tusms_preid' => $get_shift->pres_id,
                                'tusms_usuid' => $get_shift->us_id,
                                'tusms_pacnom' => $user->name,
                                'tusms_celenv' => trim($data->usm_cel),
                                "tusms_msg" => "El turno para el " . date("d/m/Y", strtotime($get_shift->tu_fec)) . " a las " . date("H:i", strtotime($get_shift->tu_hora)) . " hs ha sido agendado por " . ucwords(mb_strtolower(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . '. Responda NO para cancelar el turno',
                                'tusms_tipo' => '4',
                                'tusms_para' => '2',
                                'tusms_pass' => '1',
                                'tusms_priori' => '1',
                                'tusms_envfec' => date("Y-m-d"),
                                'tusms_envtime' => date("H:i:s")
                            ]);
                        }
                    }
                }
                return response()->json(["msg" => "true", "code" => $code]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Set name Day
     * @param type $day
     * @param type $lang
     * @return type
     */
    public function nameDay($day) {
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
            default:
# code...
            break;
        }
        return $day;
    }

    /**
     * Set name month
     * @param type $month
     * @param type $lang
     * @return type
     */
    public function NameMonth($month) {
        if ($month == '01' || $month == '1') {
            $name = 'Enero';
        }
        if ($month == '02' || $month == '2') {
            $name = 'Febrero';
        }
        if ($month == '03' || $month == '3') {
            $name = 'Marzo';
        }
        if ($month == '04' || $month == '4') {
            $name = 'Abril';
        }
        if ($month == '05' || $month == '5') {
            $name = 'Mayo';
        }
        if ($month == '06' || $month == '6') {
            $name = 'Junio';
        }
        if ($month == '07' || $month == '7') {
            $name = 'Julio';
        }
        if ($month == '08' || $month == '8') {
            $name = 'Agosto';
        }if ($month == '09' || $month == '9') {
            $name = 'Septiembre';
        }
        if ($month == '10') {
            $name = 'Octubre';
        }
        if ($month == '11') {
            $name = 'Noviembre';
        }
        if ($month == '12') {
            $name = 'Diciembre';
        }
        return ucwords($name);
    }

    /**
     * Get Ip User
     * @return type
     */
    public function getIp() {
        try {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

//aleat
    public function getUCode($length = "") {
        try {
            $code = md5(uniqid(rand(), true));
            if ($length != "")
                return substr($code, 0, $length);
            else
                return $code;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function creapass() {
        try {
            $code['unic'] = $this->getUCode();
            $code['pass'] = substr($this->getUCode(), 0, 6);
            $code['pmd5'] = md5($code['pass']);
            return $code;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

//creacion cancelacion codigo
    public function creacancod() {
        try {
            $code = substr($this->getUCode(), 0, 6);
            return $code;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Audit user
     * @return type
     */
    public function audit($activity) {
        try {
            Activities::create([
                'activity' => $activity,
                'ip' => $this->getIp(),
                'id_user' => Auth::guard('user')->User()->us_id
            ]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Horas
     * @return type
     */
    public function sumarHoras($hora1, $hora2) {
        $hora1 = explode(":", $hora1);
        $hora2 = explode(":", $hora2);
        $temp = 0;
//sumo segundos
        $segundos = (int) $hora1[2] + (int) $hora2[2];
        while ($segundos >= 60) {
            $segundos = $segundos - 60;
            $temp++;
        }
//sumo minutos
        $minutos = (int) $hora1[1] + (int) $hora2[1] + $temp;
        $temp = 0;
        while ($minutos >= 60) {
            $minutos = $minutos - 60;
            $temp++;
        }
//sumo horas
        $horas = (int) $hora1[0] + (int) $hora2[0] + $temp;
        if ($horas < 10)
            $horas = '0' . $horas;
        if ($minutos < 10)
            $minutos = '0' . $minutos;
        if ($segundos < 10)
            $segundos = '0' . $segundos;
        if ($horas >= 24) {
            $horas = 24;
        }
        $sum_hrs = $horas . ':' . $minutos . ':' . $segundos;
        return ($sum_hrs);
    }

    public function validateMailExistance($email) {
        $vmail = new verifyEmail();
        $vmail->setStreamTimeoutWait(20);
        $vmail->Debug = TRUE;
        $vmail->Debugoutput = 'html';
        $vmail->setEmailFrom('turnos@turnonet.com');
        if ($vmail->check($email)) {
            return true;
        } elseif (verifyEmail::validate($email)) {
            if (BlackListMail::where('email', $email)->count() == 0) {
                BlackListMail::create(['email' => $email]);
            }
            return false;
        } else {
            if (BlackListMail::where('email', $email)->count() == 0) {
                BlackListMail::create(['email' => $email]);
            }
            return false;
        }
    }

    public function generate_meeting($id) {

        $shift = Shift::find($id);
        $url="https://meet.jit.si/turnonet-".$shift->tu_code;
        $shift->fill(['url_zoom' => $url,'id_meeting'=>'1']);
        $shift->save();
        
    }

    

    public function getPassword($length = 6, $uc = TRUE, $n = TRUE, $sc = FALSE) {
        try {
            $source = 'abcdefghijklmnopqrstuvwxyz';
            if ($uc == 1)
                $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if ($n == 1)
                $source .= '1234567890';
            if ($sc == 1)
                $source .= '-@#~$%()=^*+[]{}-_';
            if ($length > 0) {
                $rstr = "";
                $source = str_split($source, 1);
                for ($i = 1; $i <= $length; $i++) {
                    mt_srand((double) microtime() * 1000000);
                    $num = mt_rand(1, count($source));
                    $rstr .= $source[$num - 1];
                }
            }
            return $rstr;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    

    public function setNoty($content) {
        $content = str_replace("\n", '<br>', $content);
        return $content;
    }

    public function getIdBusiness() {

        if (Auth::guard('user')->User()->level == 1) {
            return Auth::guard('user')->User()->us_id;
        } else {

            $get_business = DB::table('tu_emps')->where('em_id',Auth::guard('user')->User()->emp_id)->first();
            return $get_business->em_uscid;
        }
    }

}

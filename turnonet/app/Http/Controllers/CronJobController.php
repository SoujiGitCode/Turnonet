<?php

namespace App\Http\Controllers;

use App\BlackListMail;
use App\Mail\TuMail;
use App\Mail\TuMailReports;
use App\Mail\TuShiftMail;
use verifyEmail;
use Route;
use Log;
use App\UsersApp;
use App\Shift;
use App\Activities;
use App\Business;
use App\BlockedShedules;
use App\SendMail;
use App\SettingsBusiness;
use App\Services;
use App\Branch;
use App\MercadoPago;
use App\Directory;
use App\BlackList;
use App\Lenders;
use App\BusinessFields;
use App\ClientsCustomization;
use App\LenderNotifications;
use App\ReportsBusiness;
use App\SMS;
use Redirect;
use DB;
use Cache;
use Mail;
use Session;
use Auth;
use URL;
use Artisan;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Http\Requests;
use Illuminate\Http\Request;

class CronJobController extends Controller {

    /**
     * Validate access
     */
    public function __construct() {
        include('ValidateMailExistance.php');
        $this->middleware('AuthApi', ['only' => ['send_reports', 'send_reports_branch', 'send_reports_business']]);
    }

    public function status_turnos() {
        $newTime = strtotime('-10 minutes');
        DB::table('tu_turnos')->where('tu_estado', 'BLOQUEO')->where('tu_bloqhor', '<', date("H:i:s", $newTime))->delete();
        DB::table('blocked_schedules')->where('tur_status', 'BLOQUEO')->where('tu_bloqhor', '<', date("H:i:s", $newTime))->delete();
    }

    public function resetTurnos($id) {
        $get_shift = Shift::find($id);
        $dinit = date("H:i:s", strtotime($get_shift->tu_hora));
        $dend = date("H:i:s", strtotime($get_shift->tu_horaf));
        if (date("H", strtotime($dinit)) <= 24 && date("H", strtotime($dend)) <= 24) {
            $interval = new DateInterval('PT5M');
            $period_empty = new DatePeriod(new DateTime($dinit), $interval, new DateTime($dend));
            foreach ($period_empty as $hour) {
                if ($dend > $hour->format('H:i:s')) {
                    BlockedShedules::create([
                        'tur_id' => $id,
                        'us_id' => $get_shift->us_id,
                        'pres_id' => $get_shift->pres_id,
                        'tur_date' => date("Y-m-d", strtotime($get_shift->tu_fec)),
                        'tu_hora' => $get_shift->tu_hora,
                        'tur_time' => $hour->format('H:i:s'),
                        'tur_status' => 'ALTA',
                        'tu_bloqhor' => date("H:i:s"),
                    ]);
                }
            }
        }
    }

    public function update_commisions_business() {
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        try {

            $business = DB::table('tu_emps')
                    ->where('em_mp', 1)
                    ->orderby('em_feccre', 'desc')
                    ->offset(0)
                    ->limit(5000)
                    ->get();
            $json = array();
            foreach ($business as $rs):

                $end = date("Y-m-d");

                $init = date("Y-m-d", strtotime($end . "- 5 month"));

                $total_commission = DB::table('tu_mercadopago')
                        ->where('emp_id', $rs->em_id)
                        ->when(!empty($init), function ($query) use($init) {
                            return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                        })
                        ->when(!empty($end), function ($query) use($end) {
                            return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                        })
                        ->sum('commission');


                DB::table('tu_emps')->where('em_id', $rs->em_id)->update([
                    'total_commission' => $total_commission,
                ]);
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function update_shift_business($pay) {

        set_time_limit(0);
        ini_set('memory_limit', '128M');
        try {
            $date = date("Y") . "-01-01";
            if ($pay == 1) {
                $business = DB::table('tu_emps')
                        ->where('em_fact', $pay)
                        ->orderby('em_feccre', 'desc')
                        ->offset(0)
                        ->limit(5000)
                        ->get();
            } else {
                $business = DB::table('tu_emps')
                        ->where('em_fact', $pay)
                        ->where('em_feccre', '>=', $date)
                        ->where('em_estado', 'ALTA')
                        ->offset(0)
                        ->limit(5000)
                        ->get();
            }
            $json = array();
            foreach ($business as $rs):

                $shift = Shift::where('emp_id', $rs->em_id)->count();

                DB::table('tu_emps')->where('em_id', $rs->em_id)->update([
                    'total_turnos' => $shift,
                ]);
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function update_business($pay) {
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        try {
            $date = date("Y") . "-01-01";
            if ($pay == 1) {
                $business = DB::table('tu_emps')
                        ->where('em_fact', $pay)
                        ->orderby('em_feccre', 'desc')
                        ->offset(0)
                        ->limit(5000)
                        ->get();
            } else {
                $business = DB::table('tu_emps')
                        ->where('em_fact', $pay)
                        ->where('em_feccre', '>=', $date)
                        ->where('em_estado', 'ALTA')
                        ->offset(0)
                        ->limit(5000)
                        ->get();
            }
            $json = array();
            $i=0;
            foreach ($business as $rs):

               


                    $lenders = DB::table('tu_tmsp')->where('emp_id', $rs->em_id)->where('tmsp_estado', 'ALTA')->count();
          
                
                $mes_actual = date('Y').'-'.date("m").'-01';
                $mes_anterior=date("Y-m-d",strtotime($mes_actual."- 1 month")); 

                echo date("Y-m-d", strtotime($mes_anterior));
                echo date("Y-m-d", strtotime($mes_actual));
            


                $sms = DB::table('tu_sms')
                ->where('tusms_empid', $rs->em_id)
                ->where('tusms_envfec', '>=', date("Y-m-d", strtotime($mes_anterior)))
                ->where('tusms_envfec', '<=', date("Y-m-d", strtotime($mes_actual)))
                ->count();



                $um = DB::table('tu_turnos')
                ->where('emp_id', $rs->em_id)
                ->where('tu_bloqfec', '>=', date("Y-m-d", strtotime($mes_anterior)))
                ->where('tu_bloqfec', '<', date("Y-m-d", strtotime($mes_actual)))
                ->count();


                $ua = DB::table('tu_turnos')
                ->where('emp_id', $rs->em_id)
                ->where('tu_bloqfec', '>=', date("Y-m-d", strtotime($mes_actual)))
                ->count();



                DB::table('tu_emps')->where('em_id', $rs->em_id)->update([
                    "total_turnos_lastmonth" => $um,
                    'total_turnos_mes' => $ua,
                    'total_sms' => $sms,
                    'total_pres' => $lenders,
                    'logic_state' => 'ALTA'
                ]);

            
                
            
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function clear_cache() {
        Artisan::call('optimize:clear');
        echo Artisan::output();
    }

    public function delete_audits() {
        Activities::whereDate('created_at', '<', date("Y-m-d"))->delete();
    }

    public function reminders_oneday() {
        set_time_limit(0);
        ini_set('memory_limit', '64M');
        $hoy = date("Y-m-d");
        $date = date("Y-m-d", strtotime($hoy . "+ 1 days"));
        $shift = DB::table('tu_turnos')
                ->where('tu_fec', date("Y-m-d", strtotime($date)))
                ->where('tu_estado', 'ALTA')
                ->orderBy('tu_hora', 'ASC')
                ->get();
        $r = 0;
        foreach ($shift as $rs) {
            $user_id = $rs->us_id;
            $business = $rs->emp_id;
            $lender_id = $rs->pres_id;
            $services = '';
            $get_business = Cache::rememberForever('business_' . $business, function() use($business) {
                        return DB::table('tu_emps')->where('em_id', $business)->first();
                    });
            if (isset($get_business) != 0 && $get_business->reminder_1 == 1) {
                if ($rs->tu_servid != null) {
                    if (substr_count($rs->tu_servid, '-') <= 0) {
                        $service_id = $rs->tu_servid;
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= $get_service->serv_nom;
                        }
                    } else {
                        for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                            $service = explode('-', $rs->tu_servid);
                            $service_id = $service[$i];
                            $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                        return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                    });
                            if (isset($get_service) != 0) {
                                $services .= trim($get_service->serv_nom);
                            }
                            if ($i != substr_count($rs->tu_servid, '-')) {
                                $services .= ", ";
                            }
                        }
                    }
                }
                $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                            return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                        });
                $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business) {
                            return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business)->first();
                        });
                if (isset($user) != 0 && $user->email != "" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL) && $this->validateMailExistance($user->email)) {
                    $r = $r + 1;
                    SendMail::create([
                        'id_sq' => $rs->tu_id,
                        'emp_id' => $rs->emp_id,
                        'content' => 'Recordario de Turno',
                        'name' => $user->name,
                        'email' => strtolower($user->email),
                        'date_reports' => $hoy,
                        "hour_sendmail" => date("H:i:s", strtotime($rs->tu_hora)),
                        'type' => '1',
                        'rep_type' => '0',
                        'category' => '2'
                    ]);
                }
            }
        }
        Log::info("Recordatorio de turnos por correo (para mañana): " . $r . "  en la cola");
    }

    public function reminders_fivedays() {
        set_time_limit(0);
        ini_set('memory_limit', '64M');
        $hoy = date("Y-m-d");
        $date = date("Y-m-d", strtotime($hoy . "+ 5 days"));
        $shift = DB::table('tu_turnos')->
                where('tu_fec', date("Y-m-d", strtotime($date)))
                ->where('tu_estado', 'ALTA')
                ->orderBy('tu_hora', 'ASC')
                ->get();
        $r = 0;
        foreach ($shift as $rs) {
            $user_id = $rs->us_id;
            $business = $rs->emp_id;
            $lender_id = $rs->pres_id;
            $services = '';
            $get_business = Cache::rememberForever('business_' . $business, function() use($business) {
                        return DB::table('tu_emps')->where('em_id', $business)->first();
                    });
            if (isset($get_business) != 0 && $get_business->reminder_5 == 1) {
                if ($rs->tu_servid != null) {
                    if (substr_count($rs->tu_servid, '-') <= 0) {
                        $service_id = $rs->tu_servid;
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= $get_service->serv_nom;
                        }
                    } else {
                        for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                            $service = explode('-', $rs->tu_servid);
                            $service_id = $service[$i];
                            $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                        return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                    });
                            if (isset($get_service) != 0) {
                                $services .= trim($get_service->serv_nom);
                            }
                            if ($i != substr_count($rs->tu_servid, '-')) {
                                $services .= ", ";
                            }
                        }
                    }
                }
                $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                            return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                        });
                $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business) {
                            return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business)->first();
                        });
                if (isset($user) != 0 && $user->email != "" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL) && $this->validateMailExistance($user->email)) {
                    $r = $r + 1;
                    SendMail::create([
                        'id_sq' => $rs->tu_id,
                        'emp_id' => $rs->emp_id,
                        'content' => 'Recordario de Turno',
                        'name' => $user->name,
                        'email' => strtolower($user->email),
                        'date_reports' => $hoy,
                        "hour_sendmail" => date("H:i:s", strtotime($rs->tu_hora)),
                        'type' => '1',
                        'rep_type' => '0',
                        'category' => '2'
                    ]);
                }
            }
        }
        Log::info("Recordatorio de turnos por correo (5 dias): " . $r . "  en la cola");
    }

    public function reminders_oneday_sms() {
        set_time_limit(0);
        ini_set('memory_limit', '64M');
        $hoy = date("Y-m-d");
        $date = date("Y-m-d", strtotime($hoy . "+ 1 days"));
        $shift = DB::table('tu_turnos')->
                where('tu_fec', date("Y-m-d", strtotime($date)))
                ->where('tu_estado', 'ALTA')
                ->orderBy('tu_hora', 'ASC')
                ->get();
        $r = 0;
        foreach ($shift as $rs) {
            $business_id = $rs->emp_id;
            $user_id = $rs->us_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            if ($get_business->em_smscontrol == 'ALTA') {
                $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                            return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                        });
                $data = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                if (isset($data) && isset($user) != 0) {
                    if ($data->usm_cel != "" && $data->usm_cel != null) {
                        $r = $r + 1;
                        SMS::create([
                            'tusms_turid' => $rs->tu_id,
                            'tusms_empid' => $rs->emp_id,
                            'tusms_sucid' => $rs->suc_id,
                            'tusms_preid' => $rs->pres_id,
                            'tusms_usuid' => $rs->us_id,
                            'tusms_pacnom' => $user->name,
                            'tusms_celenv' => trim($data->usm_cel),
                            "tusms_msg" => ucwords(mb_strtolower(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'))) . " le recuerda su turno para el " . date("d/m/Y", strtotime($rs->tu_fec)) . " a las " . date("H:i", strtotime($rs->tu_hora)) . " hs. Responda NO para cancelar el turno",
                            'tusms_tipo' => '1',
                            'tusms_para' => '1',
                            'tusms_pass' => '1',
                            'tusms_priori' => '1',
                            'tusms_envfec' => date("Y-m-d"),
                            'tusms_envtime' => date("H:i:s")
                        ]);
                    }
                }
            }
        }
        Log::info("Recordatorio de turnos por sms (para mañana): " . $r . "  en la cola");
    }

    public function reminders_twoday_sms() {
        set_time_limit(0);
        ini_set('memory_limit', '64M');
        $hoy = date("Y-m-d");
        $date = date("Y-m-d", strtotime($hoy . "+ 2 days"));
        $shift = DB::table('tu_turnos')->
                where('tu_fec', date("Y-m-d", strtotime($date)))
                ->where('tu_estado', 'ALTA')
                ->orderBy('tu_hora', 'ASC')
                ->get();
        $r = 0;
        foreach ($shift as $rs) {
            $business_id = $rs->emp_id;
            $user_id = $rs->us_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            if ($get_business->em_smscontrol == 'ALTA') {
                $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                            return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                        });
                $data = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                if (isset($data) && isset($user) != 0) {
                    if ($data->usm_cel != "" && $data->usm_cel != null) {
                        $r = $r + 1;
                        SMS::create([
                            'tusms_turid' => $rs->tu_id,
                            'tusms_empid' => $rs->emp_id,
                            'tusms_sucid' => $rs->suc_id,
                            'tusms_preid' => $rs->pres_id,
                            'tusms_usuid' => $rs->us_id,
                            'tusms_pacnom' => $user->name,
                            'tusms_celenv' => trim($data->usm_cel),
                            "tusms_msg" => ucwords(mb_strtolower(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'))) . " le recuerda su turno para el " . date("d/m/Y", strtotime($rs->tu_fec)) . " a las " . date("H:i", strtotime($rs->tu_hora)) . " hs. Responda NO para cancelar el turno",
                            'tusms_tipo' => '1',
                            'tusms_para' => '1',
                            'tusms_pass' => '1',
                            'tusms_priori' => '1',
                            'tusms_envfec' => date("Y-m-d"),
                            'tusms_envtime' => date("H:i:s")
                        ]);
                    }
                }
            }
        }
        Log::info("Recordatorio de turnos por sms (2 dias): " . $r . "  en la cola");
    }

    public function reminders_fiveday_sms() {
        set_time_limit(0);
        ini_set('memory_limit', '64M');
        $hoy = date("Y-m-d");
        $date = date("Y-m-d", strtotime($hoy . "+ 5 days"));
        $shift = DB::table('tu_turnos')->
                where('tu_fec', date("Y-m-d", strtotime($date)))
                ->where('tu_estado', 'ALTA')
                ->orderBy('tu_hora', 'ASC')
                ->get();
        $r = 0;
        foreach ($shift as $rs) {
            $business_id = $rs->emp_id;
            $user_id = $rs->us_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            if ($get_business->em_smscontrol == 'ALTA') {
                $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                            return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                        });
                $data = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                if (isset($data) && isset($user) != 0) {
                    if ($data->usm_cel != "" && $data->usm_cel != null) {
                        $r = $r + 1;
                        SMS::create([
                            'tusms_turid' => $rs->tu_id,
                            'tusms_empid' => $rs->emp_id,
                            'tusms_sucid' => $rs->suc_id,
                            'tusms_preid' => $rs->pres_id,
                            'tusms_usuid' => $rs->us_id,
                            'tusms_pacnom' => $user->name,
                            'tusms_celenv' => trim($data->usm_cel),
                            "tusms_msg" => ucwords(mb_strtolower(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'))) . " le recuerda su turno para el " . date("d/m/Y", strtotime($rs->tu_fec)) . " a las " . date("H:i", strtotime($rs->tu_hora)) . " hs. Responda NO para cancelar el turno",
                            'tusms_tipo' => '1',
                            'tusms_para' => '1',
                            'tusms_pass' => '1',
                            'tusms_priori' => '1',
                            'tusms_envfec' => date("Y-m-d"),
                            'tusms_envtime' => date("H:i:s")
                        ]);
                    }
                }
            }
        }
        Log::info("Recordatorio de turnos por sms (5 dias): " . $r . "  en la cola");
    }

    public function customers_reports($hour) {
        set_time_limit(0);
        ini_set('memory_limit', '64M');
        $reports = DB::table('tu_reps')->where('rep_hora', date("H:i:s", strtotime($hour)))->orderby('emp_id', 'desc')->get();
        $r = 0;
        foreach ($reports as $rs):
            $hoy = date("Y-m-d");
            if ($hour == '06:00:00') {
                $date = date("Y-m-d", strtotime($hoy));
                $content = 'Turnos %s para el ' . date("d/m/Y") . ' solicitados hasta las 06:00 hs.';
            } else {
                $date = date("Y-m-d", strtotime($hoy . "+ 1 days"));
                $content = 'Turnos %s para el ' . date("d/m/Y", strtotime($date)) . ' solicitados hasta las ' . date("H:i", strtotime($hour)) . ' hs. del ' . date("d/m/Y") . '.';
            }
            $shift = DB::table('tu_turnos')->where('emp_id', $rs->emp_id)->where('tu_estado', 'ALTA')->where('tu_fec', $date)->count();
            if ($shift != 0) {
                $get_business = DB::table('tu_emps')->where('em_id', $rs->emp_id)->first();
                $name = mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8');
                $email = $get_business->em_email;
                $r = $r + 1;
                if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL) && $this->validateMailExistance($email)) {
                    SendMail::create([
                        'id_sq' => $rs->emp_id,
                        'emp_id' => $rs->emp_id,
                        'content' => str_replace("%s", "de " . ucwords(mb_strtolower($name)), $content),
                        'name' => $name,
                        'email' => strtolower($email),
                        'date_reports' => $date,
                        "hour_sendmail" => date("H:i:s", strtotime($hour)),
                        'type' => '1',
                        'rep_type' => $rs->rep_type,
                        'category' => '1'
                    ]);
                }
                if ($rs->rep_recsuc == '1') {
                    $branchs = Branch::where('suc_empid', $rs->emp_id)->where('suc_estado', 'ALTA')->get();
                    foreach ($branchs as $rs_b) {
                        $shift = DB::table('tu_turnos')->where('suc_id', $rs_b->suc_id)->where('tu_estado', 'ALTA')->where('tu_fec', $date)->count();
                        if ($rs_b->suc_email != "" && $shift != 0) {
                            $name = mb_convert_encoding($rs_b->suc_nom, 'UTF-8', 'UTF-8');
                            $email = $rs_b->suc_email;
                            $r = $r + 1;
                            if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL) && $this->validateMailExistance($email)) {
                                SendMail::create([
                                    'id_sq' => $rs_b->suc_id,
                                    'emp_id' => $rs->emp_id,
                                    'name' => $name,
                                    'email' => strtolower($email),
                                    'content' => str_replace("%s", "de " . ucwords(mb_strtolower($name)), $content),
                                    'date_reports' => $date,
                                    "hour_sendmail" => date("H:i:s", strtotime($hour)),
                                    'type' => '2',
                                    'rep_type' => $rs->rep_type,
                                    'category' => '1'
                                ]);
                            }
                        }
                    }
                }
                if ($rs->rep_recpre == '1') {
                    $e = 0;
                    $lenders = DB::table('tu_tmsp')->where('emp_id', $rs->emp_id)->where('tmsp_estado', 'ALTA')->get();
                    foreach ($lenders as $rs_p) {
                        $shift = DB::table('tu_turnos')->where('pres_id', $rs_p->tmsp_id)->where('tu_estado', 'ALTA')->where('tu_fec', $date)->count();
                        if ($rs_p->tmsp_pmail != "" && $shift != 0) {
                            $name = mb_convert_encoding($rs_p->tmsp_pnom, 'UTF-8', 'UTF-8');
                            $email = $rs_p->tmsp_pmail;
                            $r = $r + 1;
                            if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL) && $this->validateMailExistance($email)) {
                                SendMail::create([
                                    'id_sq' => $rs_p->tmsp_id,
                                    'emp_id' => $rs->emp_id,
                                    'name' => $name,
                                    'email' => $email,
                                    'content' => str_replace("%s", "de " . ucwords(mb_strtolower($name)), $content),
                                    'date_reports' => $date,
                                    "hour_sendmail" => date("H:i:s", strtotime($hour)),
                                    'type' => '3',
                                    'rep_type' => $rs->rep_type,
                                    'category' => '1'
                                ]);
                            }
                        }
                    }
                }
            }
        endforeach;
        Log::info("Reporte de turnos del dia " . date("Y-m-d") . " para las " . $hour . ":" . $r . "  en la cola");
    }

    public function send_mails() {
        ini_set('memory_limit', '64M');
        set_time_limit(0);
        $emails = DB::table('sendmail')->where('hour_sendmail', '<=', date("H:i:s"))->whereDate('created_at', date("Y-m-d"))->offset(0)->limit(10)->orderBy('category', 'asc')->orderBy('hour_sendmail', 'asc')->get();
        foreach ($emails as $rs_mail):
            if ($rs_mail->category == '1') {
                $this->reports($rs_mail->type, $rs_mail->id_sq, $rs_mail->date_reports, $rs_mail->emp_id, $rs_mail->content, $rs_mail->email, $rs_mail->name, $rs_mail->rep_type);
            }
            if ($rs_mail->category == '2') {
                $this->shift($rs_mail->id_sq, $rs_mail->email, $rs_mail->name);
            }
            DB::table('sendmail')->where('id', $rs_mail->id)->delete();
        endforeach;
    }

    public function reports($type, $id_sq, $date_reports, $emp_id, $content, $email, $name, $rep_type) {
        if ($type == '1') {
            $shifts = DB::table('tu_turnos')
                    ->where('emp_id', $id_sq)
                    ->where('tu_estado', 'ALTA')
                    ->where('tu_fec', date("Y-m-d", strtotime($date_reports)))
                    ->orderBy('suc_id', 'ASC')
                    ->orderBy('pres_id', 'ASC')
                    ->orderBy('tu_hora', 'ASC')
                    ->get();
        }
        if ($type == '2') {
            $shifts = DB::table('tu_turnos')
                    ->where('suc_id', $id_sq)
                    ->where('tu_estado', 'ALTA')
                    ->where('tu_fec', date("Y-m-d", strtotime($date_reports)))
                    ->orderBy('pres_id', 'ASC')
                    ->orderBy('tu_hora', 'ASC')
                    ->get();
        }
        if ($type == '3') {
            $shifts = DB::table('tu_turnos')
                    ->where('pres_id', $id_sq)
                    ->where('tu_estado', 'ALTA')
                    ->where('tu_fec', date("Y-m-d", strtotime($date_reports)))
                    ->orderBy('tu_hora', 'ASC')
                    ->get();
        }
        $json = array();
        foreach ($shifts as $rs) {
            $services = '';
            if ($rs->tu_servid != null) {
                if (substr_count($rs->tu_servid, '-') <= 0) {
                    $service_id = $rs->tu_servid;
                    $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                            });
                    if (isset($get_service) != 0) {
                        $services .= $get_service->serv_nom;
                    }
                } else {
                    for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                        $service = explode('-', $rs->tu_servid);
                        $service_id = $service[$i];
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= trim($get_service->serv_nom);
                        }
                        if ($i != substr_count($rs->tu_servid, '-')) {
                            $services .= ", ";
                        }
                    }
                }
            }
            $lender_id = $rs->pres_id;
            $branch_id = $rs->suc_id;
            $user_id = $rs->us_id;
            $business_id = $rs->emp_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                        return Branch::where('suc_id', $branch_id)->first();
                    });
            $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                        return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                    });
            $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                        return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                    });
            if (isset($lender) != 0 && isset($user) != 0 && isset($get_business) != 0) {
                $detail = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                $time = (date("H", strtotime($rs->tu_hora)) <= 12) ? 'AM' : 'PM';
                $asist = $rs->tu_asist;
                if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d")) {
                    $asist = 4;
                }
                if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                    $asist = 4;
                }
                $json[] = array(
                    "branch" => mb_strtoupper($branch->suc_nom),
                    "business" => mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8')),
                    "name" => mb_strtoupper($user->name),
                    "email" => strtolower($user->email),
                    "time" => $time,
                    "detail" => $detail,
                    "timestamp" => strtotime($rs->tu_hora),
                    "hour" => date("H:i", strtotime($rs->tu_hora)),
                    "code" => $rs->tu_code,
                    "services" => trim($services),
                    'lender' => mb_strtoupper(mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8')),
                );
            }
        }
        $title = 'Reporte de Turnos';
        if (isset($get_business) != 0) {

            $replyto = mb_convert_encoding($get_business->em_email, 'UTF-8', 'UTF-8');
            if ($replyto == "" || false == filter_var($replyto, FILTER_VALIDATE_EMAIL)) {
                $replyto = env('MAIL_FROM_ADDRESS');
            }
        } else {
            $replyto = env('MAIL_FROM_ADDRESS');
        }

        $inputs_add = DB::table('tu_emps_md')->where('mi_empid', $emp_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
        $btn_email = url('/') . '/reporte/' . $type . '/' . $rep_type . '/' . $id_sq . '/' . $emp_id . '/' . $date_reports . '/' . $this->setUrl($content);
        if (substr_count($email, ',') <= 0) {
            if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to(trim($email), mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuMailReports($replyto, 'email_reports', $title, $content, $json, $inputs_add, $rep_type, $btn_email));
                Log::info("Enviado a " . $email . " el reporte de turnos para " . $date_reports);
            }
        } else {
            if ($email != "") {
                for ($i = 0; $i <= substr_count($email, ','); $i++) {
                    $get_mail = explode(',', $email);
                    $send_email = trim($get_mail[$i]);
                    if ($send_email != "" && false !== filter_var($send_email, FILTER_VALIDATE_EMAIL)) {
                        Mail::to($send_email, mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuMailReports($replyto, 'email_reports', $title, $content, $json, $inputs_add, $rep_type, $btn_email));
                        Log::info("Enviado a " . $send_email . " el reporte de turnos para " . $date_reports);
                    }
                }
            }
        }
    }

    public function shift($id, $email, $name) {
        $get_shift = DB::table('tu_turnos')->where('tu_id', $id)->first();
        if (isset($get_shift) != 0) {
            $services = '';
            if ($get_shift->tu_servid != null) {
                if (substr_count($get_shift->tu_servid, '-') <= 0) {
                    $service_id = $get_shift->tu_servid;
                    $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                            });
                    if (isset($get_service) != 0) {
                        $services .= $get_service->serv_nom;
                    }
                } else {
                    for ($i = 0; $i <= substr_count($get_shift->tu_servid, '-'); $i++) {
                        $service = explode('-', $get_shift->tu_servid);
                        $service_id = $service[$i];
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= trim($get_service->serv_nom);
                        }
                        if ($i != substr_count($get_shift->tu_servid, '-')) {
                            $services .= ", ";
                        }
                    }
                }
            }
            $lender_id = $get_shift->pres_id;
            $business_id = $get_shift->emp_id;
            $user_id = $get_shift->us_id;
            $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                        return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                    });
            $business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                        return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                    });
            $address = DB::table('tu_emps_suc')->where('suc_id', $get_shift->suc_id)->first();
            $time = (date("H", strtotime($get_shift->tu_hora)) <= 12) ? 'AM' : 'PM';
            $create = $this->nameDay(date("w", strtotime($get_shift->tu_fec))) . ', ' . date("d", strtotime($get_shift->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($get_shift->tu_fec))) . ' del  ' . date("Y", strtotime($get_shift->tu_fec));
            if (isset($user) != 0) {
                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))) . '.<br/>
            <br/>
            Te informamos a través de este correo electrónico que tienes un turno agendado para el  ' . $create . ' a las ' . date("H:i", strtotime($get_shift->tu_hora)) . ' ' . $time . '.<br><br>
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
                    $content .= mb_convert_encoding($notifications_lender->pc_msg, 'UTF-8', 'UTF-8') . '<br/>';
                    if ($notifications_lender->pc_suc_msg == 1 && isset($notifications_branch) != 0 && $notifications_lender->pc_msg != $notifications_branch->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_branch->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                    if ($notifications_lender->pc_emp_msg == 1 && isset($notifications_business) != 0 && $notifications_lender->pc_msg != $notifications_business->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                }
                if (isset($notifications_branch) != 0 && isset($notifications_branch) == 0 && $notifications_branch->pc_msg != "") {
                    $content .= '<br><strong>Notificaciones:</strong><br>';
                    $content .= mb_convert_encoding($notifications_branch->pc_msg, 'UTF-8', 'UTF-8') . '<br/>';
                    if ($notifications_branch->pc_emp_msg == 1 && isset($notifications_business) != 0 && $notifications_branch->pc_msg != $notifications_business->pc_msg) {
                        $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                    }
                }
                if (isset($notifications_business) != 0 && isset($notifications_branch) == 0 && isset($notifications_lender) == 0 && $notifications_business->pc_msg != "") {
                    $content .= '<br><strong>Notificaciones:</strong><br>';
                    $content .= $this->setNoty(mb_convert_encoding($notifications_business->pc_msg, 'UTF-8', 'UTF-8')) . '<br/>';
                }
//confinguracion de la empresa
                $gmt = DB::table('tu_emps_con')->where('emp_id', $get_shift->emp_id)->where('suc_id', '0')->where('pres_id', '0')->first();
//confinguracion de la sucursal
                $gmt = DB::table('tu_emps_con')->where('suc_id', $get_shift->suc_id)->where('pres_id', '0')->first();
//confinguracion de la sucursal
                $gmt = DB::table('tu_emps_con')->where('pres_id', $get_shift->pres_id)->first();
                $date_shift = $get_shift->tu_fec . ' ' . $get_shift->tu_hora;
                $date_turno = date('Y-m-d H:i:s', strtotime($date_shift));
                if (isset($gmt) != 0 && $gmt->cf_tcan == '00:30:00') {
                    $newDate = strtotime('-30 minute', strtotime($date_turno));
                }
                if (isset($gmt) != 0 && $gmt->cf_tcan == '01:00:00') {
                    $newDate = strtotime('-1 hour', strtotime($date_turno));
                }
                if (isset($gmt) != 0 && $gmt->cf_tcan == '02:00:00') {
                    $newDate = strtotime('-2 hour', strtotime($date_turno));
                }
                if (isset($gmt) != 0 && $gmt->cf_tcan == '04:00:00') {
                    $newDate = strtotime('-4 hour', strtotime($date_turno));
                }
                if (isset($gmt) != 0 && $gmt->cf_tcan == '08:00:00') {
                    $newDate = strtotime('-8 hour', strtotime($date_turno));
                }
                if (isset($gmt) != 0 && $gmt->cf_tcan == '24:01:00') {
                    $newDate = strtotime('-24 hour', strtotime($date_turno));
                }
                if (isset($gmt) != 0 && $gmt->cf_tcan == '48:01:00') {
                    $newDate = strtotime('-48 hour', strtotime($date_turno));
                }
                if (isset($gmt) != 0 && $gmt->cf_tcan == '72:01:00') {
                    $newDate = strtotime('-72 hour', strtotime($date_turno));
                }
                $btn = "https://www.turnonet.com/cancelar/" . $get_shift->tu_id . "/" . $get_shift->us_id . "/" . $get_shift->pres_id . "/" . $get_shift->suc_id . "/" . $get_shift->emp_id;
                $replyto = mb_convert_encoding($business->em_email, 'UTF-8', 'UTF-8');
                if ($replyto == "" || false == filter_var($replyto, FILTER_VALIDATE_EMAIL)) {
                    $replyto = env('MAIL_FROM_ADDRESS');
                }
                $sumaHoras = $this->sumarHoras(date("H:i:s", strtotime($get_shift->tu_hora)), date("H:i:s", strtotime($get_shift->tu_durac)));
                $btn_google = "https://calendar.google.com/calendar/r/eventedit?text=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&dates=" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($get_shift->tu_hora)) . "/" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($sumaHoras)) . "&details=" . urlencode($services) . "&location=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso) . "&sf=true&output=xml";
                $btn_ical = "https://www.turnonet.com/frame/calendario2/download-ics.php?summary=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&description=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&date_start=" . urlencode($get_shift->tu_fec) . "&date_end=" . urlencode($get_shift->tu_fec) . "&summary=" . urlencode($services) . "&location=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso);
                $btn_yahoo = "https://calendar.yahoo.com/?v=60&view=d&title=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&st=" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($get_shift->tu_hora)) . "&dur=00000&desc=" . urlencode($services) . "&in_loc=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso);
                $title = 'Recordario de turno';
                if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Mail::to(trim($email), mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuShiftMail($replyto, 'email_shitf', $title, $content, $get_shift->tur_canid, $btn, $btn_google, $btn_ical, $btn_ical, $btn_yahoo));
//Log::info("Enviado a ".$email." el recordatorio de turno");
                }
            }
        }
    }

    public function send_reports(Request $request) {
        try {
            set_time_limit(0);
            $name = $request['name'];
            $email = $request['tmsp_pmail'];
            $date = $request['day'];
            if (DB::table('tu_turnos')->where('pres_id', $request['pres_id'])->where('tu_estado', 'ALTA')->where('tu_fec', date("Y-m-d", strtotime($date)))->count() == 0) {
                return response()->json(["msg" => "error"]);
            } else {
                $content = 'Turnos %s para el ' . date("d/m/Y", strtotime($request['day'])) . ' solicitados hasta las ' . date("H:i:s") . ' hs. del ' . date("d/m/Y");
                if (isset($request['tmsp_pmail']) && $email != "") {
                    SendMail::create([
                        'id_sq' => $request['pres_id'],
                        'emp_id' => $request['emp_id'],
                        'content' => str_replace("%s", "de " . ucwords(mb_strtolower($name)), $content),
                        'name' => $name,
                        'email' => $email,
                        'date_reports' => date("Y-m-d", strtotime($date)),
                        "hour_sendmail" => date("H:i:s"),
                        'type' => '3',
                        'rep_type' => $request['rep_type'],
                        'category' => '1'
                    ]);
                    $this->send_mails();
                }
                return response()->json(["msg" => "true"]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function verify_reports(Request $request) {
        try {
            set_time_limit(0);
            $name = $request['name'];
            $email = $request['tmsp_pmail'];
            $date = $request['day'];
            if (DB::table('tu_turnos')->where('pres_id', $request['pres_id'])->where('tu_estado', 'ALTA')->where('tu_fec', date("Y-m-d", strtotime($date)))->count() == 0) {
                return response()->json(["msg" => "error"]);
            } else {
                return response()->json(["msg" => "true"]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function send_reports_branch(Request $request) {
        try {
            set_time_limit(0);
            $name = $request['name'];
            $email = $request['tmsp_pmail'];
            $date = $request['day'];
            if (DB::table('tu_turnos')->where('suc_id', $request['suc_id'])->where('tu_estado', 'ALTA')->where('tu_fec', date("Y-m-d", strtotime($date)))->count() == 0) {
                return response()->json(["msg" => "error"]);
            } else {
                $content = 'Turnos %s para  ' . date("d/m/Y", strtotime($request['day'])) . ' solicitados hasta las ' . date("H:i:s") . ' hs. del ' . date("d/m/Y");
                if (isset($request['tmsp_pmail']) && $email != "") {
                    SendMail::create([
                        'id_sq' => $request['suc_id'],
                        'emp_id' => $request['emp_id'],
                        'content' => str_replace("%s", "de " . ucwords(mb_strtolower($name)), $content),
                        'name' => $name,
                        'email' => $email,
                        'date_reports' => date("Y-m-d", strtotime($date)),
                        "hour_sendmail" => date("H:i:s"),
                        'type' => '2',
                        'rep_type' => $request['rep_type'],
                        'category' => '1'
                    ]);
                    $this->send_mails();
                }
                return response()->json(["msg" => "true"]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function verify_reports_branch(Request $request) {
        try {
            set_time_limit(0);
            $name = $request['name'];
            $email = $request['tmsp_pmail'];
            $date = $request['day'];
            if (DB::table('tu_turnos')->where('suc_id', $request['suc_id'])->where('tu_estado', 'ALTA')->where('tu_fec', date("Y-m-d", strtotime($date)))->count() == 0) {
                return response()->json(["msg" => "error"]);
            } else {
                return response()->json(["msg" => "true"]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function send_reports_business(Request $request) {
        try {
            set_time_limit(0);
            $name = $request['name'];
            $email = $request['tmsp_pmail'];
            $date = $request['day'];
            if (DB::table('tu_turnos')->where('emp_id', $request['emp_id'])->where('tu_estado', 'ALTA')->where('tu_fec', date("Y-m-d", strtotime($date)))->count() == 0) {
                return response()->json(["msg" => "error"]);
            } else {
                $content = 'Turnos %s para  ' . date("d/m/Y", strtotime($request['day'])) . ' solicitados hasta las ' . date("H:i:s") . ' hs. del ' . date("d/m/Y");
                if (isset($request['tmsp_pmail']) && $email != "") {
                    SendMail::create([
                        'id_sq' => $request['emp_id'],
                        'emp_id' => $request['emp_id'],
                        'content' => str_replace("%s", "de " . ucwords(mb_strtolower($name)), $content),
                        'name' => $name,
                        'email' => $email,
                        'date_reports' => date("Y-m-d", strtotime($date)),
                        "hour_sendmail" => date("H:i:s"),
                        'type' => '1',
                        'rep_type' => $request['rep_type'],
                        'category' => '1'
                    ]);
                    $this->send_mails();
                }
                return response()->json(["msg" => "true"]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function verify_reports_business(Request $request) {
        try {
            set_time_limit(0);
            $name = $request['name'];
            $email = $request['tmsp_pmail'];
            $date = $request['day'];
            if (DB::table('tu_turnos')->where('emp_id', $request['emp_id'])->where('tu_estado', 'ALTA')->where('tu_fec', date("Y-m-d", strtotime($date)))->count() == 0) {
                return response()->json(["msg" => "error"]);
            } else {
                return response()->json(["msg" => "true"]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function send_sms() {
        ini_set('memory_limit', '64M');
        set_time_limit(0);
        $cid = 'turnonet';
        $vc = 'tur123';
        $sms = SMS::whereDate('tusms_envfec', date("Y-m-d"))->where('tusms_pass', '1')->offset(0)->limit(100)->orderBy('tusms_priori', 'asc')->orderBy('tusms_envtime', 'asc')->get();
        foreach ($sms as $rs):
            $wisnum = $this->get_numerics($rs->tusms_celenv);
            $arr = str_split($wisnum);
            $lenum = count($arr);
            if ($lenum < 9) {
                $tusms_pass = 3;
                $error = "numero incorrecto: " . $wisnum;
                Log::info("SMS no enviado, " . $error);
            } else {
                $n = trim($this->verifyPhone($rs->tusms_celenv));
                $msg = $rs->tusms_msg;
                $urlsend = "https://www.smsfacil.com.ar/sms/sender2.php";
                $str = 'msg=' . urlencode($msg) . '&n=' . urlencode($n) . '&cid=' . urlencode($cid) . '&vc=' . urlencode($vc) . '&r=' . urlencode($rs->tusms_empid) . '-' . $rs->tusms_turid;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $urlsend . '?' . $str);
                curl_exec($ch);
//Comprobar el código de estado HTTP
                if (!curl_errno($ch)) {
                    switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                        case 200:
                            Log::info("SMS enviado a " . $n);
                            $tusms_pass = 2;
                            $error = '';
                            break;
                        default:
                            $tusms_pass = 3;
                            $error = "numero incorrecto: " . $n;
                            Log::info("SMS no enviado, " . $error);
                    }
                }
                curl_close($ch);
            }
            $get_sms = SMS::find($rs->tusms_id);
            $get_sms->fill([
                'tusms_pass' => $tusms_pass,
                'tusms_error' => $error,
            ]);
            $get_sms->save();
        endforeach;
    }

    public function send_sms_id($id) {
        ini_set('memory_limit', '64M');
        set_time_limit(0);
        $cid = 'turnonet';
        $vc = 'tur123';
        $sms = SMS::find($id);

        if(isset($sms)!=0){

                    $wisnum = $this->get_numerics($sms->tusms_celenv);
        $arr = str_split($wisnum);
        $lenum = count($arr);
        if ($lenum < 9) {
            $tusms_pass = 3;
            $error = "numero incorrecto: " . $wisnum;
            Log::info("SMS no enviado, " . $error);
        } else {
            $n = trim($this->verifyPhone($sms->tusms_celenv));
            $msg = $sms->tusms_msg;
            $urlsend = "https://www.smsfacil.com.ar/sms/sender2.php";
            $str = 'msg=' . urlencode($msg) . '&n=' . urlencode($n) . '&cid=' . urlencode($cid) . '&vc=' . urlencode($vc) . '&r=' . urlencode($sms->tusms_empid) . '-' . $sms->tusms_turid;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlsend . '?' . $str);
            curl_exec($ch);
//Comprobar el código de estado HTTP
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        Log::info("SMS enviado a " . $n);
                        $tusms_pass = 2;
                        $error = '';
                        break;
                    default:
                        $tusms_pass = 3;
                        $error = "numero incorrecto: " . $n;
                        Log::info("SMS no enviado, " . $error);
                }
            }
            curl_close($ch);
        }

        $get_sms = SMS::find($sms->tusms_id);
        $get_sms->fill([
            'tusms_pass' => $tusms_pass,
            'tusms_error' => $error,
        ]);
        $get_sms->save();


        }


    }

    public function create_sms_id($id) {
        set_time_limit(0);
        ini_set('memory_limit', '64M');
        $shift = Shift::find($id);
        $business_id = $shift->emp_id;
        $user_id = $shift->us_id;
        $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                    return DB::table('tu_emps')->where('em_id', $business_id)->first();
                });
        if ($get_business->em_smscontrol == 'ALTA') {
            $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                        return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                    });
            $data = DB::table('tu_ususmd')->where('usm_turid', $shift->tu_id)->first();
            if (isset($data) && isset($user) != 0) {
                if ($data->usm_cel != "" && $data->usm_cel != null) {
                    SMS::create([
                        'tusms_turid' => $shift->tu_id,
                        'tusms_empid' => $shift->emp_id,
                        'tusms_sucid' => $shift->suc_id,
                        'tusms_preid' => $shift->pres_id,
                        'tusms_usuid' => $shift->us_id,
                        'tusms_pacnom' => $user->name,
                        'tusms_celenv' => trim($data->usm_cel),
                        "tusms_msg" => ucwords(mb_strtolower(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'))) . " le recuerda su turno para el " . date("d/m/Y", strtotime($shift->tu_fec)) . " a las " . date("H:i", strtotime($shift->tu_hora)) . " hs. Responda NO para cancelar el turno",
                        'tusms_tipo' => '1',
                        'tusms_para' => '1',
                        'tusms_pass' => '1',
                        'tusms_priori' => '1',
                        'tusms_envfec' => date("Y-m-d"),
                        'tusms_envtime' => date("H:i:s")
                    ]);
                }
            }
        }

        echo "mensaje creado";
    }

    public function verifyPhone($number) {
        $num = preg_replace('/[^0-9+]/', '', $number);
        return trim($num);
    }

    public function get_numerics($text) {
// number selection
        $selection = "0123456789";
        $arr = str_split($text);
        $len = count($arr);
        $count = -1;
        $output = "";
        while (++$count < $len) {
            $selected = $arr[$count];
            if ($selected != "") {
                if (strpos($selection, $selected) !== false)
                    $output .= $selected;
            }
        }
        return $output;
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

    public function setUrl($url) {
        $value = str_replace("/", '-', $url);
        $value = str_replace(" ", '+', $value);
        return $value;
    }

//reinaldo email existance validation

    public function validateMailExistance($email) {



        $vmail = new verifyEmail();
        $vmail->setStreamTimeoutWait(20);
        $vmail->Debug = TRUE;
        $vmail->Debugoutput = 'html';

        $vmail->setEmailFrom('turnos@turnonet.com');

        if (!$this->genericEmail($email)) {
            return true;
        } else {
            if ($vmail->check($email)) {

                return true;
            } elseif (verifyEmail::validate($email)) {


                if (BlackListMail::where('email', $email)->count() != 0) {

                    BlackListMail::create(['email' => $email]);
                }

                return false;
            } else {

                if (BlackListMail::where('email', $email)->count() != 0) {

                    BlackListMail::create(['email' => $email]);
                }

                return false;
            }
        }
    }

    public function genericEmail($email) {
        if (strpos($email, 'gmail') !== false) {
            return true;
        } else if (strpos($email, 'yahoo') !== false) {
            return true;
        } else if (strpos($email, 'hotmail') !== false) {
            return true;
        } else if (strpos($email, 'outlook') !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function setNoty($content) {
        $content = str_replace("\n", '<br>', $content);
        return $content;
    }

}

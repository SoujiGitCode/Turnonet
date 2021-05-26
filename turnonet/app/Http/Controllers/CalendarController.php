<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersApp;
use App\Visits;
use App\Shedules;
use App\BlockedShedules;
use App\Services;
use App\Shift;
use App\Country;
use App\Business;
use Route;
use Mail;
use Redirect;
use Session;
use Cache;
use DateTime;
use DateInterval;
use DatePeriod;
use Auth;
use DB;
use Log;
use App\Http\Requests;

class CalendarController extends Controller {

    /**
     * Validate access
     */
    public function __construct() {
        $this->middleware('AuthApi');
    }

    public function index($month, $year, $business, $branch, $lender, $services_selected, $overturn) {
        $get_business = Cache::rememberForever('business_' . $business, function() use($business) {
                    return Business::where('em_id', $business)->first();
                });
        $country_id = $get_business->em_pais;
        for ($i = 0; $i < 7; $i++):
//horarios del prestador
            Cache::rememberForever('shedules_lenders_' . $lender . '_' . $i, function ()use($lender, $i) {
                return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_presid', $lender)->first();
            });
//horarios de la sucursal
            Cache::rememberForever('shedules_branch_' . $branch . '_' . $i, function ()use($branch, $i) {
                return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_sucid', $branch)->where('lab_presid', 0)->first();
            });
//horarios de la empresa
            Cache::rememberForever('shedules_business_' . $business . '_' . $i, function ()use($business, $i) {
                return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_empid', $business)->where('lab_sucid', 0)->where('lab_presid', 0)->first();
            });
        endfor;
//confinguracion de la empresa
        Cache::rememberForever('settings_business_' . $business, function ()use($business) {
            return DB::table('tu_emps_con')->where('emp_id', $business)->where('suc_id', '0')->where('pres_id', '0')->first();
        });
//confinguracion de la sucursal
        Cache::rememberForever('settings_branch_' . $branch, function ()use($branch) {
            return DB::table('tu_emps_con')->where('suc_id', $branch)->where('pres_id', '0')->first();
        });
//confinguracion de la sucursal
        Cache::rememberForever('settings_lender_' . $lender, function ()use($lender) {
            return DB::table('tu_emps_con')->where('pres_id', $lender)->first();
        });
//verififo si hay servicios seleccionados
        $serv_totime = 0;
        if ($services_selected != '0') {
//busco los servicios
            for ($i = 0; $i <= substr_count($services_selected, '-'); $i++) {
                $service = explode('-', $services_selected);
                $service_id = $service[$i];
                $get_service = Cache::rememberForever('service_' . $service_id, function ()use($service_id) {
                            return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                        });
//sumo la duracion los servicios
                if (isset($get_service) != 0) {
                    $set_horamt = explode(":", $get_service->serv_tudur);
                    if ($set_horamt[0] != '') {
                        $set_horamt2 = $set_horamt[0] * 60;
                        $max_time = $set_horamt2 + $set_horamt[1];
                        $serv_totime += $max_time;
                    }
                }
            }
        }
        $json = array();
        $today = date("j");
        $day_week = date("w", mktime(0, 0, 0, $month, 1, $year)) + 7;
        $last_day = date("d", (mktime(0, 0, 0, $month + 1, 1, $year) - 1));
        $last_cell = $day_week + $last_day;
        $holidays = array();
        $day_end = $year . '-' . $month . '-' . $last_day;
        $day_init = $year . '-' . $month . '-01';
        $get_holidays = DB::table('tu_emps_fer')
                ->where('fer_presid', $lender)
                ->where('fer_estado', '1')
                ->where('fer_date', '>=', date("Y-m-d", strtotime($day_init)))
                ->where('fer_date', '<=', date("Y-m-d", strtotime($day_end)))
                ->get();
        foreach ($get_holidays as $rs) {
            $holidays[] = array(
                'date' => $rs->fer_date
            );
        }
        for ($i = 1; $i <= 43; $i++) {
            if ($i == $day_week) {
                $day = 1;
            }
            if ($i < $day_week || $i >= $last_cell) {
                $json[] = array('day' => '', 'active' => '0', 'date' => '', 'class' => "cal_nodia");
            } else {
                $date = date("Y-m-d", strtotime($year . '-' . $month . '-' . $day));
                $name_day = $this->nameDay(date("w", strtotime($date))) . ' ' . date("d", strtotime($date)) . ' de ' . $this->NameMonth(date("m", strtotime($date))) . ' del ' . date("Y", strtotime($date));
                if (date("Y-m-d") > $date) {
                    $active = 0;
                    $class = 'cal_nodianh';
                } else {
                    if (!Cache::has('shedules_business_' . $business . '_' . date("w", strtotime($date))) && !Cache::has('shedules_lenders_' . $lender . '_' . date("w", strtotime($date))) && !Cache::get('shedules_branch_' . $branch . '_' . date("w", strtotime($date))) && $overturn == 0) {
                        $active = 0;
                        $class = 'cal_nodianh';
                    } else {
                        if (!empty($holidays) && !empty($this->search($holidays, 'date', $date)) && $overturn != 1) {
                            $active = 0;
                            $class = 'cal_nodianh';
                        } else {
                            $availability = $this->availability($date, $business, $branch, $lender, $serv_totime, $overturn, $get_holidays)->getData();
                            if (!empty($availability->times)) {
                                $active = 1;
                                $class = "cal_dia";
                            } else {
                                $active = 0;
                                $class = 'cal_nodianh';
                            }
                        }
                    }
                }
                $json[] = array(
                    "class" => $class,
                    'day' => $day,
                    'title' => $name_day,
                    'active' => $active,
                    'date' => $date
                );
                $day++;
            }
        }
        if (date("m") == $month) {
            $prevmonth = $month;
            $prevyear = $year;
        } else {
            $prevmonth = date("m", mktime(12, 0, 0, $month - 1, 1, $year));
            $prevyear = date("Y", mktime(12, 0, 0, $month - 1, 1, $year));
        }
        $nextmonth = date("m", mktime(12, 0, 0, $month + 1, 1, $year));
        $nextyear = date("Y", mktime(12, 0, 0, $month, 1, $year));
        if ($nextmonth == "01") {
            $prevyear = $year;
            $nextyear = $year + 1;
        }
        return response()->json([
                    "calendar" => $json,
                    "name_month" => $this->NameMonth(date("m", strtotime($date))),
                    'prevmonth' => $prevmonth,
                    "prevyear" => $prevyear,
                    'nextmonth' => $nextmonth,
                    'nextyear' => $nextyear,
                    "month_act" => date("m", strtotime($date))
        ]);
    }

    public function availability($date, $business, $branch, $lender, $serv_totime, $overturn, $holidays) {
        $times_blocked = array();
        $get_times = DB::table('blocked_schedules')->where('pres_id', $lender)->where('tur_date', $date)->get();
        foreach ($get_times as $rs) {
            array_push($times_blocked, $rs->tur_time);
        }
        $get_times = DB::table('tu_turnos')->where('tu_estado', 'ALTA')->where('pres_id', $lender)->where('tu_fec', $date)->get();
        foreach ($get_times as $rs) {
            if ($this->contar_horas($times_blocked, $rs->tu_hora) <= 0) {
                array_push($times_blocked, $rs->tu_hora);
            }
        }
        $times_empty = array();
        $opening = '';
        $deaperture = '';
        $opening_2 = '';
        $deaperture_2 = '';
        $horarios_lender = DB::table('tu_dlab')->where('lab_presid', $lender)->count();
//horarios de la empresa
        if (Cache::has('shedules_business_' . $business . '_' . date("w", strtotime($date)))) {
            $shedules_business = Cache::get('shedules_business_' . $business . '_' . date("w", strtotime($date)));
        } else {
            $i = date("w", strtotime($date));
            $shedules_business = Cache::rememberForever('shedules_business_' . $business . '_' . $i, function ()use($business, $i) {
                        return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_empid', $business)->where('lab_sucid', 0)->where('lab_presid', 0)->first();
                    });
        }
//horarios del prestador
        if (Cache::has('shedules_lenders_' . $lender . '_' . date("w", strtotime($date)))) {
            $shedules_lenders = Cache::get('shedules_lenders_' . $lender . '_' . date("w", strtotime($date)));
        } else {
            $i = date("w", strtotime($date));
            $shedules_lenders = Cache::rememberForever('shedules_lenders_' . $lender . '_' . $i, function ()use($lender, $i) {
                        return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_presid', $lender)->first();
                    });
        }
//horarios de la sucursal
        if (Cache::has('shedules_branch_' . $branch . '_' . date("w", strtotime($date)))) {
            $shedules_branch = Cache::get('shedules_branch_' . $branch . '_' . date("w", strtotime($date)));
        } else {
            $i = date("w", strtotime($date));
            $shedules_branch = Cache::rememberForever('shedules_branch_' . $branch . '_' . $i, function ()use($branch, $i) {
                        return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_sucid', $branch)->where('lab_presid', 0)->first();
                    });
        }
// get bussiness
        if (Cache::has('business_' . $business)) {
            $get_business = Cache::get('business_' . $business);
        } else {
            $get_business = Cache::rememberForever('business_' . $business, function() use($business) {
                        return Business::where('em_id', $business)->first();
                    });
        }
// set TimeZone
        if ($get_business->em_pais == "1") {
            date_default_timezone_set('America/Argentina/Buenos_Aires');
        }
        if ($get_business->em_pais == "2") {
            date_default_timezone_set('America/Santiago');
        }
        if ($get_business->em_pais == "3") {
            date_default_timezone_set('America/Sao_Paulo');
        }
        if ($get_business->em_pais == "4") {
            date_default_timezone_set('America/Montevideo');
        }
        if ($get_business->em_pais == "5") {
            date_default_timezone_set('America/La_Paz');
        }
        if ($get_business->em_pais == "6") {
            date_default_timezone_set('America/Asuncion');
        }
        if ($get_business->em_pais == "7") {
            date_default_timezone_set('America/Mexico_City');
        }
        if ($get_business->em_pais == "8") {
            date_default_timezone_set('America/Guayaquil');
        }
        if ($get_business->em_pais == "9") {
            date_default_timezone_set('America/Bogota');
        }
        $times = array();
        if (date("Y-m-d", strtotime($date)) < date("Y-m-d")) {
            return response()->json(["times" => array()]);
        }
        if (isset($shedules_business) == 0 && isset($shedules_branch) == 0 && isset($shedules_lenders) == 0 && $overturn == 0) {
            return response()->json(["times" => array()]);
        }
        if (Cache::has('settings_business_' . $business)) {
            $gmt = Cache::get('settings_business_' . $business);
        } else {
            $gmt = Cache::rememberForever('settings_business_' . $business, function ()use($business) {
                        return DB::table('tu_emps_con')->where('emp_id', $business)->where('suc_id', '0')->where('pres_id', '0')->first();
                    });
        }
        if (Cache::has('settings_lender_' . $lender)) {
            $gmt = Cache::get('settings_lender_' . $lender);
        } else {
            $gmt = Cache::rememberForever('settings_lender_' . $lender, function ()use($lender) {
                        return DB::table('tu_emps_con')->where('pres_id', $lender)->first();
                    });
        }
        if (isset($gmt) != 0 && $gmt->cf_daysp != 0) {
            $mdays = "-" . $gmt->cf_daysp . ' days';
            $newDate = date("Y-m-d", strtotime($date . $mdays));
            if ($newDate < date("Y-m-d") && $overturn == 0) {
                return response()->json(["times" => array()]);
            }
        }
        if (isset($gmt) != 0 && $gmt->cf_days != 0 && $overturn == 0) {
            $mdays = "+" . $gmt->cf_days . ' days';
            $newDate = date("Y-m-d", strtotime(date("Y-m-d") . $mdays));
            if ($newDate <= date("Y-m-d", strtotime($date))) {
                return response()->json(["times" => array()]);
            }
        }
        if (isset($gmt) != 0 && $gmt->cf_turt == "00:00:00" && $overturn == 0) {
            return response()->json(["times" => array()]);
        }
        if (isset($shedules_lenders) == 0 && $horarios_lender != 0 && $overturn == 0) {
            return response()->json(["times" => array()]);
        }
//no laborables registrados
        if (isset($shedules_business) != 0) {
            $opening = $shedules_business->lab_hin;
            $deaperture = $shedules_business->lab_hou;
            $opening_2 = $shedules_business->lab_hin2;
            $deaperture_2 = $shedules_business->lab_hou2;
        }
        if (isset($shedules_lenders) != 0) {
            $opening = $shedules_lenders->lab_hin;
            $deaperture = $shedules_lenders->lab_hou;
            $opening_2 = $shedules_lenders->lab_hin2;
            $deaperture_2 = $shedules_lenders->lab_hou2;
        }
        if ($overturn == 1) {
            $opening = '06:00:00';
            $deaperture = "24:00:00";
        }
        if ($opening == "" || $deaperture == "") {
            return response()->json(["times" => array()]);
        }
         if ($date == date("Y-m-d") && $deaperture < date("H:i:s") && $opening_2 == "00:00:00") {
            return response()->json(["times" => array()]);
        }
        if ($date == date("Y-m-d") && $deaperture_2 < date("H:i:s") && $opening_2 != "00:00:00") {
            return response()->json(["times" => array()]);
        }
        $set_turtime = "00:00:00";
        if (isset($gmt) != 0) {
            $set_turtime = $gmt->cf_turt;
        }
//busco la configuracion activa y obtengo la duracion del turno;
        $interval = 30;
        if (isset($gmt) != 0 && $serv_totime != "0") {
            $def_time = '00:00:00';
            $set_extim = explode(":", $def_time);
            $set_estim2 = $set_extim[0] * 60;
            $max_deftime = $set_estim2 + $set_extim[1];
            if ($serv_totime > $max_deftime) {
                $set_turtime = date("H:i:s", mktime(0, 0 + $serv_totime, 0, 1, 2, 2013));
            }
        }
        $v_HorasPartes = explode(":", $set_turtime);
        if ($set_turtime == "00:00:00") {
            return response()->json(["times" => array()]);
        }
        $interval = ($v_HorasPartes[0] * 60) + $v_HorasPartes[1];
        $init = new DateTime($opening);
        $end = new DateTime($deaperture);
        $end->modify('+1 second');
        if ($init > $end) {
            $end->modify('+1 day');
        }
        $interval = new DateInterval('PT' . $interval . 'M');
        $period = new DatePeriod($init, $interval, $end);
        if (isset($gmt) != 0) {
            $simultaneo = $gmt->cf_simtu;
        } else {
            $simultaneo = 1;
        }
        foreach ($period as $hour) {
            $time = $hour->format('H:i:s');
            if ($time != '00:00:00') {
                //Log::info($business.' duracion del turno:'.$set_turtime.' Hora tomada:'.$time);

                $hora_final = $this->getHoraFinal($time, date("H:i:s", strtotime($set_turtime)));

                $hora_final = strtotime('-5 minute', strtotime($hora_final));
                $hora_final=date("H:i:s",$hora_final);

                if ($this->validateTime($business, $time, $hora_final, $times_blocked, $simultaneo, $overturn)) {
                    if ($overturn == 1) {
                        $times[] = array(
                            'time' => $time,
                        );
                    } else {

                        $contar_horas_init = $this->contar_horas($times_blocked, $time);
                        $contar_horas_end = $this->contar_horas($times_blocked, $hora_final);



                        if ($contar_horas_end < $simultaneo) {
                            if ($date == date("Y-m-d") && date("H:i:s", strtotime($time)) > date("H:i:s")) {
                                if ($get_business->em_id == "855" || $get_business->em_id == "637" || $get_business->em_id == "3340") {
                                    $times[] = array(
                                        'time' => $time,
                                        'hora_final' => $hora_final,
                                    );
                                } else {
                                    if ($time < date("H:i:s", strtotime($deaperture)) || $overturn == 1) {
                                        $times[] = array(
                                            'time' => $time,
                                            'hora_final' => $hora_final,
                                        );
                                    }
                                }
                            }
                            if ($date > date("Y-m-d")) {
                                if ($get_business->em_id == "855" || $get_business->em_id == "637" || $get_business->em_id == "3340") {
                                    $times[] = array(
                                        'time' => $time,
                                        'hora_final' => $hora_final,
                                    );
                                } else {
                                    if ($time < date("H:i:s", strtotime($deaperture)) || $overturn == 1) {
                                        $times[] = array(
                                            'time' => $time,
                                            'hora_final' => $hora_final,
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($overturn == 0 && $opening_2 != "00:00:00") {
            $init = new DateTime($opening_2);
            $end = new DateTime($deaperture_2);
            $end->modify('+1 second');
            $period_2 = new DatePeriod($init, $interval, $end);
            foreach ($period_2 as $hour) {
                $time = $hour->format('H:i:s');
                if ($time != '00:00:00') {
//Log::info($business.' duracion del turno:'.$set_turtime.' Hora tomada:'.$time);
                    $hora_final = $this->getHoraFinal($time, date("H:i:s", strtotime($set_turtime)));
                    $hora_final = strtotime('-5 minute', strtotime($hora_final));
                $hora_final=date("H:i:s",$hora_final);

                    if ($this->validateTime($business, $time, $hora_final, $times_blocked, $simultaneo, $overturn)) {
                        if ($overturn == 1) {
                            $times[] = array(
                                'time' => $time,
                            );
                        } else {


                            $contar_horas_init = $this->contar_horas($times_blocked, $time);
                            $contar_horas_end = $this->contar_horas($times_blocked, $hora_final);


                            if ($contar_horas_init < $simultaneo) {
                                if ($date == date("Y-m-d") && date("H:i:s", strtotime($time)) > date("H:i:s")) {
                                    if ($get_business->em_id == "855" || $get_business->em_id == "637" || $get_business->em_id == "3340") {
                                        $times[] = array(
                                            'time' => $time,
                                        );
                                    } else {
                                        if ($time < date("H:i:s", strtotime($deaperture_2))) {
                                            $times[] = array(
                                                'time' => $time,
                                            );
                                        }
                                    }
                                }
                                if ($date > date("Y-m-d")) {
                                    if ($get_business->em_id == "855" || $get_business->em_id == "637" || $get_business->em_id == "3340") {
                                        $times[] = array(
                                            'time' => $time,
                                        );
                                    } else {
                                        if ($time < date("H:i:s", strtotime($deaperture_2))) {
                                            $times[] = array(
                                                'time' => $time,
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return response()->json(["times" => $times]);
    }

    public function times($date, $business, $branch, $lender, $services_selected, $overturn) {
        $times_blocked = array();
        $name_day = $this->nameDay(date("w", strtotime($date))) . ' ' . date("d", strtotime($date)) . ' de ' . $this->NameMonth(date("m", strtotime($date))) . ' del ' . date("Y", strtotime($date));
        $get_times = DB::table('blocked_schedules')->where('pres_id', $lender)->where('tur_date', $date)->get();
        foreach ($get_times as $rs) {
            array_push($times_blocked, $rs->tur_time);
        }
        $get_times = DB::table('tu_turnos')->where('tu_estado', 'ALTA')->where('pres_id', $lender)->where('tu_fec', $date)->get();
        foreach ($get_times as $rs) {
            if ($this->contar_horas($times_blocked, $rs->tu_hora) <= 0) {
                array_push($times_blocked, $rs->tu_hora);
            }
        }
        $times_empty = array();
        $opening = '';
        $deaperture = '';
        $opening_2 = '';
        $deaperture_2 = '';
        $horarios_lender = DB::table('tu_dlab')->where('lab_presid', $lender)->count();
//horarios de la empresa
        if (Cache::has('shedules_business_' . $business . '_' . date("w", strtotime($date)))) {
            $shedules_business = Cache::get('shedules_business_' . $business . '_' . date("w", strtotime($date)));
        } else {
            $i = date("w", strtotime($date));
            $shedules_business = Cache::rememberForever('shedules_business_' . $business . '_' . $i, function ()use($business, $i) {
                        return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_empid', $business)->where('lab_sucid', 0)->where('lab_presid', 0)->first();
                    });
        }
//horarios del prestador
        if (Cache::has('shedules_lenders_' . $lender . '_' . date("w", strtotime($date)))) {
            $shedules_lenders = Cache::get('shedules_lenders_' . $lender . '_' . date("w", strtotime($date)));
        } else {
            $i = date("w", strtotime($date));
            $shedules_lenders = Cache::rememberForever('shedules_lenders_' . $lender . '_' . $i, function ()use($lender, $i) {
                        return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_presid', $lender)->first();
                    });
        }
//horarios de la sucursal
        if (Cache::has('shedules_branch_' . $branch . '_' . date("w", strtotime($date)))) {
            $shedules_branch = Cache::get('shedules_branch_' . $branch . '_' . date("w", strtotime($date)));
        } else {
            $i = date("w", strtotime($date));
            $shedules_branch = Cache::rememberForever('shedules_branch_' . $branch . '_' . $i, function ()use($branch, $i) {
                        return DB::table('tu_dlab')->where('lab_dian', $i)->where('lab_sucid', $branch)->where('lab_presid', 0)->first();
                    });
        }
// get bussiness
        if (Cache::has('business_' . $business)) {
            $get_business = Cache::get('business_' . $business);
        } else {
            $get_business = Cache::rememberForever('business_' . $business, function() use($business) {
                        return Business::where('em_id', $business)->first();
                    });
        }
// set TimeZone
        if ($get_business->em_pais == "1") {
            date_default_timezone_set('America/Argentina/Buenos_Aires');
        }
        if ($get_business->em_pais == "2") {
            date_default_timezone_set('America/Santiago');
        }
        if ($get_business->em_pais == "3") {
            date_default_timezone_set('America/Sao_Paulo');
        }
        if ($get_business->em_pais == "4") {
            date_default_timezone_set('America/Montevideo');
        }
        if ($get_business->em_pais == "5") {
            date_default_timezone_set('America/La_Paz');
        }
        if ($get_business->em_pais == "6") {
            date_default_timezone_set('America/Asuncion');
        }
        if ($get_business->em_pais == "7") {
            date_default_timezone_set('America/Mexico_City');
        }
        if ($get_business->em_pais == "8") {
            date_default_timezone_set('America/Guayaquil');
        }
        if ($get_business->em_pais == "9") {
            date_default_timezone_set('America/Bogota');
        }
        $times = array();
        if (date("Y-m-d", strtotime($date)) < date("Y-m-d")) {
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'El dia es menor a el dia actual']);
        }
        if (isset($shedules_business) == 0 && isset($shedules_branch) == 0 && isset($shedules_lenders) == 0 && $overturn == 0) {
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'No tiene horarios registrados para el dia']);
        }
        if (Cache::has('settings_business_' . $business)) {
           
            $gmt = Cache::get('settings_business_' . $business);
        } else {
       
            $gmt = Cache::rememberForever('settings_business_' . $business, function ()use($business) {
                        return DB::table('tu_emps_con')->where('emp_id', $business)->where('suc_id', '0')->where('pres_id', '0')->first();
                    });
        }
        if (Cache::has('settings_lender_' . $lender)) {
       
            $gmt = Cache::get('settings_lender_' . $lender);

         

        } else {
           
            $gmt = Cache::rememberForever('settings_lender_' . $lender, function ()use($lender) {
                        return DB::table('tu_emps_con')->where('pres_id', $lender)->first();
                    });
        }
        if (isset($gmt) != 0 && $gmt->cf_daysp != 0) {
            $mdays = "-" . $gmt->cf_daysp . ' days';
            $newDate = date("Y-m-d", strtotime($date . $mdays));
            if ($newDate < date("Y-m-d") && $overturn == 0) {
                return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'No tiene dispoible dias para dar turnos ']);
            }
        }
        if (isset($gmt) != 0 && $gmt->cf_days != 0 && $overturn == 0) {
            $mdays = "+" . $gmt->cf_days . ' days';
            $newDate = date("Y-m-d", strtotime(date("Y-m-d") . $mdays));
            if ($newDate <= date("Y-m-d", strtotime($date))) {
                return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'Se ha superado el limite de dias para dar turnos']);
            }
        }
        if (isset($gmt) != 0 && $gmt->cf_turt == "00:00:00" && $overturn == 0) {
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'La duracion promedio del turno es 00:00:00']);
        }
        if (isset($shedules_lenders) == 0 && $horarios_lender != 0 && $overturn == 0) {
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'No tiene la configuracion de empresa/prestador']);
        }
//no laborables registrados
        if (DB::table('tu_emps_fer')->where('fer_presid', $lender)->where('fer_estado', '1')->where('fer_date', date("Y-m-d", strtotime($date)))->count() != 0 && $overturn != 1) {
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'Dia no laborable']);
        }
        if (isset($shedules_business) != 0) {
            $opening = $shedules_business->lab_hin;
            $deaperture = $shedules_business->lab_hou;
            $opening_2 = $shedules_business->lab_hin2;
            $deaperture_2 = $shedules_business->lab_hou2;
        }
        if (isset($shedules_lenders) != 0) {
            $opening = $shedules_lenders->lab_hin;
            $deaperture = $shedules_lenders->lab_hou;
            $opening_2 = $shedules_lenders->lab_hin2;
            $deaperture_2 = $shedules_lenders->lab_hou2;
        }
        if ($overturn == 1) {
            $opening = '06:00:00';
            $deaperture = "24:00:00";
        }
        if ($opening == "" || $deaperture == "") {
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'No tiene horas de inicio o cierre de la jornada']);
        }
        if ($date == date("Y-m-d") && $deaperture < date("H:i:s") && $opening_2 == "00:00:00") {
////echo "10<br>";
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'La hora de cierre de la jornada es menor a la hora actual','hora actual'=>date("H:i:s"),'hora de cierra'=>$deaperture]);
        }

        if ($date == date("Y-m-d") && $deaperture_2 < date("H:i:s") && $opening_2 != "00:00:00") {
////echo "10<br>";
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'La hora de cierre de la jornada es menor a la hora actual','hora actual'=>date("H:i:s"),'hora de cierra'=>$deaperture]);
        }
        $serv_totime = 0;
        $set_turtime = "00:00:00";
        if (isset($gmt) != 0) {
            $set_turtime = $gmt->cf_turt;
        }
//busco la configuracion activa y obtengo la duracion del turno;
        $interval = 30;
//verififo si hay servicios seleccionados
        if ($services_selected != '0') {
//busco los servicios
            for ($i = 0; $i <= substr_count($services_selected, '-'); $i++) {
                $service = explode('-', $services_selected);
                $service_id = $service[$i];
                $get_service = Cache::rememberForever('service_' . $service_id, function ()use($service_id) {
                            return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                        });
//sumo la duracion los servicios
                if (isset($get_service) != 0) {
                    $set_horamt = explode(":", $get_service->serv_tudur);
                    if ($set_horamt[0] != '') {
                        $set_horamt2 = $set_horamt[0] * 60;
                        $max_time = $set_horamt2 + $set_horamt[1];
                        $serv_totime += $max_time;
                    }
                }
            }
            if (isset($gmt) != 0) {
                $def_time = '00:00:00';
                $set_extim = explode(":", $def_time);
                $set_estim2 = $set_extim[0] * 60;
                $max_deftime = $set_estim2 + $set_extim[1];
                if ($serv_totime > $max_deftime) {
                    $set_turtime = date("H:i:s", mktime(0, 0 + $serv_totime, 0, 1, 2, 2013));
                }
            }
        }
//echo $set_turtime;
        if ($set_turtime == "00:00:00") {
            return response()->json(["times" => array(), "name_day" => $name_day, 'error' => 'La duracion final del turno es 00:00:00']);
        }
////echo "hola".$set_turtime;
        $v_HorasPartes = explode(":", $set_turtime);
        $interval = ($v_HorasPartes[0] * 60) + $v_HorasPartes[1];
//echo $interval;
        $init = new DateTime($opening);
        $end = new DateTime($deaperture);
        $end->modify('+1 second');
        if ($init > $end) {
            $end->modify('+1 day');
        }
        $interval = new DateInterval('PT' . $interval . 'M');
        $period = new DatePeriod($init, $interval, $end);
        $e = 0;
        if (isset($gmt) != 0) {
            $simultaneo = $gmt->cf_simtu;
        } else {
            $simultaneo = 1;
        }
////echo print_r($times_blocked);
        foreach ($period as $hour) {
            $time = $hour->format('H:i:s');
            if ($time != '00:00:00') {
//Log::info($business.' duracion del turno:'.$set_turtime.' Hora tomada:'.$time);
                $hora_final = $this->getHoraFinal($time, date("H:i:s", strtotime($set_turtime)));
                $hora_final = strtotime('-5 minute', strtotime($hora_final));
                $hora_final=date("H:i:s",$hora_final);

                $validateTime = $this->validateTime($business, $time, $hora_final, $times_blocked, $simultaneo, $overturn);
                if ($this->validateTime($business, $time, $hora_final, $times_blocked, $simultaneo, $overturn)) {
                    if ($overturn == 1) {
                        $e = $e + 1;
                        $times[] = array(
                            "id" => $e,
                            'time' => $time,
                            "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                            "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                            'time_format' => date("H:i", strtotime($time))
                        );
                    } else {

                        $contar_horas_init = $this->contar_horas($times_blocked, $time);
                        $contar_horas_end = $this->contar_horas($times_blocked, $hora_final);

                        if ($simultaneo && $contar_horas_init < $simultaneo) {
                            $e = $e + 1;
                            if ($date == date("Y-m-d") && date("H:i:s", strtotime($time)) > date("H:i:s")) {
                                if ($get_business->em_id == "855" || $get_business->em_id == "637" || $get_business->em_id == "3340") {
                                    $times[] = array(
                                        "id" => $e,
                                        'time' => $time,
                                        "turnos_simulteneos"=>$simultaneo,
                                        "turnos_tomados_init" => $contar_horas_init,
                                        "turnos_tomados_end" => $contar_horas_end,
                                        'hora_final' => $hora_final,
                                        "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                        "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                        'time_format' => date("H:i", strtotime($time))
                                    );
                                } else {
                                    if ($time < date("H:i:s", strtotime($deaperture)) || $overturn == 1) {
                                        $times[] = array(
                                            "id" => $e,
                                            'time' => $time,
                                            'hora_final' => $hora_final,
                                            "turnos_simulteneos"=>$simultaneo,
                                            "turnos_tomados_init" => $contar_horas_init,
                                            "turnos_tomados_end" => $contar_horas_end,
                                            "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                            "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                            'time_format' => date("H:i", strtotime($time))
                                        );
                                    }
                                }
                            }
                            if ($date > date("Y-m-d")) {
                                if ($get_business->em_id == "855" || $get_business->em_id == "637" || $get_business->em_id == "3340") {
                                    $times[] = array(
                                        "id" => $e,
                                        'time' => $time,
                                        'hora_final' => $hora_final,
                                        "turnos_simulteneos"=>$simultaneo,
                                        "turnos_tomados_init" => $contar_horas_init,
                                        "turnos_tomados_end" => $contar_horas_end,
                                        "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                        "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                        'time_format' => date("H:i", strtotime($time))
                                    );
                                } else {
                                    if ($time < date("H:i:s", strtotime($deaperture)) || $overturn == 1) {
                                        $times[] = array(
                                            "id" => $e,
                                            'time' => $time,
                                            'hora_final' => $hora_final,
                                            "turnos_simulteneos"=>$simultaneo,
                                            "turnos_tomados_init" => $contar_horas_init,
                                            "turnos_tomados_end" => $contar_horas_end,
                                            "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                            "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                            'time_format' => date("H:i", strtotime($time))
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($overturn == 0 && $opening_2 != "00:00:00") {
            $init = new DateTime($opening_2);
            $end = new DateTime($deaperture_2);
            $end->modify('+1 second');
            $period_2 = new DatePeriod($init, $interval, $end);
            foreach ($period_2 as $hour) {
                $time = $hour->format('H:i:s');
                if ($time != '00:00:00') {

                    $hora_final = $this->getHoraFinal($time, date("H:i:s", strtotime($set_turtime)));
                    $hora_final = strtotime('-5 minute', strtotime($hora_final));
                $hora_final=date("H:i:s",$hora_final);

                    if ($this->validateTime($business, $time, $hora_final, $times_blocked, $simultaneo, $overturn)) {
                        if ($overturn == 1) {
                            $e = $e + 1;
                            $times[] = array(
                                "id" => $e,
                                'time' => $time,
                                "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                'time_format' => date("H:i", strtotime($time))
                            );
                        } else {


                            $contar_horas_init = $this->contar_horas($times_blocked, $time);
                            $contar_horas_end = $this->contar_horas($times_blocked, $hora_final);


                            if ($contar_horas_init < $simultaneo) {
                                $e = $e + 1;
                                if ($date == date("Y-m-d") && date("H:i:s", strtotime($time)) > date("H:i:s")) {
                                    if ($get_business->em_id == "855" || $get_business->em_id == "637" || $get_business->em_id == "3340") {
                                        $times[] = array(
                                            "id" => $e,
                                            'time' => $time,
                                            "turnos_simulteneos"=>$simultaneo,
                                            "turnos_tomados_init" => $contar_horas_init,
                                            "turnos_tomados_end" => $contar_horas_end,
                                            "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                            "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                            'time_format' => date("H:i", strtotime($time))
                                        );
                                    } else {
                                        if ($time < date("H:i:s", strtotime($deaperture_2))) {
                                            $times[] = array(
                                                "id" => $e,
                                                'time' => $time,
                                                "turnos_simulteneos"=>$simultaneo,
                                                "turnos_tomados_init" => $contar_horas_init,
                                                "turnos_tomados_end" => $contar_horas_end,
                                                "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                                "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                                'time_format' => date("H:i", strtotime($time))
                                            );
                                        }
                                    }
                                }
                                
                                if ($date > date("Y-m-d")) {
                                    if ($get_business->em_id == "855" || $get_business->em_id == "637" || $get_business->em_id == "3340") {
                                        $times[] = array(
                                            "id" => $e,
                                            'time' => $time,
                                            "turnos_simulteneos"=>$simultaneo,
                                            "turnos_tomados_init" => $contar_horas_init,
                                            "turnos_tomados_end" => $contar_horas_end,
                                            "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                            "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                            'time_format' => date("H:i", strtotime($time))
                                        );
                                    } else {
                                        if ($time != date("H:i:s", strtotime($deaperture_2))) {
                                            $times[] = array(
                                                "id" => $e,
                                                'time' => $time,
                                                "turnos_simulteneos"=>$simultaneo,
                                                "turnos_tomados_init" => $contar_horas_init,
                                                "turnos_tomados_end" => $contar_horas_end,
                                                "title" => "Solicitar Turno a las " . date("H:i", strtotime($time)) . " del día " . $name_day,
                                                "text" => $name_day . ' a las ' . date("H:i", strtotime($time)) . ' hs.',
                                                'time_format' => date("H:i", strtotime($time))
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return response()->json(["times" => $times, "name_day" => $name_day]);
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

    public function search($array, $key, $value) {
        $results = array();
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }
            foreach ($array as $subarray) {
                $results = array_merge($results, $this->search($subarray, $key, $value));
            }
        }
        return $results;
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
        $sum_hrs = $horas . ':' . $minutos . ':' . $segundos;
        return ($sum_hrs);
    }

    /**
     * Horas 
     * @return type
     */
    public function getHoraFinal($hora1, $hora2) {
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
        $sum_hrs = str_replace("-", "", $sum_hrs);
        return date("H:i:s",strtotime($sum_hrs));
    }

    public function contar_horas($a, $buscado) {
        $i = 0;
        if (!is_array($a)) {
            return $i;
        }
        if (empty($a)) {
            return $i;
        }
        $str = ", " . implode(", ", $a) . ",";
        $count = substr_count($str, ' ' . $buscado . ',');
        return $count;
    }

    public function validateTime($id, $init, $end, $times_blocked, $simultaneo, $overturn) {
            

            
        if ($overturn == 1) {
            return true;
        } else {

            
            if (date("H", strtotime($init)) <= 24 && date("H", strtotime($end)) <= 24) {
                $interval = new DateInterval('PT5M');
                $period_empty = new DatePeriod(new DateTime($init), $interval, new DateTime($end));
                foreach ($period_empty as $hour) {
                    if ($end > $hour->format('H:i:s')) {
                        if ($this->contar_horas($times_blocked, $hour->format('H:i:s')) >= $simultaneo) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
    }

}

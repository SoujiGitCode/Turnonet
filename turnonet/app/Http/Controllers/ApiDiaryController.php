<?php

namespace App\Http\Controllers;

use App\Mail\TuMailBusiness;
use Illuminate\Http\Request;
use Route;
use App\Activities;
use App\Visits;
use App\Business;
use App\Branch;
use App\UsersApp;
use App\Directory;
use App\BusinessFields;
use App\ClientsCustomization;
use App\LenderNotifications;
use App\SMS;
use App\Lenders;
use App\Country;
use App\Services;
use App\Shift;
use Redirect;
use DB;
use Mail;
use Cache;
use Session;
use Auth;
use URL;
use App\Http\Requests;

class ApiDiaryController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('AuthApi');
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists($date, $status, $type_shift,$business,$branch,$lender) {
        try {
            $json = array();
            $type_shift = $type_shift;
            $tu_st = '';
            $tu_carga_user = '';
            $tu_carga_admin = '';
            if ($type_shift == 'SOBRETURNO') {
                $tu_st = '1';
            }
            if ($type_shift == 'ADMIN') {
                $tu_carga_admin = '1';
            }
            if ($type_shift == 'USER') {
                $tu_carga_user = '1';
            }
            if ($type_shift == 'ALL' || $type_shift == 'SOBRETURNO' || $type_shift == 'ADMIN' || $type_shift == 'USER') {
                $type_shift = '';
            }
            if ($lender == 'ALL') {
                $lender = '';
            }
            if ($branch == 'ALL') {
                $branch = '';
            }


            $get_business = Cache::rememberForever('business_' . $business, function() use($business) {
                        return DB::table('tu_emps')->where('em_id', $business)->first();
                    });

            $country_id = $get_business->em_pais;
            $get_country = Cache::rememberForever('country_' . $country_id, function ()use($country_id) {
                        return Country::where('pa_id', $country_id)->first();
                    });


            date_default_timezone_set($get_country->time_zone);
            if ($type_shift == '0') {
                $shift = DB::table('tu_turnos')
                        ->where('emp_id', $business)
                        ->where('tu_fec', date("Y-m-d", strtotime($date)))
                        ->when(!empty($lender), function ($query) use($lender) {
                            return $query->where('pres_id', $lender);
                        })
                        ->when(!empty($branch), function ($query) use($branch) {
                            return $query->where('suc_id', $branch);
                        })
                        ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                            return $query->where('tu_carga', '!=', 0);
                        })
                        ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                            return $query->where('tu_carga', 0);
                        })
                        ->when(!empty($tu_st), function ($query) use($tu_st) {
                            return $query->where('tu_st', $tu_st);
                        })
                        ->where('tu_estado', $status)
                        ->where('tu_asist', 0)
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            } else {
                $shift = DB::table('tu_turnos')
                        ->where('emp_id', $business)
                        ->where('tu_fec', date("Y-m-d", strtotime($date)))
                        ->where('tu_estado', $status)
                        ->when(!empty($lender), function ($query) use($lender) {
                            return $query->where('pres_id', $lender);
                        })
                        ->when(!empty($branch), function ($query) use($branch) {
                            return $query->where('suc_id', $branch);
                        })
                        ->when(!empty($tu_st), function ($query) use($tu_st) {
                            return $query->where('tu_st', $tu_st);
                        })
                        ->when(!empty($type_shift), function ($query) use($type_shift) {
                            return $query->where('tu_asist', $type_shift);
                        })
                        ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                            return $query->where('tu_carga', '!=', 0);
                        })
                        ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                            return $query->where('tu_carga', 0);
                        })
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            }
            foreach ($shift as $rs) {
                $services = '';

                $canceled = '';
                $motivo_canceled = '';
                if ($status == "BAJA") {
                    $motivo = DB::table('tu_tucan')->where('tucan_turid', $rs->tu_id)->first();
                    if (isset($motivo) != 0):
                        $motivo_canceled = $motivo->tucan_mot;
                        $person_id=$motivo->tucan_usid;
                        $person = Cache::rememberForever('person_' . $person_id, function() use($person_id) {
                            return DB::table('tu_users')->where('us_id', $person_id)->first();
                        });
                        if (isset($person) != 0):
                            $canceled = mb_substr($person->us_nom, 0, 12);
                        endif;
                    endif;
                }
                if ($rs->tu_servid != null) {
                    if (substr_count($rs->tu_servid, '-') <= 0) {
                        $service_id = $rs->tu_servid;
                        $get_service = Cache::rememberForever('service_' . $service_id, function ()use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= $get_service->serv_nom;
                        }
                    } else {
                        for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                            $service = explode('-', $rs->tu_servid);
                            $service_id = $service[$i];
                            $get_service = Cache::rememberForever('service_' . $service_id, function ()use($service_id) {
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
                $user_id = $rs->us_id;
                $lender = Cache::rememberForever('lender_' . $lender_id, function ()use($lender_id) {
                            return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                        });
                $user = Cache::rememberForever('user_' . $user_id, function ()use($user_id, $business) {
                            return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business)->first();
                        });
                if (isset($lender) != 0 && isset($user) != 0) {
                    $time = (date("H", strtotime($rs->tu_hora)) <= 11) ? 'AM' : 'PM';
                    $asist = $rs->tu_asist;
                    if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist=="3") {
                        $asist = 4;
                    }
                    if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist=="3" && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                        $asist = 4;
                    }

                    $name_alt='';

                    if($business=="1551" || $business=="1687" || $business=="2128" || $business=="2112" || $business=="1728" || $business=="1706" || $business=="1688" || $business=="1689"  || $business=="1617" || $business=="1544"  || $business=="1545" || $business=="1546" || $business=="1549" || $business=="1550" || $business=="1345" ){

                        $data_cs = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                        if(isset($data_cs)!=0){
                            $name_alt="- ".$data_cs->usm_gen1;
                        }

                    }
                    if ($type_shift != '3') {
                        $json[] = array(
                            "id" => $rs->tu_id,
                            "tu_st" => $rs->tu_st,
                            "id_business" => $business,
                            "us_id" => $user->id,
                            "id_usuario" => $rs->us_id,
                            "name" => mb_strtoupper($user->name),
                            "tu_asist" => $asist,
                            "email" => strtolower($user->email),
                            "time" => $time,
                            "timestamp" => strtotime($rs->tu_hora),
                            "hour" => date("H:i", strtotime($rs->tu_hora)),
                            "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))).' del '.date("Y", strtotime($rs->tu_fec)),
                            "created_at" => $rs->tu_fec,
                            "code" => $rs->tu_code,
                            "services" =>  mb_substr(trim($services), 0, 50),
                            "lender_1" => $rs->pres_id,
                            "tu_carga" => $rs->tu_carga,
                            "canceled"=>$canceled,
                            'lender' => mb_strtoupper($lender->tmsp_pnom).' '.$name_alt,
                        );
                    } else {
                        if ($asist != 4) {
                            $json[] = array(
                                "id" => $rs->tu_id,
                                "tu_st" => $rs->tu_st,
                                "id_business" => $business,
                                "id_usuario" => $rs->us_id,
                                "us_id" => $user->id,
                                "name" => mb_strtoupper($user->name),
                                "tu_asist" => $asist,
                                "email" => strtolower($user->email),
                                "time" => $time,
                                "timestamp" => strtotime($rs->tu_hora),
                                "hour" => date("H:i", strtotime($rs->tu_hora)),
                                "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))).' del '.date("Y", strtotime($rs->tu_fec)),
                                "created_at" => $rs->tu_fec,
                                "code" => $rs->tu_code,
                                "services" =>  mb_substr(trim($services), 0, 50),
                                "lender_1" => $rs->pres_id,
                                "tu_carga" => $rs->tu_carga,
                                "canceled"=>$canceled,
                                'lender' => mb_strtoupper($lender->tmsp_pnom).' '.$name_alt,
                            );
                        }
                    }
                }
            }
            return response()->json(['lists' => $json, 'name_date' => $this->nameDay(date("w", strtotime($date))) . ' ' . date("d", strtotime($date)) . ' de ' . $this->NameMonth(date("m", strtotime($date))) . ' del ' . date("Y", strtotime($date))]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }



    /**
     * Display list user resources
     * @return type
     */
    public function lists_user($user, $date, $status, $type_shift, $lender, $branch, $business) {
        try {
            $json = array();
            $type_shift = $type_shift;
            $tu_st = '';
            $tu_carga_user = '';
            $tu_carga_admin = '';
            if ($type_shift == 'SOBRETURNO') {
                $tu_st = '1';
            }
            if ($type_shift == 'ADMIN') {
                $tu_carga_admin = '1';
            }
            if ($type_shift == 'USER') {
                $tu_carga_user = '1';
            }
            if ($type_shift == 'ALL' || $type_shift == 'SOBRETURNO' || $type_shift == 'ADMIN' || $type_shift == 'USER') {
                $type_shift = '';
            }
            if ($lender == 'ALL') {
                $lender = '';
            }
            if ($branch == 'ALL') {
                $branch = '';
            }
            if ($date == '0') {
                $date = '';
            }
            if ($date != '') {
                $date = explode('-', $date);
                $date = $date[2] . "-" . $date[1] . "-" . $date[0];
            }
            if ($type_shift == '0') {
                $shift = Shift::
                        where('emp_id', $business)
                        ->where('us_id', $user)
                         ->where('tu_estado','!=','BLOQUEO')
                        ->when(!empty($lender), function ($query) use($lender) {
                            return $query->where('pres_id', $lender);
                        })
                        ->when(!empty($branch), function ($query) use($branch) {
                            return $query->where('suc_id', $branch);
                        })
                        ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                            return $query->where('tu_carga', '!=', 0);
                        })
                        ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                            return $query->where('tu_carga', 0);
                        })
                        ->when(!empty($tu_st), function ($query) use($tu_st) {
                            return $query->where('tu_st', $tu_st);
                        })
                        ->when(!empty($date), function ($query) use($date) {
                            return $query->where('tu_fec', date("Y-m-d", strtotime($date)));
                        })
                        ->where('tu_asist', 0)
                        ->orderBy('tu_fec', 'ASC')
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            } else {
                $shift = Shift::
                        where('emp_id', $business)
                        ->where('us_id', $user)
                  ->where('tu_estado','!=','BLOQUEO')
                        ->when(!empty($lender), function ($query) use($lender) {
                            return $query->where('pres_id', $lender);
                        })
                        ->when(!empty($branch), function ($query) use($branch) {
                            return $query->where('suc_id', $branch);
                        })
                        ->when(!empty($tu_st), function ($query) use($tu_st) {
                            return $query->where('tu_st', $tu_st);
                        })
                        ->when(!empty($type_shift), function ($query) use($type_shift) {
                            return $query->where('tu_asist', $type_shift);
                        })
                        ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                            return $query->where('tu_carga', '!=', 0);
                        })
                        ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                            return $query->where('tu_carga', 0);
                        })
                        ->when(!empty($date), function ($query) use($date) {
                            return $query->where('tu_fec', date("Y-m-d", strtotime($date)));
                        })
                        ->orderBy('tu_fec', 'ASC')
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            }
            foreach ($shift as $rs) {
                $services = '';

                $canceled = '';
                $motivo_canceled = '';
                if ($status == "BAJA") {
                    $motivo = DB::table('tu_tucan')->where('tucan_turid', $rs->tu_id)->first();
                    if (isset($motivo) != 0):
                        $motivo_canceled = $motivo->tucan_mot;
                        $person_id=$motivo->tucan_usid;
                        $person = Cache::rememberForever('person_' . $person_id, function() use($person_id) {
                            return DB::table('tu_users')->where('us_id', $person_id)->first();
                        });
                        if (isset($person) != 0):
                            $canceled = $person->us_nom;
                        endif;
                    endif;
                }
                if ($rs->tu_servid != null) {
                    if (substr_count($rs->tu_servid, '-') <= 0) {
                        $service_id = $rs->tu_servid;
                        $get_service = Cache::rememberForever('service_' . $service_id, function ()use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= $get_service->serv_nom;
                        }
                    } else {
                        for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                            $service = explode('-', $rs->tu_servid);
                            $service_id = $service[$i];
                            $get_service = Cache::rememberForever('service_' . $service_id, function ()use($service_id) {
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
                $user_id = $rs->us_id;
                $lender = Cache::rememberForever('lender_' . $lender_id, function ()use($lender_id) {
                            return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                        });
                $user = Cache::rememberForever('user_' . $user_id, function ()use($user_id, $business) {
                            return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business)->first();
                        });
                if (isset($lender) != 0 && isset($user) != 0) {
                    $time = (date("H", strtotime($rs->tu_hora)) <= 11) ? 'AM' : 'PM';
                    $asist = $rs->tu_asist;
                     if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist=="3") {
                        $asist = 4;
                    }
                    if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist=="3" &&  date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                        $asist = 4;
                    }

                    $name_alt='';

                    if($business=="1551" || $business=="1687" || $business=="2128" || $business=="2112" || $business=="1728" || $business=="1706" || $business=="1688" || $business=="1689"  || $business=="1617" || $business=="1544"  || $business=="1545" || $business=="1546" || $business=="1549" || $business=="1550" || $business=="1345" ){

                        $data_cs = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                        if(isset($data_cs)!=0){
                            $name_alt="- ".$data_cs->usm_gen1;
                        }

                    }
                    if ($type_shift != '3') {
                        $json[] = array(
                            "id" => $rs->tu_id,
                            "tu_st" => $rs->tu_st,
                            "id_business" => $business,
                            "id_usuario" => $rs->us_id,
                            "us_id" => $user->id,
                            "name" => mb_strtoupper($user->name),
                            "tu_asist" => $asist,
                            "email" => strtolower($user->email),
                            "time" => $time,
                            "timestamp" => strtotime($rs->tu_fec.' '.$rs->tu_hora),
                            "hour" => date("H:i", strtotime($rs->tu_hora)),
                            "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))).' del '.date("Y", strtotime($rs->tu_fec)),
                            "created_at" => $rs->tu_fec,
                            "code" => $rs->tu_code,
                            "services" =>  mb_substr(trim($services), 0, 50),
                            "lender_1" => $rs->pres_id,
                            "tu_carga" => $rs->tu_carga,
                             "canceled"=>$canceled,
                             "status"=>$rs->tu_estado,
                            'lender' => mb_strtoupper($lender->tmsp_pnom).' '.$name_alt,
                        );
                    } else {
                        if ($asist != 4) {
                            $json[] = array(
                                "id" => $rs->tu_id,
                                "tu_st" => $rs->tu_st,
                                "id_business" => $business,
                                "id_usuario" => $rs->us_id,
                                "us_id" => $user->id,
                                "name" => mb_strtoupper($user->name),
                                "tu_asist" => $asist,
                                "email" => strtolower($user->email),
                                "time" => $time,
                                "timestamp" => strtotime($rs->tu_fec.' '.$rs->tu_hora),
                                "hour" => date("H:i", strtotime($rs->tu_hora)),
                                "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))).' del '.date("Y", strtotime($rs->tu_fec)),
                                "created_at" => $rs->tu_fec,
                                "code" => $rs->tu_code,
                                "services" =>  mb_substr(trim($services), 0, 50),
                                "lender_1" => $rs->pres_id,
                                "tu_carga" => $rs->tu_carga,
                                "canceled"=>$canceled,
                                "status"=>$rs->tu_estado,
                                'lender' => mb_strtoupper($lender->tmsp_pnom).' '.$name_alt,
                            );
                        }
                    }
                }
            }

            return response()->json(['lists' => $json]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list user resources
     * @return type
     */
    public function lists_register($date, $status, $type_shift, $lender, $branch, $business) {
        try {
            $json = array();
            $type_shift = $type_shift;
            $tu_st = '';
            $tu_carga_user = '';
            $tu_carga_admin = '';
            if ($type_shift == 'SOBRETURNO') {
                $tu_st = '1';
            }
            if ($type_shift == 'ADMIN') {
                $tu_carga_admin = '1';
            }
            if ($type_shift == 'USER') {
                $tu_carga_user = '1';
            }
            if ($type_shift == 'ALL' || $type_shift == 'SOBRETURNO' || $type_shift == 'ADMIN' || $type_shift == 'USER') {
                $type_shift = '';
            }
            if ($lender == 'ALL') {
                $lender = '';
            }
            if ($branch == 'ALL') {
                $branch = '';
            }
            if ($date == '0') {
                $date = '';
            }
            if ($date != '') {
                $date = explode('-', $date);
                $date = $date[2] . "-" . $date[1] . "-" . $date[0];
            }
            if ($type_shift == '0') {
                $shift = Shift::
                        where('emp_id', $business)
                        ->when(!empty($lender), function ($query) use($lender) {
                            return $query->where('pres_id', $lender);
                        })
                        ->when(!empty($branch), function ($query) use($branch) {
                            return $query->where('suc_id', $branch);
                        })
                        ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                            return $query->where('tu_carga', '!=', 0);
                        })
                        ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                            return $query->where('tu_carga', 0);
                        })
                        ->when(!empty($tu_st), function ($query) use($tu_st) {
                            return $query->where('tu_st', $tu_st);
                        })
                        ->when(!empty($date), function ($query) use($date) {
                            return $query->where('tu_bloqfec', date("Y-m-d", strtotime($date)));
                        })
                        ->where('tu_asist', 0)
                        ->orderBy('tu_fec', 'ASC')
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            } else {
                $shift = Shift::
                        where('emp_id', $business)
                 
                        ->when(!empty($lender), function ($query) use($lender) {
                            return $query->where('pres_id', $lender);
                        })
                        ->when(!empty($branch), function ($query) use($branch) {
                            return $query->where('suc_id', $branch);
                        })
                        ->when(!empty($tu_st), function ($query) use($tu_st) {
                            return $query->where('tu_st', $tu_st);
                        })
                        ->when(!empty($type_shift), function ($query) use($type_shift) {
                            return $query->where('tu_asist', $type_shift);
                        })
                        ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                            return $query->where('tu_carga', '!=', 0);
                        })
                        ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                            return $query->where('tu_carga', 0);
                        })
                        ->when(!empty($date), function ($query) use($date) {
                            return $query->where('tu_bloqfec', date("Y-m-d", strtotime($date)));
                        })
                        ->orderBy('tu_fec', 'ASC')
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            }
            foreach ($shift as $rs) {
                $services = '';

                $canceled = '';
                $motivo_canceled = '';
                if ($status == "BAJA") {
                    $motivo = DB::table('tu_tucan')->where('tucan_turid', $rs->tu_id)->first();
                    if (isset($motivo) != 0):
                        $motivo_canceled = $motivo->tucan_mot;
                        $person_id=$motivo->tucan_usid;
                        $person = Cache::rememberForever('person_' . $person_id, function() use($person_id) {
                            return DB::table('tu_users')->where('us_id', $person_id)->first();
                        });
                        if (isset($person) != 0):
                            $canceled = $person->us_nom;
                        endif;
                    endif;
                }
                if ($rs->tu_servid != null) {
                    if (substr_count($rs->tu_servid, '-') <= 0) {
                        $service_id = $rs->tu_servid;
                        $get_service = Cache::rememberForever('service_' . $service_id, function ()use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= $get_service->serv_nom;
                        }
                    } else {
                        for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                            $service = explode('-', $rs->tu_servid);
                            $service_id = $service[$i];
                            $get_service = Cache::rememberForever('service_' . $service_id, function ()use($service_id) {
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
                $user_id = $rs->us_id;
                $lender = Cache::rememberForever('lender_' . $lender_id, function ()use($lender_id) {
                            return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                        });
                $user = Cache::rememberForever('user_' . $user_id, function ()use($user_id, $business) {
                            return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business)->first();
                        });
                if (isset($lender) != 0 && isset($user) != 0) {
                    $time = (date("H", strtotime($rs->tu_hora)) <= 11) ? 'AM' : 'PM';
                    $asist = $rs->tu_asist;
                     if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist=="3") {
                        $asist = 4;
                    }
                    if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist=="3" &&  date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                        $asist = 4;
                    }

                    $name_alt='';

                    if($business=="1551" || $business=="1687" || $business=="2128" || $business=="2112" || $business=="1728" || $business=="1706" || $business=="1688" || $business=="1689"  || $business=="1617" || $business=="1544"  || $business=="1545" || $business=="1546" || $business=="1549" || $business=="1550" || $business=="1345" ){

                        $data_cs = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                        if(isset($data_cs)!=0){
                            $name_alt="- ".$data_cs->usm_gen1;
                        }

                    }
                    if ($type_shift != '3') {
                        $json[] = array(
                            "id" => $rs->tu_id,
                            "tu_st" => $rs->tu_st,
                            "id_business" => $business,
                            "id_usuario" => $rs->us_id,
                            "us_id" => $user->id,
                            "name" => mb_strtoupper($user->name),
                            "tu_asist" => $asist,
                            "email" => strtolower($user->email),
                            "time" => $time,
                            "timestamp" => strtotime($rs->tu_fec.' '.$rs->tu_hora),
                            "hour" => date("H:i", strtotime($rs->tu_hora)),
                            "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))).' del '.date("Y", strtotime($rs->tu_fec)),
                            "created_at" => $rs->tu_fec,
                            "code" => $rs->tu_code,
                            "services" =>  mb_substr(trim($services), 0, 50),
                            "lender_1" => $rs->pres_id,
                            "tu_carga" => $rs->tu_carga,
                             "canceled"=>$canceled,
                             "status"=>$rs->tu_estado,
                            'lender' => mb_strtoupper($lender->tmsp_pnom).' '.$name_alt,
                        );
                    } else {
                        if ($asist != 4) {
                            $json[] = array(
                                "id" => $rs->tu_id,
                                "tu_st" => $rs->tu_st,
                                "id_business" => $business,
                                "id_usuario" => $rs->us_id,
                                "us_id" => $user->id,
                                "name" => mb_strtoupper($user->name),
                                "tu_asist" => $asist,
                                "email" => strtolower($user->email),
                                "time" => $time,
                                "timestamp" => strtotime($rs->tu_fec.' '.$rs->tu_hora),
                                "hour" => date("H:i", strtotime($rs->tu_hora)),
                                "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))).' del '.date("Y", strtotime($rs->tu_fec)),
                                "created_at" => $rs->tu_fec,
                                "code" => $rs->tu_code,
                                "services" =>  mb_substr(trim($services), 0, 50),
                                "lender_1" => $rs->pres_id,
                                "tu_carga" => $rs->tu_carga,
                                "canceled"=>$canceled,
                                "status"=>$rs->tu_estado,
                                'lender' => mb_strtoupper($lender->tmsp_pnom).' '.$name_alt,
                            );
                        }
                    }
                }
            }

            return response()->json(['lists' => $json,'name_date' =>' Turnos Registrados: '. $this->nameDay(date("w", strtotime($date))) . ' ' . date("d", strtotime($date)) . ' de ' . $this->NameMonth(date("m", strtotime($date))) . ' del ' . date("Y", strtotime($date))]);
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

    /**
     * Get Type Device
     * @return string
     */
    public function getTypedevice() {
        try {
            $tablet_browser = 0;
            $mobile_browser = 0;
            $body_class = 'desktop';
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $tablet_browser++;
                $body_class = "tablet";
            }
            if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $mobile_browser++;
                $body_class = "mobile";
            }
            if (isset($_SERVER['HTTP_ACCEPT'])) {
                if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ( (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
                    $mobile_browser++;
                    $body_class = "mobile";
                }
            }
            $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
            $mobile_agents = array(
                'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
                'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
                'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
                'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
                'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
                'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
                'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
                'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
                'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-');
            if (in_array($mobile_ua, $mobile_agents)) {
                $mobile_browser++;
            }
            if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
                $mobile_browser++;
                $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
                if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                    $tablet_browser++;
                }
            }
            if ($tablet_browser > 0) {
                return '3';
            } else if ($mobile_browser > 0) {
                return '2';
            } else {
                return '1';
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function getDay($day) {
        try {
            if ($day == '0') {
                return 'Domingo';
            }
            if ($day == '1') {
                return 'Lunes';
            }
            if ($day == '2') {
                return 'Martes';
            }
            if ($day == '3') {
                return 'Miércoles';
            }
            if ($day == '4') {
                return 'Jueves';
            }
            if ($day == '5') {
                return 'Viernes';
            }
            if ($day == '6') {
                return 'Sábado';
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function getCodeLocation($ip) {
        try {
            $code = 'Buenos Aires F.D.';
            return $code;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function getNameLocation($ip) {
        try {
            $name = 'Buenos Aires';
            return $name;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function visits($page) {
        try {
            $visit = Visits::where('page', $page)->where('ip', $this->getIp())->where('date', date("Y-m-d"))->get();
            if (count($visit) == 0) {
                Visits::create([
                    'page' => $page,
                    'ip' => $this->getIp(),
                    'device' => $this->getTypedevice(),
                    'code' => $this->getCodeLocation($this->getIp()),
                    'name' => $this->getNameLocation($this->getIp()),
                    'date' => date("Y-m-d"),
                    'year' => date("Y"),
                    'month' => date("m"),
                    'hour' => date("H") . ':00:00',
                    'day' => date("w"),
                    'name_day' => $this->getDay(date("w"))
                ]);
            }
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
     * Set Format phone
     * @param type $phone
     * @return boolean|string
     */
    public function format_phone($phone) {
        try {
            if (!isset($phone{3})) {
                return '';
            }
            $phone = preg_replace("/[^0-9]/", "", $phone);
            $length = strlen($phone);
            switch ($length) {
                case 7:
                    return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                    break;
                case 8:
                    return preg_replace("/([0-9]{4})([0-9]{4})/", "$1-$2", $phone);
                    break;
                case 9:
                    return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1 $2-$3", $phone);
                    break;
                case 10:
                    return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1 $2-$3", $phone);
                    break;
                case 11:
                    return preg_replace("/([0-9]{1})([0-9]{2})([0-9]{4})([0-9]{4})/", "$1 $2 $3-$4", $phone);
                    break;
                default:
                    return $phone;
                    break;
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

}

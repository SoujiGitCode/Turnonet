<?php

namespace App\Http\Controllers;

use App\Mail\TuMailBusiness;
use Illuminate\Http\Request;
use Route;
use verifyEmail;
use App\Activities;
use App\Visits;
use App\Business;
use App\Branch;
use App\UsersApp;
use App\Directory;
use App\BusinessFields;
use App\ClientsCustomization;
use App\BlockedShedules;
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

class DiaryController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('user');
    }

    /**
     * Display dairy
     * @return type
     */
    public function index(Request $request) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Agenda";
                $this->visits('Agenda');
                return view('frontend.business', ['subtitle' => $subtitle]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display business
     * @return type
     */
    public function business_registers(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/agenda');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/agenda');
                $subtitle = " - Agenda";
                $this->visits('Agenda');
                $lenders = Lenders::where('emp_id', $id)->where('tmsp_estado', 'ALTA')->get();
                $branch = Branch::where('suc_empid', $id)->where('suc_estado', 'ALTA')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.diary_registers', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'lenders' => $lenders, 'branch' => $branch, 'act_diary' => '1', 'menu_agenda' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display business
     * @return type
     */
    public function business(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/agenda');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/agenda');
                $subtitle = " - Agenda";
                $this->visits('Agenda');
                $lenders = Lenders::where('emp_id', $id)->where('tmsp_estado', 'ALTA')->get();
                $branch = Branch::where('suc_empid', $id)->where('suc_estado', 'ALTA')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.diary', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'lenders' => $lenders, 'branch' => $branch, 'act_diary' => '1', 'menu_agenda' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display business
     * @return type
     */
    public function business_user(Request $request, $id, $user) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/agenda');
                $get_user = Directory::where('us_id', $user)->where('emp_id', $id)->first();
                if (isset($get_user) == 0)
                    return redirect::to('/agenda');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/agenda');
                $subtitle = " - Agenda";
                $this->visits('Agenda');
                $lenders = Lenders::where('emp_id', $id)->where('tmsp_estado', 'ALTA')->get();
                $branch = Branch::where('suc_empid', $id)->where('suc_estado', 'ALTA')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.diary_user', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'lenders' => $lenders, 'branch' => $branch, 'act_diary' => '1', 'menu_agenda' => '1', 'get_user' => $get_user]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display cancels
     * @return type
     */
    public function cancels(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/agenda');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/agenda');
                $subtitle = " - Agenda - Turnos Cancelados";
                $this->visits('Agenda - Turnos Cancelados');
                $lenders = Lenders::where('emp_id', $id)->where('tmsp_estado', 'ALTA')->get();
                $branch = Branch::where('suc_empid', $id)->where('suc_estado', 'ALTA')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.diary_cancel', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'lenders' => $lenders, 'branch' => $branch, 'act_diary' => '1', 'menu_agenda' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display lenders
     * @return type
     */
    public function lenders(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/agenda');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/agenda');
                $lender = $this->lists_lenders($id)->getData();
                if (count($lender) > 1) {
                    $subtitle = " - Agenda - Prestadores";
                    $this->visits('Agenda - Prestadores');
                    $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                    return view('frontend.lenders', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_diary' => '1', 'menu_agenda' => '1']);
                }
                if (count($lender) == 1) {
                    return Redirect::to('agenda/disponibilidad/' . $lender[0]->id);
                }
                return Redirect::to('agenda/empresa/' . $id);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display reasing
     * @return type
     */
    public function cancelar(Request $request, $code) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Agenda - Cancelar Turno";
                $this->visits('Agenda - Cancelar Turno');
                $shift = Shift::where('tu_code', $code)->first();
                if (isset($shift) == 0)
                    return redirect::to('/agenda');
                $lender = Lenders::find($shift->pres_id);
                $get_business = Business::find($shift->emp_id);
                $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                $services = Services::where('serv_presid', $shift->pres_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.cancel', ['subtitle' => $subtitle, 'lender' => $lender, 'user' => $user, 'services' => $services, 'get_business' => $get_business, 'shift' => $shift, 'act_diary' => '1', 'menu_agenda' => '1', 'business' => $business]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display reasing
     * @return type
     */
    public function reasing(Request $request, $code) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Agenda - Reasignar Turno";
                $this->visits('Agenda - Reasignar Turno');
                $shift = Shift::where('tu_code', $code)->first();
                if (isset($shift) == 0)
                    return redirect::to('/agenda');
                $lender = Lenders::find($shift->pres_id);
                $get_business = Business::find($shift->emp_id);
                $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                $services = Services::where('serv_presid', $shift->pres_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.reasing', ['subtitle' => $subtitle, 'lender' => $lender, 'user' => $user, 'services' => $services, 'business' => $business, 'get_business' => $get_business, 'shift' => $shift, 'act_diary' => '1', 'menu_agenda' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display news
     * @return type
     */
    public function news(Request $request, $code) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Agenda - Nuevo Turno";
                $this->visits('Agenda - Nuevo Turno');
                $shift = Shift::where('tu_code', $code)->first();
                if (isset($shift) == 0)
                    return redirect::to('/agenda');
                $lender = Lenders::find($shift->pres_id);
                $get_business = Business::find($shift->emp_id);
                $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                $services = Services::where('serv_presid', $shift->pres_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.new_shift', ['subtitle' => $subtitle, 'lender' => $lender, 'user' => $user, 'services' => $services, 'business' => $business, 'get_business' => $get_business, 'shift' => $shift, 'act_diary' => '1', 'menu_agenda' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display view_shitf
     * @return type
     */
    public function view_shitf(Request $request, $code) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Agenda - Detalle del Turno";
                $this->visits('Agenda - Detalle del Turno');
                $shift = Shift::where('tu_code', $code)->first();
                if (isset($shift) == 0)
                    return redirect::to('/agenda');
                $lender = Lenders::find($shift->pres_id);
                $get_business = Business::find($shift->emp_id);
                $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                $services = Services::where('serv_presid', $shift->pres_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
                $phone = ($user->phone == '') ? '' : $this->format_phone($user->phone);
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.view_shitf', ['subtitle' => $subtitle, 'lender' => $lender, 'user' => $user, 'phone' => $phone, 'services' => $services, 'business' => $business, 'get_business' => $get_business, 'shift' => $shift, 'act_diary' => '1', 'menu_agenda' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display edit_shitf
     * @return type
     */
    public function edit_shitf(Request $request, $code) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Agenda - Detalle del Turno";
                $this->visits('Agenda - Detalle del Turno');
                $shift = Shift::where('tu_code', $code)->first();
                if (isset($shift) == 0)
                    return redirect::to('/agenda');
                $lender = Lenders::find($shift->pres_id);
                $get_business = Business::find($shift->emp_id);
                $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                $services = Services::where('serv_presid', $shift->pres_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
                $phone = ($user->phone == '') ? '' : $this->format_phone($user->phone);
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                if (ClientsCustomization::where('usm_turid', $shift->tu_id)->count() == 0) {
                    ClientsCustomization::insert([
                        'usm_usid' => $shift->us_id,
                        'usm_empid' => $shift->emp_id,
                        'usm_turid' => $shift->tu_id
                    ]);
                }
                $inputs_add = BusinessFields::where('mi_empid', $shift->emp_id)->orderby('mi_field', 'asc')->get();
                $data = ClientsCustomization::where('usm_turid', $shift->tu_id)->first();
                return view('frontend.edit_shift', ['subtitle' => $subtitle, 'lender' => $lender, 'user' => $user, 'phone' => $phone, 'services' => $services, 'business' => $business, 'get_business' => $get_business, 'shift' => $shift, 'act_diary' => '1', 'menu_agenda' => '1', 'inputs_add' => $inputs_add, 'data' => $data]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display create shift
     * @return type
     */
    public function create(Request $request, $id) {
        try {
//reinaldo using validate mail func
            if (!$this->validateMailExistance($request['email'])) {
                return response()->json(["response" => "no-email"]);
            }
//
            if (!Auth::guard('user')->guest()) {
                $lender = Lenders::find($id);
                if (isset($lender) == 0)
                    return redirect::to('/agenda');
                $subtitle = " - Agenda - Nuevo Turno";
                $this->visits('Agenda - Nuevo Turno');
                $get_business = Business::find($lender->emp_id);
                $services = Services::where('serv_presid', $lender->tmsp_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                $inputs_add = BusinessFields::where('mi_empid', $get_business->em_id)->orderby('mi_field', 'asc')->get();
                return view('frontend.shift_create', ['subtitle' => $subtitle, 'lender' => $lender, 'services' => $services, 'get_business' => $get_business, 'act_diary' => '1', 'menu_agenda' => '1', 'business' => $business, 'inputs_add' => $inputs_add]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists($date, $status, $type_shift, $lender, $branch, $business) {
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
                        $person_id = $motivo->tucan_usid;
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
                    $time = (date("H", strtotime($rs->tu_hora)) <= 12) ? 'AM' : 'PM';
                    $asist = $rs->tu_asist;
                    if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist == "3") {
                        $asist = 4;
                    }
                    if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist == "3" && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                        $asist = 4;
                    }
                    $name_alt = '';
                    if ($business == "1551" || $business == "1687" || $business == "2128" || $business == "2112" || $business == "1728" || $business == "1706" || $business == "1688" || $business == "1689" || $business == "1617" || $business == "1544" || $business == "1545" || $business == "1546" || $business == "1549" || $business == "1550" || $business == "1345") {
                        $data_cs = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                        if (isset($data_cs) != 0) {
                            $name_alt = "- " . $data_cs->usm_gen1;
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
                            "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))) . ' del ' . date("Y", strtotime($rs->tu_fec)),
                            "created_at" => $rs->tu_fec,
                            "code" => $rs->tu_code,
                            "services" => mb_substr(trim($services), 0, 50),
                            "lender_1" => $rs->pres_id,
                            "tu_carga" => $rs->tu_carga,
                            "canceled" => $canceled,
                            'lender' => mb_strtoupper($lender->tmsp_pnom) . ' ' . $name_alt,
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
                                "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))) . ' del ' . date("Y", strtotime($rs->tu_fec)),
                                "created_at" => $rs->tu_fec,
                                "code" => $rs->tu_code,
                                "services" => mb_substr(trim($services), 0, 50),
                                "lender_1" => $rs->pres_id,
                                "tu_carga" => $rs->tu_carga,
                                "canceled" => $canceled,
                                'lender' => mb_strtoupper($lender->tmsp_pnom) . ' ' . $name_alt,
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
                        ->where('tu_estado', '!=', 'BLOQUEO')
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
                        ->where('tu_estado', '!=', 'BLOQUEO')
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
                        $person_id = $motivo->tucan_usid;
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
                    $time = (date("H", strtotime($rs->tu_hora)) <= 12) ? 'AM' : 'PM';
                    $asist = $rs->tu_asist;
                    if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist == "3") {
                        $asist = 4;
                    }
                    if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist == "3" && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                        $asist = 4;
                    }
                    $name_alt = '';
                    if ($business == "1551" || $business == "1687" || $business == "2128" || $business == "2112" || $business == "1728" || $business == "1706" || $business == "1688" || $business == "1689" || $business == "1617" || $business == "1544" || $business == "1545" || $business == "1546" || $business == "1549" || $business == "1550" || $business == "1345") {
                        $data_cs = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                        if (isset($data_cs) != 0) {
                            $name_alt = "- " . $data_cs->usm_gen1;
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
                            "timestamp" => strtotime($rs->tu_fec . ' ' . $rs->tu_hora),
                            "hour" => date("H:i", strtotime($rs->tu_hora)),
                            "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))) . ' del ' . date("Y", strtotime($rs->tu_fec)),
                            "created_at" => $rs->tu_fec,
                            "code" => $rs->tu_code,
                            "services" => mb_substr(trim($services), 0, 50),
                            "lender_1" => $rs->pres_id,
                            "tu_carga" => $rs->tu_carga,
                            "canceled" => $canceled,
                            "status" => $rs->tu_estado,
                            'lender' => mb_strtoupper($lender->tmsp_pnom) . ' ' . $name_alt,
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
                                "timestamp" => strtotime($rs->tu_fec . ' ' . $rs->tu_hora),
                                "hour" => date("H:i", strtotime($rs->tu_hora)),
                                "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))) . ' del ' . date("Y", strtotime($rs->tu_fec)),
                                "created_at" => $rs->tu_fec,
                                "code" => $rs->tu_code,
                                "services" => mb_substr(trim($services), 0, 50),
                                "lender_1" => $rs->pres_id,
                                "tu_carga" => $rs->tu_carga,
                                "canceled" => $canceled,
                                "status" => $rs->tu_estado,
                                'lender' => mb_strtoupper($lender->tmsp_pnom) . ' ' . $name_alt,
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
                        $person_id = $motivo->tucan_usid;
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
                    $time = (date("H", strtotime($rs->tu_hora)) <= 12) ? 'AM' : 'PM';
                    $asist = $rs->tu_asist;
                    if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist == "3") {
                        $asist = 4;
                    }
                    if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist == "3" && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                        $asist = 4;
                    }
                    $name_alt = '';
                    if ($business == "1551" || $business == "1687" || $business == "2128" || $business == "2112" || $business == "1728" || $business == "1706" || $business == "1688" || $business == "1689" || $business == "1617" || $business == "1544" || $business == "1545" || $business == "1546" || $business == "1549" || $business == "1550" || $business == "1345") {
                        $data_cs = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                        if (isset($data_cs) != 0) {
                            $name_alt = "- " . $data_cs->usm_gen1;
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
                            "timestamp" => strtotime($rs->tu_fec . ' ' . $rs->tu_hora),
                            "hour" => date("H:i", strtotime($rs->tu_hora)),
                            "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))) . ' del ' . date("Y", strtotime($rs->tu_fec)),
                            "created_at" => $rs->tu_fec,
                            "code" => $rs->tu_code,
                            "services" => mb_substr(trim($services), 0, 50),
                            "lender_1" => $rs->pres_id,
                            "tu_carga" => $rs->tu_carga,
                            "canceled" => $canceled,
                            "status" => $rs->tu_estado,
                            'lender' => mb_strtoupper($lender->tmsp_pnom) . ' ' . $name_alt,
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
                                "timestamp" => strtotime($rs->tu_fec . ' ' . $rs->tu_hora),
                                "hour" => date("H:i", strtotime($rs->tu_hora)),
                                "date" => $this->nameDay(date("w", strtotime($rs->tu_fec))) . ' ' . date("d", strtotime($rs->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($rs->tu_fec))) . ' del ' . date("Y", strtotime($rs->tu_fec)),
                                "created_at" => $rs->tu_fec,
                                "code" => $rs->tu_code,
                                "services" => mb_substr(trim($services), 0, 50),
                                "lender_1" => $rs->pres_id,
                                "tu_carga" => $rs->tu_carga,
                                "canceled" => $canceled,
                                "status" => $rs->tu_estado,
                                'lender' => mb_strtoupper($lender->tmsp_pnom) . ' ' . $name_alt,
                            );
                        }
                    }
                }
            }
            return response()->json(['lists' => $json, 'name_date' => ' Turnos Registrados: ' . $this->nameDay(date("w", strtotime($date))) . ' ' . date("d", strtotime($date)) . ' de ' . $this->NameMonth(date("m", strtotime($date))) . ' del ' . date("Y", strtotime($date))]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists_lenders($id) {
        try {
            $json = array();
            $lenders = Lenders::where('emp_id', $id)->where('tmsp_estado', 'ALTA')->get();
            foreach ($lenders as $rs) {
                $json[] = array(
                    "id" => $rs->tmsp_id,
                );
            }
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status shift
     * @param Request $request
     * @return boolean
     */
    public function up_status(Request $request) {
        try {
            $bloqueo = 0;
            if (isset($request['bloqueo'])) {
                $bloqueo = 1;
            }
            $aviso = 0;
            if (isset($request['aviso'])) {
                $aviso = 1;
            }
            $shift = Shift::find($request['tu_id']);
            $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
            $services = '';
            $lender = Lenders::find($shift->pres_id);
            $business = Business::find($shift->emp_id);
            $address = DB::table('tu_emps_suc')->where('suc_id', $shift->suc_id)->first();
            $create = $this->nameDay(date("w", strtotime($shift->tu_fec))) . ', ' . date("d", strtotime($shift->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($shift->tu_fec))) . ' del  ' . date("Y", strtotime($shift->tu_fec));
            $time = (date("H", strtotime($shift->tu_hora)) <= 12) ? 'AM' : 'PM';
// get services
            if ($shift->tu_servid != "") {
                if (substr_count($shift->tu_servid, '-') <= 0) {
                    $get_service = Services::find($shift->tu_servid);
                    if (isset($get_service) != 0) {
                        $services .= $get_service->serv_nom;
                    }
                } else {
                    for ($i = 0; $i <= substr_count($shift->tu_servid, '-'); $i++) {
                        $service = explode('-', $shift->tu_servid);
                        $get_service = Services::find($service[$i]);
                        if (isset($get_service) != 0) {
                            $services .= trim($get_service->serv_nom);
                        }
                        if ($i != substr_count($shift->tu_servid, '-')) {
                            $services .= ", ";
                        }
                    }
                }
            }
            if ($bloqueo == 0) {
                BlockedShedules::where('tur_id', $request['tu_id'])->delete();
            }
            DB::table('tu_tucan')->insert([
                'tucan_turid' => $shift->tu_id,
                "tucan_mot" => $request['message'],
                "tucan_fec" => date("Y-m-d"),
                "tucan_hor" => date("H:i:s"),
                "tucan_usid" => Auth::guard('user')->User()->us_id
            ]);
            if (isset($user) != 0) {
                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))) . '
            <br/>
            Te informamos a través de este correo electrónico que el turno que has solicitado para el ' . $create . ' a las ' . date("H:i", strtotime($shift->tu_hora)) . ' ' . $time . ' ha sido cancelado.<br><br>
            <strong style="color:#FF5722">DATOS DEL TURNO:</strong><br/>
            <strong>Código:</strong> ' . $shift->tu_code . '<br/>
            <strong>Empresa:</strong> ' . mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8') . '<br/>
            <strong>Prestador:</strong> ' . mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8') . '<br/>';
                if (isset($address) != 0) {
                    $content .= '<strong>Dirección:</strong> ' . $address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso . '<br/>';
                }
                if ($services != '') {
                    $content .= '<strong>Servicios solicitados:</strong> ' . $services . '<br/>';
                }
                $content .= '<strong>Motivo de cancelación:</strong> ' . $request['message'] . '<br/>';
                $content .= '<br>Para más información comunicate con ' . mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8') . '.';
                $title = 'Turno Cancelado';
                $replyto = mb_convert_encoding($business->em_email, 'UTF-8', 'UTF-8');
                if ($replyto == "" || false == filter_var($replyto, FILTER_VALIDATE_EMAIL)) {
                    $replyto = env('MAIL_FROM_ADDRESS');
                }
                if ($user->email != "" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL) && $aviso == 1) {
                    Mail::to($user->email, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuMailBusiness($replyto, 'email_single', $title, $content));
                }
            }
            $notifications_business = LenderNotifications::
                    where('pc_empid', $business->em_id)
                    ->first();
            if (isset($notifications_business) != 0 && $notifications_business->pc_mailc == '1') {
                Mail::to(Auth::guard('user')->User()->us_mail, mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))
                        ->send(
                                new TuMailBusiness(
                                        $replyto,
                                        'email_single',
                                        $title,
                                        str_replace('Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))), 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))), $content)
                ));
            }
            $this->audit('Dar de baja a turno ID #' . $shift->tu_code);
            $shift = Shift::find($request['tu_id']);
            $shift->fill([
                'tu_estado' => 'BAJA'
            ]);
            $shift->save();
            if ($business->em_smscontrol == 'ALTA') {
                $data = ClientsCustomization::where('usm_turid', $shift->tu_id)->first();
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
                            "tusms_msg" => "El turno para el " . date("d/m/Y", strtotime($shift->tu_fec)) . " a las " . date("H:i", strtotime($shift->tu_hora)) . " hs. ha sido cancelado por " . ucwords(mb_strtolower(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))),
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
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status shift
     * @param Request $request
     * @return boolean
     */
    public function up_status_all(Request $request) {
        try {
            $bloqueo = 0;
            if (isset($request['bloqueo'])) {
                $bloqueo = 1;
            }
            $aviso = 0;
            if (isset($request['aviso'])) {
                $aviso = 1;
            }
            if ($request['all_shift'] != "") {
                for ($i = 1; $i <= substr_count($request['all_shift'], ','); $i++) {
                    $item = explode(',', $request['all_shift']);
                    $shift = Shift::find($item);
                    $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                    $services = '';
                    $lender = Lenders::find($shift->pres_id);
                    $business = Business::find($shift->emp_id);
                    $address = DB::table('tu_emps_suc')->where('suc_id', $shift->suc_id)->first();
                    $create = $this->nameDay(date("w", strtotime($shift->tu_fec))) . ', ' . date("d", strtotime($shift->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($shift->tu_fec))) . ' del  ' . date("Y", strtotime($shift->tu_fec));
                    $time = (date("H", strtotime($shift->tu_hora)) <= 12) ? 'AM' : 'PM';
// get services
                    if ($shift->tu_servid != "") {
                        if (substr_count($shift->tu_servid, '-') <= 0) {
                            $get_service = Services::find($shift->tu_servid);
                            if (isset($get_service) != 0) {
                                $services .= $get_service->serv_nom;
                            }
                        } else {
                            for ($i = 0; $i <= substr_count($shift->tu_servid, '-'); $i++) {
                                $service = explode('-', $shift->tu_servid);
                                $get_service = Services::find($service[$i]);
                                if (isset($get_service) != 0) {
                                    $services .= trim($get_service->serv_nom);
                                }
                                if ($i != substr_count($shift->tu_servid, '-')) {
                                    $services .= ", ";
                                }
                            }
                        }
                    }
                    if ($bloqueo == 0) {
                        BlockedShedules::where('tur_id', $item)->delete();
                    }
                    DB::table('tu_tucan')->insert([
                        'tucan_turid' => $shift->tu_id,
                        "tucan_mot" => $request['message'],
                        "tucan_fec" => date("Y-m-d"),
                        "tucan_hor" => date("H:i:s"),
                        "tucan_usid" => Auth::guard('user')->User()->us_id
                    ]);
                    if (isset($user) != 0) {
                        $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))) . '
                    <br/>
                    Te informamos a través de este correo electrónico que el turno que has solicitado para el ' . $create . ' a las ' . date("H:i", strtotime($shift->tu_hora)) . ' ' . $time . ' ha sido cancelado.<br><br>
                    <strong style="color:#FF5722">DATOS DEL TURNO:</strong><br/>
                    <strong>Código:</strong> ' . $shift->tu_code . '<br/>
                    <strong>Empresa:</strong> ' . mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8') . '<br/>
                    <strong>Prestador:</strong> ' . mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8') . '<br/>';
                        if (isset($address) != 0) {
                            $content .= '<strong>Dirección:</strong> ' . $address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso . '<br/>';
                        }
                        if ($services != '') {
                            $content .= '<strong>Servicios solicitados:</strong> ' . $services . '<br/>';
                        }
                        $content .= '<strong>Motivo de cancelación:</strong> ' . $request['message'] . '<br/>';
                        $content .= '<br>Para más información comunicate con ' . mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8') . '.';
                        $title = 'Turno Cancelado';
                        $replyto = mb_convert_encoding($business->em_email, 'UTF-8', 'UTF-8');
                        if ($replyto == "" || false == filter_var($replyto, FILTER_VALIDATE_EMAIL)) {
                            $replyto = env('MAIL_FROM_ADDRESS');
                        }
                        if ($user->email != "" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL) && $aviso == 1) {
                            Mail::to($user->email, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuMailBusiness($replyto, 'email_single', $title, $content));
                        }
                    }
                    $notifications_business = LenderNotifications::
                            where('pc_empid', $business->em_id)
                            ->first();
                    if (isset($notifications_business) != 0 && $notifications_business->pc_mailc == '1') {
                        Mail::to(Auth::guard('user')->User()->us_mail, mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))
                                ->send(
                                        new TuMailBusiness(
                                                $replyto,
                                                'email_single',
                                                $title,
                                                str_replace('Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))), 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))), $content)
                        ));
                    }
                    $this->audit('Dar de baja a turno ID #' . $shift->tu_code);
                    $shift = Shift::find($request['tu_id']);
                    $shift->fill([
                        'tu_estado' => 'BAJA'
                    ]);
                    $shift->save();
                    if ($business->em_smscontrol == 'ALTA') {
                        $data = ClientsCustomization::where('usm_turid', $shift->tu_id)->first();
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
                                    "tusms_msg" => "El turno para el " . date("d/m/Y", strtotime($shift->tu_fec)) . " a las " . date("H:i", strtotime($shift->tu_hora)) . " hs. ha sido cancelado por " . ucwords(mb_strtolower(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))),
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
            }
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status shift
     * @param Request $request
     * @return boolean
     */
    public function up_asistencia(Request $request) {
        try {
            if ($request['status'] == '1' || $request['status'] == '2') {
                $this->audit('Confirmar la asistencia del  turno ID #' . $request['id']);
            } else {
                $this->audit('Cancelar la asistencia del turno ID #' . $request['id']);
            }
            $shift = Shift::find($request['id']);
            $shift->fill([
                'tu_asist' => $request['status']
            ]);
            $shift->save();
            return response()->json(["msg" => "borrado"]);
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
            return '1';
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
                'id_user' => $this->getIdBusiness()
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

    public function getIdBusiness() {
        if (Auth::guard('user')->User()->level == 1) {
            return Auth::guard('user')->User()->us_id;
        } else {
            $get_business = DB::table('tu_emps')->where('em_id', Auth::guard('user')->User()->emp_id)->first();
            return $get_business->em_uscid;
        }
    }

    public function lista_servicios_prestador($id) {
        $lender = Lenders::find($id);
        $get_business = Business::find($lender->emp_id);
        $services = Services::where('serv_presid', $lender->tmsp_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
        return view('frontend.service_lender', ['lender' => $lender, 'services' => $services, 'get_business' => $get_business]);
    }

//reinaldo email existance validation
    public function validateMailExistance($email) {
        include('ValidateMailExistance.php');
        $vmail = new verifyEmail();
        $vmail->setStreamTimeoutWait(20);
        $vmail->Debug = TRUE;
        $vmail->Debugoutput = 'html';
        $vmail->setEmailFrom('turnos@turnonet.com');
        if ($vmail->check($email)) {
            return true;
        } elseif (verifyEmail::validate($email)) {
            return false;
        } else {
            return false;
        }
    }

//
}

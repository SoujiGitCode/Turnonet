<?php

namespace App\Http\Controllers;

use verifyEmail;
use App\Mail\TuMailBusiness;
use App\Mail\TuShiftMail;
use Illuminate\Http\Request;
use Route;
use Log;
use App\Activities;
use App\Visits;
use App\Business;
use App\Branch;
use App\UsersApp;
use App\BlackLists;
use App\Directory;
use App\BusinessFields;
use App\Notifications;
use App\ClientsCustomization;
use App\LenderNotifications;
use App\SettingsBusiness;
use App\BlockedShedules;
use App\SMS;
use App\Frame;
use App\Lenders;
use App\Country;
use App\Services;
use App\Shift;
use Redirect;
use DB;
use MercadoPago;
use Mail;
use Cache;
use Session;
use Auth;
use URL;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Http\Requests;

class FrameController extends Controller {

    /**
     * Display index
     * @return type
     */
    public function index($id, $code) {
        try {
            $get_business = Business::find($id);
            if (isset($get_business) == 0)
                return view('errors/error_ex');
            $lenders = Lenders::where('emp_id', $get_business->em_id)->where('tmsp_estado', 'ALTA')->get();
            foreach ($lenders as $rs) {
                $lender = Lenders::find($rs->tmsp_id);
                $lender->fill([
                    'tmsp_pnom' => $rs->tmsp_pnom,
                    'url' => $rs->tmsp_id . ' ' . $rs->tmsp_pnom,
                ]);
                $lender->save();
            }
            if (Frame::where('emp_id', $get_business->em_id)->count() == 0) {
                Frame::create([
                    'emp_id' => $get_business->em_id,
                    'font_1' => '1',
                    'font_2' => '2',
                    'font_3' => '3',
                    'title' => "Turnonet - " . $get_business->em_nomfan,
                    'url' => $this->updateUrl(trim($get_business->em_id . " " . $get_business->em_nomfan)),
                    'color_1' => '#FF5722',
                    'color_2' => '#808080',
                    'color_3' => '#3EAF23',
                    'color_4' => '#ffb8b8',
                    'color_5' => '#A9F897',
                    'color_6' => '#009cde',
                    'color_7' => '#f9f9f9',
                    'color_8' => '#E5E2E2',
                    'color_9' => '#ffffff',
                    'header' => '0',
                    'marca' => '0',
                    'footer' => '0',
                    'name' => '0',
                    'searchbar' => '0'
                ]);
            }
            $get_frame = Frame::where('emp_id', $get_business->em_id)->first();
            return Redirect::to('/' . $get_frame->url);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display index
     * @return type
     */
    public function index_e($id) {
        try {
            $get_business = Business::find($id);
            if (isset($get_business) == 0)
                return view('errors/error_ex');
            $lenders = Lenders::where('emp_id', $get_business->em_id)->where('tmsp_estado', 'ALTA')->get();
            foreach ($lenders as $rs) {
                $lender = Lenders::find($rs->tmsp_id);
                $lender->fill([
                    'tmsp_pnom' => $rs->tmsp_pnom,
                    'url' => $rs->tmsp_id . ' ' . $rs->tmsp_pnom,
                ]);
                $lender->save();
            }
            if (Frame::where('emp_id', $get_business->em_id)->count() == 0) {
                Frame::create([
                    'emp_id' => $get_business->em_id,
                    'font_1' => '1',
                    'font_2' => '2',
                    'font_3' => '3',
                    'title' => "Turnonet - " . $get_business->em_nomfan,
                    'url' => $this->updateUrl(trim($get_business->em_id . " " . $get_business->em_nomfan)),
                    'color_1' => '#FF5722',
                    'color_2' => '#808080',
                    'color_3' => '#3EAF23',
                    'color_4' => '#ffb8b8',
                    'color_5' => '#A9F897',
                    'color_6' => '#009cde',
                    'color_7' => '#f9f9f9',
                    'color_8' => '#E5E2E2',
                    'color_9' => '#ffffff',
                    'header' => '0',
                    'marca' => '0',
                    'footer' => '0',
                    'name' => '0',
                    'searchbar' => '0'
                ]);
            }
            $get_frame = Frame::where('emp_id', $get_business->em_id)->first();
            return Redirect::to('/' . $get_frame->url);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display index
     * @return type
     */
    public function view_alias() {
        try {
            $url = explode("/", $_SERVER["REQUEST_URI"]);
            if (isset($url[1])) {
                $get_frame = Frame::where('url', $url[1])->first();
                if (isset($get_frame) != 0) {
                    $id = $get_frame->emp_id;
                    $get_business = Business::find($id);


                    
                    if (isset($get_business) != 0 && $get_business->em_estado == 'BAJA') {
                        
                     return view('frame.inactive', ['get_business' => $get_business]);
                 }

                    $lenders = Lenders::where('emp_id', $get_business->em_id)->where('tmsp_estado', 'ALTA')->get();
                    foreach ($lenders as $rs) {
                        $lender = Lenders::find($rs->tmsp_id);
                        $lender->fill([
                            'tmsp_pnom' => $rs->tmsp_pnom,
                            'url' => $rs->tmsp_id . ' ' . $rs->tmsp_pnom,
                        ]);
                        $lender->save();
                    }
                    if (isset($get_business) != 0)
                        if ($get_business->em_estado == 'BAJA') {
                           return view('errors/404');
                           return view('frame.inactive', ['get_business' => $get_business]);
                       }
                       $lender = $this->lists_lenders($id)->getData();
                       if (count($lender) > 1) {
                        $branch = Branch::where('suc_empid', $id)->where('suc_estado', 'ALTA')->orderby('suc_uscid', 'desc')->get();
                        return view('frame.lenders', ['get_business' => $get_business, 'branch' => $branch]);
                    }
                    if (count($lender) == 0) {
                        $branch = Branch::where('suc_empid', $id)->where('suc_estado', 'ALTA')->orderby('suc_uscid', 'desc')->get();
                        return view('frame.lenders', ['get_business' => $get_business, 'branch' => $branch]);
                    }
                    if (count($lender) == 1) {
                        $lender = $lender[0]->id;
                        $lender = Lenders::find($lender);
                        if (isset($lender) == 0)
                            return redirect::to('/e/esn/' . $business . '/' . $code);
                        $get_business = Business::find($lender->emp_id);
                        if ($get_business->em_estado == 'BAJA') {
                           return view('errors/404');
                           return view('frame.inactive', ['get_business' => $get_business]);
                       }
                       $services = Services::where('serv_presid', $lender->tmsp_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
                       $lenders = Lenders::where('emp_id', $lender->emp_id)->where('tmsp_estado', 'ALTA')->count();
                       $inputs_add = BusinessFields::where('mi_empid', $get_business->em_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
                       if ($lender->tmsp_estado == 'BAJA') {
                        return view('frame.inactive_lender', ['lender' => $lender, 'get_business' => $get_business]);
                    }
                    $back_button = url('/') . '/' . $get_frame->url;
                    return view('frame.calendar', ['lender' => $lender, 'services' => $services, 'get_business' => $get_business, 'lenders' => $lenders, 'inputs_add' => $inputs_add, 'back_button' => $back_button]);
                }
            } else {
                return view('errors/404');
            }
        } else {
            return view('errors/error_ex');
        }
    } catch (Exception $ex) {
        return false;
    }
    return false;
}

    /**
     * Display speciality
     * @return type
     */
    public function speciality($id, $speciality) {
        try {
            $get_business = Business::find($id);
            if (isset($get_business) == 0)
                return view('errors/error_ex');
            $lenders = Lenders::where('emp_id', $get_business->em_id)->where('tmsp_estado', 'ALTA')->get();
            foreach ($lenders as $rs) {
                $lender = Lenders::find($rs->tmsp_id);
                $lender->fill([
                    'tmsp_pnom' => $rs->tmsp_pnom,
                    'url' => $rs->tmsp_id . ' ' . $rs->tmsp_pnom,
                ]);
                $lender->save();
            }
            if (Frame::where('emp_id', $get_business->em_id)->count() == 0) {
                Frame::create([
                    'emp_id' => $get_business->em_id,
                    'font_1' => '1',
                    'font_2' => '2',
                    'font_3' => '3',
                    'title' => "Turnonet - " . $get_business->em_nomfan,
                    'url' => $this->updateUrl(trim($get_business->em_id . " " . $get_business->em_nomfan)),
                    'color_1' => '#FF5722',
                    'color_2' => '#808080',
                    'color_3' => '#3EAF23',
                    'color_4' => '#ffb8b8',
                    'color_5' => '#A9F897',
                    'color_6' => '#009cde',
                    'color_7' => '#f9f9f9',
                    'color_8' => '#E5E2E2',
                    'color_9' => '#ffffff',
                    'header' => '0',
                    'marca' => '0',
                    'footer' => '0',
                    'name' => '0',
                    'searchbar' => '0'
                ]);
            }
            $get_frame = Frame::where('emp_id', $get_business->em_id)->first();
            $get_speciality = Services::find($speciality);
            if (isset($get_speciality) == 0)
                return view('errors/error_ex');
            return Redirect::to($get_frame->url . '/especialidad/' . $get_speciality->url);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display view_speciality
     * @return type
     */
    public function view_speciality($business, $speciality) {
        try {
            $get_frame = Frame::where('url', $business)->first();
            if (isset($get_frame) != 0) {
                $get_business = Business::find($get_frame->emp_id);
                if (isset($get_business) == 0)
                    return view('errors/error_ex');
                $lenders = Lenders::where('emp_id', $get_business->em_id)->where('tmsp_estado', 'ALTA')->get();
                foreach ($lenders as $rs) {
                    $lender = Lenders::find($rs->tmsp_id);
                    $lender->fill([
                        'tmsp_pnom' => $rs->tmsp_pnom,
                        'url' => $rs->tmsp_id . ' ' . $rs->tmsp_pnom,
                    ]);
                    $lender->save();
                }
                $get_speciality = Services::where('url', $speciality)->first();
                if (isset($get_speciality) == 0)
                    return view('errors/error_ex');
                $lender = $this->lists_lenders($get_business->em_id)->getData();
                if ($get_business->em_estado == 'BAJA') {
                   return view('errors/404');
                   return view('frame.inactive', ['get_business' => $get_business]);
               }
               if (count($lender) > 1) {
                $branch = Branch::where('suc_empid', $get_business->em_id)->where('suc_estado', 'ALTA')->orderby('suc_uscid', 'desc')->get();
                return view('frame.lenders_speciality', ['get_business' => $get_business, 'branch' => $branch, 'get_speciality' => $get_speciality]);
            }
            if (count($lender) == 0) {
                $branch = Branch::where('suc_empid', $get_business->em_id)->where('suc_estado', 'ALTA')->orderby('suc_uscid', 'desc')->get();
                return view('frame.lenders_speciality', ['get_business' => $get_business, 'branch' => $branch, 'get_speciality' => $get_speciality]);
            }
            if (count($lender) == 1) {
                $lender = $lender[0]->id;
                $lender = Lenders::find($lender);
                if (isset($lender) == 0)
                    return redirect::to('/e/esn/' . $business . '/' . $code);
                $get_business = Business::find($lender->emp_id);
                if ($get_business->em_estado == 'BAJA') {
                   return view('errors/404');
                   return view('frame.inactive', ['get_business' => $get_business]);
               }
               $services = Services::where('serv_presid', $lender->tmsp_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
               $lenders = Lenders::where('emp_id', $lender->emp_id)->where('tmsp_estado', 'ALTA')->count();
               $inputs_add = BusinessFields::where('mi_empid', $get_business->em_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
               if ($lender->em_estado == 'BAJA') {
                return view('frame.inactive_lender', ['lender' => $lender]);
            }
            $back_button = url('/') . '/' . $get_frame->url;
            return view('frame.calendar', ['lender' => $lender, 'services' => $services, 'get_business' => $get_business, 'lenders' => $lenders, 'inputs_add' => $inputs_add, 'back_button' => $back_button]);
        }
    } else {
        return view('errors/error_ex');
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
    public function calendario($business, $branch, $lender, $code) {
        try {
            $get_business = Business::find($business);
            if (isset($get_business) == 0)
                return view('errors/error_ex');
            $get_frame = Frame::where('emp_id', $get_business->em_id)->first();
            if (isset($get_frame) != 0) {
                $lender = Lenders::find($lender);
                if (isset($lender) != 0) {
                    return Redirect::to('/' . $get_frame->url . '/disponibilidad/' . $lender->url);
                } else {
                    return view('errors/error_ex');
                }
            } else {
                return view('errors/error_ex');
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
    public function calendar($business, $lender) {
        try {
            $get_frame = Frame::where('url', $business)->first();
            if (isset($get_frame) != 0) {
                $lender = Lenders::where('url', $lender)->first();
                if (isset($lender) == 0)
                    return redirect::to($business);
                $get_business = Business::find($lender->emp_id);
                if ($get_business->em_estado == 'BAJA') {
                   return view('errors/404');
                   return view('frame.inactive', ['get_business' => $get_business]);
               }
               $get_branch = Branch::where('suc_id', $lender->suc_id)->first();
               if ($get_branch->suc_estado == 'BAJA') {
                return view('frame.inactive_branch', ['get_business' => $get_business]);
            }
            $services = Services::where('serv_presid', $lender->tmsp_id)->where('serv_tipo', '1')->where('serv_estado', '1')->get();
            $lenders = Lenders::where('emp_id', $lender->emp_id)->where('tmsp_estado', 'ALTA')->count();
            $inputs_add = BusinessFields::where('mi_empid', $get_business->em_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
            if ($lender->tmsp_estado == 'BAJA') {
                return view('frame.inactive_lender', ['lender' => $lender, 'get_business' => $get_business]);
            }
            $back_button = url('e/esn/' . $get_business->em_id . '/' . substr($get_business->em_valcod, 0, 4));
            return view('frame.calendar', ['lender' => $lender, 'services' => $services, 'get_business' => $get_business, 'lenders' => $lenders, 'inputs_add' => $inputs_add, 'back_button' => $back_button]);
        } else {
            return view('errors/error_ex');
        }
    } catch (Exception $ex) {
        return false;
    }
    return false;
}

    /**
     * Display confirm
     * @return type
     */
    public function confirm($business, $lender, $shift) {
        try {
            $get_frame = Frame::where('url', $business)->first();
            if (isset($get_frame) != 0) {
                $lender = Lenders::where('url', $lender)->first();
                if (isset($lender) == 0)
                    return redirect::to($business);
                $get_business = Business::find($lender->emp_id);
                $get_shift = Shift::where('tu_id', $shift)->first();
                if (isset($get_shift) == 0) {
                    return view('frame.expired', ['lender' => $lender, 'get_business' => $get_business]);
                } else {
                    if ($get_shift->tu_estado == 'ALTA') {
                        return view('frame.ocuppied', ['lender' => $lender, 'get_business' => $get_business]);
                    } else {
                        $inputs_add = BusinessFields::where('mi_empid', $get_business->em_id)->orderby('mi_field', 'asc')->get();
                        $data = ClientsCustomization::where('usm_usid', $get_shift->us_id)->where('usm_empid', $get_shift->emp_id)->orderBy('usm_turid', 'desc')->first();
                        return view('frame.confirm', ['lender' => $lender, 'get_business' => $get_business, 'inputs_add' => $inputs_add, 'get_shift' => $get_shift, 'data' => $data]);
                    }
                }
            } else {
                return view('errors/error_ex');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display cancel
     * @return type
     */
    public function cancel($shift, $user, $lender, $branch, $business) {
        try {
            $get_business = Business::find($business);
            $lender = Lenders::find($lender);
            if (Frame::where('emp_id', $get_business->em_id)->count() == 0) {
                Frame::create([
                    'emp_id' => $get_business->em_id,
                    'font_1' => '1',
                    'font_2' => '2',
                    'font_3' => '3',
                    'title' => "Turnonet - " . $get_business->em_nomfan,
                    'url' => $this->updateUrl(trim($get_business->em_id . " " . $get_business->em_nomfan)),
                    'color_1' => '#FF5722',
                    'color_2' => '#808080',
                    'color_3' => '#3EAF23',
                    'color_4' => '#ffb8b8',
                    'color_5' => '#A9F897',
                    'color_6' => '#009cde',
                    'color_7' => '#f9f9f9',
                    'color_8' => '#E5E2E2',
                    'color_9' => '#ffffff',
                    'header' => '0',
                    'marca' => '0',
                    'footer' => '0',
                    'name' => '0',
                    'searchbar' => '0'
                ]);
            }
            $get_frame = Frame::where('emp_id', $business)->first();
            return Redirect($get_frame->url . '/' . $lender->url . '/cancelar/' . $shift);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display cancel
     * @return type
     */
    public function view_cancel($business, $lender, $shift) {
        try {
            $get_frame = Frame::where('url', $business)->first();
            if (isset($get_frame) != 0) {
                $get_business = Business::find($get_frame->emp_id);
                if (isset($get_business) == 0)
                    return view('errors/error_ex');
                $lender = Lenders::where('url', $lender)->first();
                if (isset($lender) == 0)
                    return redirect::to($business);
                $lenders = Lenders::where('emp_id', $lender->emp_id)->where('tmsp_estado', 'ALTA')->count();
                $get_shift = Shift::where('tu_id', $shift)->first();
                if (isset($get_shift) == 0) {
                    return redirect::to($business);
                } else {
//confinguracion de la empresa
                    $gmt = SettingsBusiness::where('emp_id', $get_shift->emp_id)->where('suc_id', '0')->where('pres_id', '0')->first();
//confinguracion de la sucursal
                    $gmt = SettingsBusiness::where('suc_id', $get_shift->suc_id)->where('pres_id', '0')->first();
//confinguracion de la sucursal
                    $gmt = SettingsBusiness::where('pres_id', $get_shift->pres_id)->first();
                    $date_shift = $get_shift->tu_fec . ' ' . $get_shift->tu_hora;
                    $date = date('Y-m-d H:i:s', strtotime($date_shift));
                    if ($get_shift->tu_estado == 'BAJA') {
                        return view('frame.canceled', ['lender' => $lender, 'get_business' => $get_business, 'text' => ' El turno ha sido cancelado.']);
                    } else {
                        if ($get_shift->tu_fec < date("Y-m-d")) {
                            return view('frame.canceled', ['lender' => $lender, 'get_business' => $get_business, 'text' => 'La fecha del turno ya ha expirado.']);
                        }
                        if (isset($gmt) != 0 && $gmt->cf_tcan == '00:30:00') {
                            $newDate = strtotime('-30 minute', strtotime($date));
                        }
                        if (isset($gmt) != 0 && $gmt->cf_tcan == '01:00:00') {
                            $newDate = strtotime('-1 hour', strtotime($date));
                        }
                        if (isset($gmt) != 0 && $gmt->cf_tcan == '02:00:00') {
                            $newDate = strtotime('-2 hour', strtotime($date));
                        }
                        if (isset($gmt) != 0 && $gmt->cf_tcan == '04:00:00') {
                            $newDate = strtotime('-4 hour', strtotime($date));
                        }
                        if (isset($gmt) != 0 && $gmt->cf_tcan == '08:00:00') {
                            $newDate = strtotime('-8 hour', strtotime($date));
                        }
                        if (isset($gmt) != 0 && $gmt->cf_tcan == '24:01:00') {
                            $newDate = strtotime('-24 hour', strtotime($date));
                        }
                        if (isset($gmt) != 0 && $gmt->cf_tcan == '48:01:00') {
                            $newDate = strtotime('-48 hour', strtotime($date));
                        }
                        if (isset($gmt) != 0 && $gmt->cf_tcan == '72:01:00') {
                            $newDate = strtotime('-72 hour', strtotime($date));
                        }
                        if (isset($newDate)) {
                            $newDate = date('Y-m-d H:i:s', $newDate);
                            /* if ($newDate < date("Y-m-d H:i:s")) {
                              return view('frame.canceled', ['lender' => $lender, 'get_business' => $get_business, 'text' => 'El tiempo límite para cancelar el  turno ya ha pasado.']);
                          } */
                          return view('frame.cancel', ['lender' => $lender, 'get_business' => $get_business, 'get_shift' => $get_shift]);
                      } else {
                        return view('frame.cancel', ['lender' => $lender, 'get_business' => $get_business, 'get_shift' => $get_shift]);
                    }
                }
            }
        } else {
            return view('errors/error_ex');
        }
    } catch (Exception $ex) {
        return false;
    }
    return false;
}

    /**
     * Display shift
     * @return type
     */
    public function shift($business, $lender, $shift) {
        try {
            $get_frame = Frame::where('url', $business)->first();
            if (isset($get_frame) != 0) {
                $lender = Lenders::where('url', $lender)->first();
                if (isset($lender) == 0)
                    return redirect::to($business);
                $get_business = Business::find($lender->emp_id);
                $get_shift = Shift::where('tu_id', $shift)->first();
                if (isset($get_shift) == 0) {
                    return view('frame.expired', ['lender' => $lender, 'get_business' => $get_business]);
                } else {
                    if ($get_shift->tu_estado == "BLOQUEO") {
                        return redirect::to($business . '/' . $lender . '/turno/' . $shift);
                    } else {
                        $services = '';
                        if ($get_shift->tu_servid != null) {
                            if (substr_count($get_shift->tu_servid, '-') <= 0) {
                                $service_id = $get_shift->tu_servid;
                                $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return Services::where('serv_id', $service_id)->first();
                                });
                                if (isset($get_service) != 0) {
                                    $services .= $get_service->serv_nom;
                                }
                            } else {
                                for ($i = 0; $i <= substr_count($get_shift->tu_servid, '-'); $i++) {
                                    $service = explode('-', $get_shift->tu_servid);
                                    $service_id = $service[$i];
                                    $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                        return Services::where('serv_id', $service_id)->first();
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
                        $notifications_lender = LenderNotifications::
                        where('pc_presid', $lender->tmsp_id)
                        ->first();
                        $notifications_branch = LenderNotifications::
                        where('pc_sucid', $lender->suc_id)
                        ->first();
                        $notifications_business = LenderNotifications::
                        where('pc_empid', $lender->emp_id)
                        ->first();
                        $address = DB::table('tu_emps_suc')->where('suc_id', $get_shift->suc_id)->first();
                        $sumaHoras = $this->sumarHoras(date("H:i:s", strtotime($get_shift->tu_hora)), date("H:i:s", strtotime($get_shift->tu_durac)));
                        $btn_google = "https://calendar.google.com/calendar/r/eventedit?text=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&dates=" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($get_shift->tu_hora)) . "/" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($sumaHoras)) . "&details=" . urlencode($services) . "&location=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso) . "&sf=true&output=xml";
                        $btn_ical = "https://www.turnonet.com/frame/calendario2/download-ics.php?summary=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&description=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&date_start=" . urlencode($get_shift->tu_fec) . "&date_end=" . urlencode($get_shift->tu_fec) . "&summary=" . urlencode($services) . "&location=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso);
                        $btn_yahoo = "https://calendar.yahoo.com/?v=60&view=d&title=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&st=" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($get_shift->tu_hora)) . "&dur=00000&desc=" . urlencode($services) . "&in_loc=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso);
                        return view('frame.shift', ['get_shift' => $get_shift, 'lender' => $lender, 'get_business' => $get_business, 'notifications_lender' => $notifications_lender, 'notifications_branch' => $notifications_branch, 'notifications_business' => $notifications_business, 'btn_google' => $btn_google, 'btn_ical' => $btn_ical, 'btn_yahoo' => $btn_yahoo]);
                    }
                }
            } else {
                return view('errors/error_ex');
            }
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
    public function cancel_shift(Request $request) {
        try {
            $isHuman = $this->validate_captcha_google($request['captcha']);
            if ($request['captcha'] == '') {
                return response()->json(["response" => "no-catpcha"]);
            }
            if ($isHuman) {
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
                BlockedShedules::where('tur_id', $request['tu_id'])->delete();
                Notifications::create([
                    'description' => 'El turno ' . $shift->tu_code . ' ha sido cancelado',
                    "us_id" => $business->em_uscid,
                    'tipo' => '2',
                    'url' => url('/') . '/agenda/turno/' . $shift->tu_code,
                ]);
                DB::table('tu_tucan')->insert([
                    'tucan_turid' => $shift->tu_id,
                    "tucan_mot" => $request['message'],
                    "tucan_fec" => date("Y-m-d"),
                    "tucan_hor" => date("H:i:s"),
                    "tucan_usid" => $shift->us_id
                ]);
                $shift = Shift::find($request['tu_id']);
                $shift->fill([
                    'tu_estado' => 'BAJA',
                    'id_meeting' => '',
                    'url_zoom' => '',
                    'password_zoom' => ''
                ]);
                $shift->save();
                if ($business->em_smscontrol == 'ALTA') {
                    if ($business->em_tel != "" && $business->em_tel != null) {
                        SMS::create([
                            'tusms_turid' => $shift->tu_id,
                            'tusms_empid' => $shift->emp_id,
                            'tusms_sucid' => $shift->suc_id,
                            'tusms_preid' => $shift->pres_id,
                            'tusms_usuid' => $shift->us_id,
                            'tusms_pacnom' => mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'),
                            'tusms_celenv' => trim($business->em_tel),
                            "tusms_msg" => "El turno para el " . date("d/m/Y", strtotime($shift->tu_fec)) . " a las " . date("H:i", strtotime($shift->tu_hora)) . " hs. ha sido cancelado por " . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))),
                            'tusms_tipo' => '4',
                            'tusms_para' => '2',
                            'tusms_pass' => '1',
                            'tusms_priori' => '1',
                            'tusms_envfec' => date("Y-m-d"),
                            'tusms_envtime' => date("H:i:s")
                        ]);
                    }
                }
                if ($shift->id_meeting != "") {
                    $lender = Lenders::find($get_shift->pres_id);
                    if ($lender->activate_zoom == 1) {
                        $this->delete_meeting($shift->tu_id);
                    }
                }
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
                    if ($user->email != "" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                        Mail::to($user->email, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuMailBusiness($replyto, 'email_single', $title, $content));
                    }
                    $notifications_business = LenderNotifications::
                    where('pc_empid', $business->em_id)
                    ->first();
                    if (isset($notifications_business) != 0 && $notifications_business->pc_mailc == '1') {
                        $content_2 = str_replace(
                            'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))),
                            'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8')))
                            , $content);
                        $email = $business->em_email;
                        $name = mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8');
                        if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            Mail::to($email, mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuMailBusiness($replyto, 'email_single', $title, $content_2));
                        }
                        $content_2 = str_replace(
                            'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))),
                            'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8')))
                            , $content);
                        $email = $lender->tmsp_pmail;
                        $name = mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8');
                        if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            Mail::to($email, mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuMailBusiness($replyto, 'email_single', $title, $content_2));
                        }
                    }
                }
                return response()->json(["msg" => "borrado"]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list bussiness resources
     * @return type
     */
    public function lists_business($id, $speciality, $branch) {
        try {
            $json = array();
            if ($branch == 'ALL') {
                $branch = '';
            }
            if ($speciality != "ALL") {
                $get_speciality = Services::find($speciality);
                $name_speciality = $get_speciality->serv_nom;
            }
            $lenders = Lenders::
            where('emp_id', $id)
            ->when(!empty($branch), function ($query) use($branch) {
                return $query->where('suc_id', $branch);
            })
            ->where('tmsp_estado', 'ALTA')
            ->orderby('position', 'asc')
            ->get();
            foreach ($lenders as $rs) {
                $services = '';
                $i = 0;
                $get_services = Services::where('serv_presid', $rs->tmsp_id)->where('serv_tipo', '2')->where('serv_estado', '1')->offset(0)->limit(2)->get();
                foreach ($get_services as $rs_es) {
                    $i = $i + 1;
                    $services .= trim($rs_es->serv_nom);
                    if ($i != count($get_services)) {
                        $services .= ", ";
                    }
                }
                $email = ($rs->tmsp_pmail == '') ? 'N/A' : strtolower($rs->tmsp_pmail);
                $image = url('/') . '/img/empty.jpg';
                $branch_id = $rs->suc_id;
                $business_id = $rs->emp_id;
                $business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                    return Business::where('em_id', $business_id)->first();
                });
                $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                    return Branch::where('suc_id', $branch_id)->first();
                });
                if ($branch->suc_estado == "ALTA") {
                    if (isset($business) != 0 && $business->em_pfot != "") {
                        $image = "https://www.turnonet.com/fotos/empresas/" . $business->em_pfot;
                    }
                    if (isset($sucursal) != 0 && $sucursal->suc_pfot != "") {
                        $image = "https://www.turnonet.com/fotos/sucursales/" . $sucursal->suc_pfot;
                    }
                    if ($rs->tmsp_pfot != "") {
                        $image = "https://www.turnonet.com/fotos/prestadores/" . $rs->tmsp_pfot;
                    }
                    if ($speciality == 'ALL') {
                        $json[] = array(
                            "empresa" => $id,
                            "image" => $image,
                            "id" => $rs->tmsp_id,
                            "services" => $services,
                            'zoom' => $rs->activate_zoom,
                            "suc_id" => $rs->suc_id,
                            "url" => $rs->url,
                            "mp" => $rs->tmsp_pagoA,
                            "days" => mb_convert_encoding($rs->tmsp_dias, 'UTF-8', 'UTF-8'),
                            "title" => $rs->tmsp_tit,
                            "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                            "email" => strtolower(mb_convert_encoding($email, 'UTF-8', 'UTF-8')),
                        );
                    } else {
                        if (strrpos($services, $name_speciality) !== false) {
                            $json[] = array(
                                "empresa" => $id,
                                "image" => $image,
                                "id" => $rs->tmsp_id,
                                "services" => $services,
                                "suc_id" => $rs->suc_id,
                                'zoom' => $rs->activate_zoom,
                                "url" => $rs->url,
                                "mp" => $rs->tmsp_pagoA,
                                "days" => mb_convert_encoding($rs->tmsp_dias, 'UTF-8', 'UTF-8'),
                                "title" => $rs->tmsp_tit,
                                "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                                "email" => strtolower(mb_convert_encoding($email, 'UTF-8', 'UTF-8')),
                            );
                        }
                    }
                }
            }
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function store_shift(Request $request) {
        try {
            $business = $request['emp_id'];
            $lender = $request['pres_id'];
            $date = $request['tu_fec'];
            if (!$this->validateMailExistance($request['email'])) {
                return response()->json(["response" => "no-email"]);
            }
            if (Cache::has('settings_business_' . $request['emp_id'])) {
                $gmt = Cache::get('settings_business_' . $request['emp_id']);
            } else {
                $gmt = Cache::rememberForever('settings_business_' . $business, function ()use($business) {
                    return DB::table('tu_emps_con')->where('emp_id', $business)->where('suc_id', '0')->where('pres_id', '0')->first();
                });
            }
            if (Cache::has('settings_lender_' . $request['pres_id'])) {
                $gmt = Cache::get('settings_lender_' . $request['pres_id']);
            } else {
                $gmt = Cache::rememberForever('settings_lender_' . $lender, function ()use($lender) {
                    return DB::table('tu_emps_con')->where('pres_id', $lender)->first();
                });
            }
            if (isset($gmt) != 0) {
                $simultaneo = $gmt->cf_simtu;
            } else {
                $simultaneo = 1;
            }
            $blocking = BlackLists::where('emp_id', $request['emp_id'])->where('email', $request['email'])->where('status', '1')->count();
            if ($blocking != 0) {
                return response()->json(["response" => "blocking"]);
            }
            if (isset($request['dni'])) {
                $blocking = BlackLists::where('emp_id', $request['emp_id'])->where('email', $request['dni'])->where('status', '1')->count();
                if ($blocking != 0) {
                    return response()->json(["response" => "blocking"]);
                }
            }
            $dni = '';
            if (isset($request['dni'])) {
                $dni = $request['dni'];
            }
            $get_user = UsersApp::where('us_mail', $request['email'])->first();
            if (isset($get_user) != 0) {
                $us_id = $get_user->us_id;
            } else {
                UsersApp::create([
                    'us_nom' => $request['name'],
                    'us_mail' => $request['email']
                ]);
                $user_app = UsersApp::where('us_mail', $request['email'])->first();
                $us_id = $user_app->us_id;
            }
            $data = Directory::where('email', $request['email'])->where('emp_id', $request['emp_id'])->where('status', '1')->first();
            if (isset($data) != 0) {
                $us_id = $data->us_id;
            } else {
                Directory::create([
                    'emp_id' => $request['emp_id'],
                    'us_id' => $us_id,
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'dni' => $dni
                ]);
                $user = Directory::where('email', $request['email'])->where('emp_id', $request['emp_id'])->where('status', '1')->first();
                $us_id = $user->us_id;
            }
            $get_business = DB::table('tu_emps')->where('em_id', $business)->first();
            if ($get_business->shift_user != 0) {
                if ($get_business->shift_user > 0 && $get_business->shift_user <= 5) {
                    $count_shift = Shift::
                    where('us_id', $us_id)
                    ->where('pres_id', $request['pres_id'])
                    ->where('tu_fec', date("Y-m-d", strtotime($date)))
                    ->count();
                }
                if ($get_business->shift_user > 5 && $get_business->shift_user <= 10) {
                    $week_start = date("Y-m-d", strtotime('last Monday', strtotime($date)));
                    $week_end = date("Y-m-d", strtotime('next Sunday', strtotime($date)));
                    $count_shift = Shift::
                    where('us_id', $us_id)
                    ->where('pres_id', $request['pres_id'])
                    ->where('tu_fec', '>=', date("Y-m-d", strtotime($week_start)))
                    ->where('tu_fec', '<=', date("Y-m-d", strtotime($week_end)))
                    ->count();
                }
                if ($get_business->shift_user > 10 && $get_business->shift_user <= 15) {
                    $init_start = date("Y", strtotime($date)) . '-' . date("m", strtotime($date)) . '-01';
                    $last_day = date("Y", strtotime($date)) . '-' . date("m", strtotime($date)) . '-' . date("d", (mktime(0, 0, 0, date("Y", strtotime(date("m", strtotime($date)))) + 1, 1, date("Y", strtotime(date("Y", strtotime($date))))) - 1));
                    $count_shift = Shift::
                    where('us_id', $us_id)
                    ->where('pres_id', $request['pres_id'])
                    ->where('tu_fec', '>=', date("Y-m-d", strtotime($init_start)))
                    ->where('tu_fec', '<=', date("Y-m-d", strtotime($last_day)))
                    ->count();
                }
                if ($count_shift >= $this->countLimit($get_business->shift_user)) {
                    return response()->json(["response" => "limit"]);
                }
            }
            if ($get_business->ip_user != 0) {
                if ($get_business->ip_user > 0 && $get_business->ip_user <= 5) {
                    $count_shift = Shift::
                    where('tur_ipfrom', $this->getIp())
                    ->where('pres_id', $request['pres_id'])
                    ->where('tu_fec', date("Y-m-d", strtotime($date)))
                    ->count();
                }
                if ($get_business->ip_user > 5 && $get_business->ip_user <= 10) {
                    $week_start = date("Y-m-d", strtotime('last Monday', strtotime($date)));
                    $week_end = date("Y-m-d", strtotime('next Sunday', strtotime($date)));
                    $count_shift = Shift::
                    where('tur_ipfrom', $this->getIp())
                    ->where('pres_id', $request['pres_id'])
                    ->where('tu_fec', '>=', date("Y-m-d", strtotime($week_start)))
                    ->where('tu_fec', '<=', date("Y-m-d", strtotime($week_end)))
                    ->count();
                }
                if ($get_business->ip_user > 10 && $get_business->ip_user <= 15) {
                    $init_start = date("Y", strtotime($date)) . '-' . date("m", strtotime($date)) . '-01';
                    $last_day = date("Y", strtotime($date)) . '-' . date("m", strtotime($date)) . '-' . date("d", (mktime(0, 0, 0, date("Y", strtotime(date("m", strtotime($date)))) + 1, 1, date("Y", strtotime(date("Y", strtotime($date))))) - 1));
                    $count_shift = Shift::
                    where('tur_ipfrom', $this->getIp())
                    ->where('pres_id', $request['pres_id'])
                    ->where('tu_fec', '>=', date("Y-m-d", strtotime($init_start)))
                    ->where('tu_fec', '<=', date("Y-m-d", strtotime($last_day)))
                    ->count();
                }
                if ($count_shift >= $this->countLimit($get_business->ip_user)) {
                    return response()->json(["response" => "limit"]);
                }
            }
            $bloqued_shift = BlockedShedules::
            where('pres_id', $request['pres_id'])
            ->where('tur_date', date("Y-m-d", strtotime($date)))
            ->where('tur_time', date("H:i:s", strtotime($request['tu_hora'])))
            ->count();
            if ($bloqued_shift >= $simultaneo) {   //valido si hay turnos solicitados
                return response()->json(["response" => "false"]);
            }
            $shift = Shift::
            where('pres_id', $request['pres_id'])
            ->where('tu_fec', date("Y-m-d", strtotime($date)))
            ->where('tu_estado', '!=', 'BAJA')
            ->where('tu_hora', date("H:i:s", strtotime($request['tu_hora'])))
            ->count();
            if ($shift >= $simultaneo) {   //valido si hay turnos solicitados
                return response()->json(["response" => "false"]);
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
                if ($request['tu_servid'] != "") {
                    if (substr_count($request['tu_servid'], '-') <= 0) {
                        $get_service = Services::find($request['tu_servid']);
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
                        for ($i = 0; $i <= substr_count($request['tu_servid'], '-'); $i++) {
                            $service = explode('-', $request['tu_servid']);
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
                            if ($i != substr_count($request['tu_servid'], '-')) {
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
                if ($request['inputs_add'] == 0) {
                    $status = 'ALTA';
                } else {
                    $status = 'BLOQUEO';
                }
                Shift::create([
                    'suc_id' => $request['suc_id'],
                    'us_id' => $us_id,
                    'tu_st' => 0,
                    'emp_id' => $request['emp_id'],
                    'pres_id' => $request['pres_id'],
                    'tur_ipfrom' => $this->getIp(),
                    'tu_fec' => date("Y-m-d", strtotime($date)),
                    'tu_hora' => date("H:i:s", strtotime($request['tu_hora'])),
                    'tu_servid' => $request['tu_servid'],
                    'tu_durac' => date("H:i:s", strtotime($set_turtime)),
                    'tu_horaf' => $this->sumarHoras(date("H:i:s", strtotime($request['tu_hora'])), date("H:i:s", strtotime($set_turtime))),
                    'tu_estado' => $status,
                    "tu_bloqfec" => date("Y-m-d"),
                    "tu_bloqhor" => date("H:i:s"),
                    'tu_usadm' => $us_id,
                    'tu_carga' => 1
                ]);
                $get_shift = Shift::
                where('suc_id', $request['suc_id'])
                ->where('emp_id', $request['emp_id'])
                ->where('pres_id', $request['pres_id'])
                ->where('us_id', $us_id)
                ->where('tu_fec', date("Y-m-d", strtotime($date)))
                ->where('tu_hora', date("H:i:s", strtotime($request['tu_hora'])))
                ->orderBy('tu_id', 'desc')
                ->first();
                $dinit = date("H:i:s", strtotime($request['tu_hora']));
                $dend = $this->sumarHoras(date("H:i:s", strtotime($request['tu_hora'])), date("H:i:s", strtotime($set_turtime)));
                $tur_status = 'BLOQUEO';
                if ($request['inputs_add'] == 0) {
                    $tur_status = 'ALTA';
                }
                if (date("H", strtotime($dinit)) <= 24 && date("H", strtotime($dend)) <= 24) {
                    $interval = new DateInterval('PT5M');
                    $period_empty = new DatePeriod(new DateTime($dinit), $interval, new DateTime($dend));
                    foreach ($period_empty as $hour) {
                        if ($dend > $hour->format('H:i:s')) {
                            BlockedShedules::create([
                                'tur_id' => $get_shift->tu_id,
                                'us_id' => $us_id,
                                'pres_id' => $request['pres_id'],
                                'tur_date' => date("Y-m-d", strtotime($date)),
                                'tur_time' => $hour->format('H:i:s'),
                                'tu_hora' => date("H:i:s", strtotime($request['tu_hora'])),
                                'tu_bloqhor' => date("H:i:s"),
                                'tur_status' => $tur_status,
                            ]);
                        }
                    }


                   
                }
                if ($request['inputs_add'] == 0) {
                    $code = $request['emp_id'] . $request['suc_id'] . $request['pres_id'] . $get_shift->tu_id;
                    $shift = Shift::find($get_shift->tu_id);
                    $shift->fill(['tu_code' => $code]);
                    $shift->save();
                    $this->send_mail($get_shift->tu_id);
                }
                return response()->json(["response" => "true", "url" => $get_shift->tu_id]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function update_shift(Request $request) {
        try {
            $get_shift = Shift::find($request['shift']);
            if (isset($get_shift) == 0) {
                return response()->json(["response" => "false"]);
            } else {
                if (isset($request['paymentMethodId']) && !$this->payment($get_shift->tu_id, $request['amount'], $request['token'], $request['paymentMethodId'], $request['emp_id'], $request['pres_id'], $get_shift->us_id)) {
                    return response()->json(["response" => "no-payment"]);
                }
                $code = $request['emp_id'] . $request['suc_id'] . $request['pres_id'] . $get_shift->tu_id;
                $shift = Shift::find($get_shift->tu_id);
                $shift->fill(['tu_code' => $code, 'tu_estado' => 'ALTA']);
                $shift->save();
                if (ClientsCustomization::where('usm_turid', $get_shift->tu_id)->count() == 0) {
                    ClientsCustomization::insert([
                        'usm_usid' => $get_shift->us_id,
                        'usm_empid' => $get_shift->emp_id,
                        'usm_turid' => $get_shift->tu_id
                    ]);
                }
                if (isset($request['date_1_dd']) && isset($request['date_1_mm']) && isset($request['date_1'])) {
                    $brithdate = $request['date_1'] . "-" . $request['date_1_mm'] . "-" . $request['date_1_dd'];
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_fecnac' => $brithdate,
                    ]);
                }
                if (isset($request['f_2'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_tipdoc' => $request['f_2'],
                    ]);
                }
                if (isset($request['f_3'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_obsoc' => $request['f_3'],
                    ]);
                }
                if (isset($request['f_4'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_obsocpla' => $request['f_4'],
                    ]);
                }
                if (isset($request['f_5'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_numdoc' => $request['f_5'],
                    ]);
                }
                if (isset($request['f_6'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_afilnum' => $request['f_6'],
                    ]);
                }
                if (isset($request['f_7'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_tel' => $request['f_7'],
                    ]);
                }
                if (isset($request['f_8'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_cel' => $request['f_8'],
                    ]);
                }
                if (isset($request['f_9'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_gen1' => $request['f_9'],
                    ]);
                }
                if (isset($request['f_10'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_gen2' => $request['f_10'],
                    ]);
                }
                if (isset($request['f_11'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
                        'usm_gen3' => $request['f_11'],
                    ]);
                }
                if (isset($request['f_12'])) {
                    ClientsCustomization::where('usm_turid', $request['shift'])->update([
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
                BlockedShedules::where('tur_id', $get_shift->tu_id)->update([
                    'tur_status' => 'ALTA'
                ]);

                $business = $request['emp_id'];
                $lender = $request['pres_id'];

                if (Cache::has('settings_business_' . $request['emp_id'])) {
                    $gmt = Cache::get('settings_business_' . $request['emp_id']);
                } else {
                    $gmt = Cache::rememberForever('settings_business_' . $business, function ()use($business) {
                        return DB::table('tu_emps_con')->where('emp_id', $business)->where('suc_id', '0')->where('pres_id', '0')->first();
                    });
                }
                if (Cache::has('settings_lender_' . $request['pres_id'])) {
                    $gmt = Cache::get('settings_lender_' . $request['pres_id']);
                } else {
                    $gmt = Cache::rememberForever('settings_lender_' . $lender, function ()use($lender) {
                        return DB::table('tu_emps_con')->where('pres_id', $lender)->first();
                    });
                }
                if (isset($gmt) != 0) {
                    $simultaneo = $gmt->cf_simtu;
                } else {
                    $simultaneo = 1;
                }

                $lender = Lenders::find($get_shift->pres_id);
                if ($lender->activate_zoom == 1) {
                    $this->generate_meeting($get_shift->tu_id);
                }
                $this->send_mail($get_shift->tu_id);
                $code = $request['emp_id'] . $request['suc_id'] . $request['pres_id'] . $get_shift->tu_id;
                return response()->json(["response" => "true", "url" => $get_shift->tu_id]);
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
    public function lists_lenders($id) {
        try {
            $json = array();
            $lenders = Lenders::where('emp_id', $id)->where('tmsp_estado', 'ALTA')->orderby('position', 'asc')->get();
            foreach ($lenders as $rs) {
                $branch = Branch::find($rs->suc_id);
                if ($branch->suc_estado == "ALTA") {
                    $lender = Lenders::find($rs->tmsp_id);
                    $lender->fill([
                        'tmsp_pnom' => $rs->tmsp_pnom,
                        'url' => $rs->tmsp_id . ' ' . $rs->tmsp_pnom,
                    ]);
                    $lender->save();
                    $json[] = array(
                        "id" => $rs->tmsp_id,
                        "suc_id" => $rs->suc_id
                    );
                }
            }
            return response()->json($json);
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

    public function countLimit($limit) {
        switch ($limit) {
            case 1:
            $limit = 1;
            break;
            case 2:
            $limit = 2;
            break;
            case 3:
            $limit = 3;
            break;
            case 4:
            $limit = 4;
            break;
            case 5:
            $limit = 5;
            break;
            case 6:
            $limit = 1;
            break;
            case 7:
            $limit = 2;
            break;
            case 8:
            $limit = 3;
            break;
            case 9:
            $limit = 4;
            break;
            case 10:
            $limit = 5;
            break;
            case 11:
            $limit = 1;
            break;
            case 12:
            $limit = 2;
            break;
            case 13:
            $limit = 3;
            break;
            case 14:
            $limit = 4;
            break;
            case 15:
            $limit = 5;
            break;
            default:
            break;
        }
        return $limit;
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

    public function send_mail($id) {
        $get_shift = Shift::find($id);
        $services = '';
        if ($get_shift->tu_servid != null) {
            if (substr_count($get_shift->tu_servid, '-') <= 0) {
                $service_id = $get_shift->tu_servid;
                $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                    return Services::where('serv_id', $service_id)->first();
                });
                if (isset($get_service) != 0) {
                    $services .= $get_service->serv_nom;
                }
            } else {
                for ($i = 0; $i <= substr_count($get_shift->tu_servid, '-'); $i++) {
                    $service = explode('-', $get_shift->tu_servid);
                    $service_id = $service[$i];
                    $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                        return Services::where('serv_id', $service_id)->first();
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
        $lender = Lenders::find($get_shift->pres_id);
        $business = Business::find($get_shift->emp_id);
        $address = DB::table('tu_emps_suc')->where('suc_id', $get_shift->suc_id)->first();
        $user = Directory::where('us_id', $get_shift->us_id)->where('emp_id', $get_shift->emp_id)->first();
        $time = (date("H", strtotime($get_shift->tu_hora)) <= 12) ? 'AM' : 'PM';
        $create = $this->nameDay(date("w", strtotime($get_shift->tu_fec))) . ' ' . date("d", strtotime($get_shift->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($get_shift->tu_fec))) . ' del ' . date("Y", strtotime($get_shift->tu_fec));
        $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))) . '.<br/>
        <br/>
        Te informamos a través de este correo electrónico que el turno que has solicitado para el ' . $create . ' a las  ' . date("H:i", strtotime($get_shift->tu_hora)) . ' ' . $time . ' ha sido agendado con éxito.<br /><br><br>
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
        $replyto = mb_convert_encoding($business->em_email, 'UTF-8', 'UTF-8');
        if ($replyto == "" || false == filter_var($replyto, FILTER_VALIDATE_EMAIL)) {
            $replyto = env('MAIL_FROM_ADDRESS');
        }
        $sumaHoras = $this->sumarHoras(date("H:i:s", strtotime($get_shift->tu_hora)), date("H:i:s", strtotime($get_shift->tu_durac)));
        $btn_google = "https://calendar.google.com/calendar/r/eventedit?text=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&dates=" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($get_shift->tu_hora)) . "/" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($sumaHoras)) . "&details=" . urlencode($services) . "&location=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso) . "&sf=true&output=xml";
        $btn_ical = "https://www.turnonet.com/frame/calendario2/download-ics.php?summary=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&description=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&date_start=" . urlencode($get_shift->tu_fec) . "&date_end=" . urlencode($get_shift->tu_fec) . "&summary=" . urlencode($services) . "&location=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso);
        $btn_yahoo = "https://calendar.yahoo.com/?v=60&view=d&title=" . urlencode("Recordatorio de turno - " . mb_strtoupper(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'))) . "&st=" . $get_shift->tu_fec . "T" . date("H:i:s", strtotime($get_shift->tu_hora)) . "&dur=00000&desc=" . urlencode($services) . "&in_loc=" . urldecode($address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso);
        if ($user->email != "" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($user->email, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuShiftMail($replyto, 'email_shitf', $title, $content, $get_shift->tur_canid, $btn, $btn_google, $btn_ical, $btn_ical, $btn_yahoo));
        }
        if (isset($notifications_business) != 0 && $notifications_business->pc_mailn == '1') {
            $content_2 = str_replace(
                'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))),
                'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8')))
                , $content);
            $email = $business->em_email;
            $name = mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8');
            if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($email, mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuShiftMail($replyto, 'email_shitf', $title, $content_2, $get_shift->tur_canid, $btn, $btn_google, $btn_ical, $btn_ical, $btn_yahoo));
            }
            $content_2 = str_replace(
                'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))),
                'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8')))
                , $content);
            $email = $lender->tmsp_pmail;
            $name = mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8');
            if ($email != "" && false !== filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($email, mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuShiftMail($replyto, 'email_shitf', $title, $content_2, $get_shift->tur_canid, $btn, $btn_google, $btn_ical, $btn_ical, $btn_yahoo));
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
                        'tusms_pacnom' => mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'),
                        'tusms_celenv' => trim($business->em_tel),
                        "tusms_msg" => "El turno para el " . date("d/m/Y", strtotime($get_shift->tu_fec)) . " a las " . date("H:i", strtotime($get_shift->tu_hora)) . " hs. ha sido agendado por " . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))),
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
    }

    public function payment($shift, $amount, $token, $paymentMethodId, $business, $lender, $us_id) {
        $site = DB::table('tu_settingsmp')->where('id', '1')->first();
        $get_business = Business::find($business);
        $user = Directory::where('us_id', $us_id)->where('emp_id', $business)->first();
        $commission = $amount * $get_business->commission / 100;
        $commission = round($commission, 4);
        $email = $user->email;
        $description = 'Pago de servicio';
        $user_token = $get_business->access_token;
//$user_token="TEST-7588120685842923-030920-6ea3a7b82a2dd3066d4e0e97a7a908ba-534428992";
        require ('mercadopago/mercadopago.php');
        MercadoPago\SDK::configure(['ACCESS_TOKEN' => $user_token]);
        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = $amount;
        $payment->application_fee = $commission;
        $payment->token = $token;
        $payment->description = $description;
        $payment->installments = 1;
        $payment->payment_method_id = $paymentMethodId;
        $payment->payer = array(
            "email" => $email
        );
        $payment->save();

        Log::info('PAGO POR MERCADO PAGO PARA '.$get_business->em_id.' '.$payment->status.' '.$payment->status_detail);
        
// VALIDAR LA TRANSACCION
        if ($payment->status == 'approved' && isset($payment->id) != 0) {
            $pago = DB::table('tu_mercadopago')->insert([
                'id_payment' => $payment->id,
                'id_turno' => $shift,
                "amount" => $amount,
                "commission" => $commission,
                "id_prestador" => $lender,
                'emp_id' => $business,
                'created_at' => date("Y-m-d H:i:s")
            ]);
            return true;
        } else {
            return false;
        }
    }

    public function validate_captcha_google($token) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => env('CAPTCHA_KEY_SECRET'), 'response' => $token)));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $arrResponse = json_decode($response, true);
            if ($arrResponse["success"] == '1' && $arrResponse["action"] == 'homepage' && $arrResponse["score"] >= 0.5) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function updateUrl($value) {
        $value = strtolower($value);
        $value = mb_strtolower($value, 'UTF-8');
        $value = trim($value);
        $value = preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[\s-]/s', '', $value);
//Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $value = str_replace($find, $repl, $value);
// Añadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
        $value = str_replace($find, '-', $value);
// Eliminamos y Reemplazamos otros carácteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $value = preg_replace($find, $repl, $value);
//Asignamos Valor al atributo  URL
        return $value;
    }

//reinaldo email existance validation
    public function validateMailExistance($email) {
        include('ValidateMailExistance.php');
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
                return false;
            } else {
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

    public function delete_meeting($id) {
        
        $shift = Shift::find($id);
        $shift->fill(['url_zoom' => '', 'password_zoom' => '','id_meeting'=>'']);
        $shift->save();
    }

    public function setNoty($content) {
        $content = str_replace("\n", '<br>', $content);
        return $content;
    }

}
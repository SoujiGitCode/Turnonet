<?php

namespace App\Http\Controllers;

use App\Mail\TuMail;
use Illuminate\Http\Request;
use Route;
use App\Activities;
use App\Categories;
use App\WorkSocialBusiness;
use App\Visits;
use App\Business;
use App\ReportsBusiness;
use App\BusinessFields;
use App\SettingsBusiness;
use App\LenderNotifications;
use App\Services;
use App\Branch;
use App\Country;
use App\SocialWork;
use App\UsersApp;
use App\Directory;
use App\Lenders;
use App\Shift;
use App\Shedules;
use Artisan;
use Redirect;
use DB;
use Cache;
use Mail;
use Session;
use Image;
use Auth;
use URL;
use App\Http\Requests;

class BranchController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('user');
        $this->middleware('VerifyShift', ['except' => ['lists_business']]);
    }

    /**
     * Display dashboard
     * @return type
     */
    public function index(Request $request) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Sucursales";
                $this->visits('Sucursales');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.branch_lists', ['subtitle' => $subtitle, 'business' => $business, 'act_branch' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display create
     * @return type
     */
    public function create(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/sucursales');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');

                $subtitle = " - Sucursales - Nueva Sucursal";
                $this->visits('Sucursales - Nueva Sucursal');
                $countries = Country::orderby('pa_nom', 'asc')->get();
                $em_rub = Categories::select('rub_cat')->distinct()->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.create_branch', ['subtitle' => $subtitle, 'em_rub' => $em_rub, 'countries' => $countries, 'business' => $business, 'get_business' => $get_business, 'act_business' => '1', 'menu_sucursal' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display edit
     * @return type
     */
    public function edit(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');
                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');
                $subtitle = " - Sucursales - Editar sucursal";
                $this->visits('Sucursales - Editar sucursal');
                $get_business = Business::find($get_branch->suc_empid);
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                $countries = Country::orderby('pa_nom', 'asc')->get();

                return view('frontend.edit_branch', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'business' => $business, 'countries' => $countries, 'act_branch' => '1', 'menu_editar' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display diary
     * @return type
     */
    public function diary(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');


                $settings = LenderNotifications::where('pc_sucid', $id)->where('pc_presid', null)->first();
                $subtitle = " - Sucursales - Agenda";
                $this->visits('Sucursales - Agenda');
                $get_business = Business::find($get_branch->suc_empid);
                $lenders = Lenders::where('emp_id', $get_business->em_id)->where('suc_id', $id)->where('tmsp_estado', 'ALTA')->get();
                $branch = Branch::where('suc_empid', $get_business->em_id)->where('suc_estado', 'ALTA')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.diary_branch', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'business' => $business, 'settings' => $settings, 'act_branch' => '1', 'menu_diary' => '1', 'lenders' => $lenders, 'branch' => $branch]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display notifications
     * @return type
     */
    public function notifications(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');

                if (LenderNotifications::where('pc_sucid', $id)->where('pc_presid', null)->count() == 0) {
                    LenderNotifications::create([
                        'pc_empid' => $get_branch->suc_empid,
                        'pc_sucid' => $id,
                        'pc_presid' => null
                    ]);
                }
                $settings = LenderNotifications::where('pc_sucid', $id)->where('pc_presid', null)->first();
                $subtitle = " - Sucursales - Notificaciones";
                $this->visits('Sucursales - Notificaciones');
                $get_business = Business::find($get_branch->suc_empid);
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.notifications_branch', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'business' => $business, 'settings' => $settings, 'act_branch' => '1', 'menu_notifications' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display notifications
     * @return type
     */
    public function send_reports(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');

                $get_business = Business::find($get_branch->suc_empid);

                if (ReportsBusiness::where('emp_id', $get_business->em_id)->count() == 0) {
                    ReportsBusiness::create([
                        'emp_id' => $get_business->em_id
                    ]);
                }
                $settings = ReportsBusiness::where('emp_id', $get_business->em_id)->first();

                $subtitle = " - Sucursales - Enviar Reportes";
                $this->visits('Sucursales - Enviar Reportes');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.send_reports_branch', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'business' => $business, 'act_branch' => '1', 'menu_reports' => '1', 'settings' => $settings]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display settings
     * @return type
     */
    public function settings(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');
                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');
                if (SettingsBusiness::where('suc_id', $id)->where('pres_id', '0')->count() == 0) {


                    $gt_settingbs = SettingsBusiness::where('emp_id', $get_branch->suc_empid)->where('suc_id', '0')->where('pres_id', '0')->first();
                    if (isset($gt_settingbs) != 0) {

                        SettingsBusiness::create([
                            'emp_id' => $get_branch->suc_empid,
                            'suc_id' => $id,
                            'pres_id' => '0',
                            'cf_simtu' => $gt_settingbs->cf_simtu,
                            'cf_days' => $gt_settingbs->cf_days,
                            'cf_daysp' => $gt_settingbs->cf_daysp,
                            'cf_tcan' => $gt_settingbs->cf_tcan,
                            'cf_tipval' => $gt_settingbs->cf_tipval,
                            'cf_turt' => $gt_settingbs->cf_turt,
                            'cf_daysp_all' => $gt_settingbs->cf_daysp_all,
                            'cf_days_all' => $gt_settingbs->cf_days_all,
                            'cf_tcan_all' => $gt_settingbs->cf_tcan_all
                        ]);
                    } else {
                        SettingsBusiness::create([
                            'emp_id' => $get_branch->suc_empid,
                            'suc_id' => $id,
                            'pres_id' => '0'
                        ]);
                    }
                }
                $settings = SettingsBusiness::where('suc_id', $id)->where('pres_id', '0')->first();
                $subtitle = " - Sucursales - Configuración";
                $this->visits('Sucursales - Configuración');
                $get_business = Business::find($get_branch->suc_empid);
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.settings_branch', ['subtitle' => $subtitle, 'get_branch' => $get_branch, 'get_business' => $get_business, 'business' => $business, 'settings' => $settings, 'act_branch' => '1', 'menu_settings' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /*
     * Display shedules
     * @return type
     */

    public function shedules(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');
                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');
                $subtitle = " - Sucursales - Días y horarios de atención";
                $this->visits('Sucursales - Días y horarios de atención');
                $get_business = Business::find($get_branch->suc_empid);

                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.shedules_branch', ['subtitle' => $subtitle, 'get_branch' => $get_branch, 'get_business' => $get_business, 'business' => $business, 'act_branch' => '1', 'menu_shedules' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display not_working
     * @return type
     */
    public function not_working(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');
                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');
                $subtitle = " - Sucursales - Días no laborables";
                $this->visits('Sucursales - Días no laborables');
                $get_business = Business::find($get_branch->suc_empid);
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.not_working_branch', ['subtitle' => $subtitle, 'get_branch' => $get_branch, 'get_business' => $get_business, 'business' => $business, 'act_branch' => '1', 'menu_not_working' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display frame
     * @return type
     */
    public function baja(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');
                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');
                $subtitle = " - Sucursales - Eliminar Sucursal";
                $this->visits('Sucursales - Eliminar Sucursal');




                $get_business = Business::find($get_branch->suc_empid);

                $shift = Shift::where('suc_id', $id)->where('tu_fec', '>=', date("Y-m-d"))->where('tu_estado', 'ALTA')->count();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.baja_branch', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'business' => $business, 'shift' => $shift, 'act_branch' => '1', 'menu_delete' => '1']);
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
                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');
                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');

                $get_business = Business::find($get_branch->suc_empid);


                $subtitle = " - Sucursal - Prestadores";
                $this->visits('Sucursal - Prestadores');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();

                return view('frontend.lenders_branch', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'business' => $business, 'act_branch' => '1', 'menu_lenders' => '1']);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list branch
     * @return type
     */
    public function lists($id, $type) {
        try {
            $branch = Branch::where('suc_uscid', $this->getIdBusiness())->where('suc_empid', $id)->where('suc_estado', $type)->orderby('suc_uscid', 'desc')->get();
            $json = array();
            foreach ($branch as $rs) {

                $image = url('/') . '/img/empty.jpg';

                $business_id = $rs->suc_empid;

                $business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                            return Business::where('em_id', $business_id)->first();
                        });

                if ($business->em_pfot != "") {
                    $image = "https://www.turnonet.com/fotos/empresas/" . $business->em_pfot;
                }
                if ($rs->suc_pfot != "") {
                    $image = "https://www.turnonet.com/fotos/sucursales/" . $rs->suc_pfot;
                }
                $lenders = Lenders::where('suc_id', $rs->suc_id)->where('tmsp_estado', 'ALTA')->count();
                $shift = Shift::where('suc_id', $rs->suc_id)->count();

                if (Auth::guard('user')->User()->level == '1' || Auth::guard('user')->User()->rol != '1') {

                    $json[] = array(
                        "id" => $rs->suc_id,
                        "image" => $image,
                        "lenders" => $lenders,
                        "shift" => $shift,
                        "suc_estado" => $rs->suc_estado,
                        "name" => mb_convert_encoding($rs->suc_nom, 'UTF-8', 'UTF-8'),
                        "address" => $rs->suc_dom . " " . $rs->suc_domnum . " " . $rs->suc_dompiso,
                        "email" => strtolower($rs->em_email),
                    );
                } else {
                    if (isset(Auth::guard('user')->User()->suc_id) && Auth::guard('user')->User()->suc_id != "" && Auth::guard('user')->User()->suc_id != "0") {

                        if (Auth::guard('user')->User()->suc_id == $rs->suc_id) {

                            $json[] = array(
                                "id" => $rs->suc_id,
                                "image" => $image,
                                "lenders" => $lenders,
                                "shift" => $shift,
                                "suc_estado" => $rs->suc_estado,
                                "name" => mb_convert_encoding($rs->suc_nom, 'UTF-8', 'UTF-8'),
                                "address" => $rs->suc_dom . " " . $rs->suc_domnum . " " . $rs->suc_dompiso,
                                "email" => strtolower($rs->em_email),
                            );
                        }
                    } else {



                        $json[] = array(
                            "id" => $rs->suc_id,
                            "image" => $image,
                            "lenders" => $lenders,
                            "shift" => $shift,
                            "suc_estado" => $rs->suc_estado,
                            "name" => mb_convert_encoding($rs->suc_nom, 'UTF-8', 'UTF-8'),
                            "address" => $rs->suc_dom . " " . $rs->suc_domnum . " " . $rs->suc_dompiso,
                            "email" => strtolower($rs->em_email),
                        );
                    }
                }
            }
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list business
     * @return type
     */
    public function lists_business($id) {
        try {

            if (Auth::guard('user')->User()->us_id == "474536") {

                $branchs = Branch::where('suc_empid', $id)->where('suc_estado', 'ALTA')->orderby('suc_uscid', 'desc')->get();
                $json = array();
                foreach ($branchs as $rs) {


                    $json[] = array(
                        "id" => $rs->suc_id,
                        "name" => mb_strtoupper(mb_convert_encoding($rs->suc_nom, 'UTF-8', 'UTF-8')),
                    );
                }
            } else {

                $branchs = Branch::where('suc_uscid', $this->getIdBusiness())->where('suc_empid', $id)->where('suc_estado', 'ALTA')->orderby('suc_uscid', 'desc')->get();
                $json = array();
                foreach ($branchs as $rs) {

                    if (Auth::guard('user')->User()->level == '1' || Auth::guard('user')->User()->rol != '1') {

                        $json[] = array(
                            "id" => $rs->suc_id,
                            "name" => mb_strtoupper(mb_convert_encoding($rs->suc_nom, 'UTF-8', 'UTF-8')),
                        );
                    } else {

                        if (isset(Auth::guard('user')->User()->suc_id) && Auth::guard('user')->User()->suc_id != "" && Auth::guard('user')->User()->suc_id != "0") {

                            if (Auth::guard('user')->User()->suc_id == $rs->suc_id) {

                                $json[] = array(
                                    "id" => $rs->suc_id,
                                    "name" => mb_strtoupper(mb_convert_encoding($rs->suc_nom, 'UTF-8', 'UTF-8')),
                                );
                            }
                        } else {

                            $json[] = array(
                                "id" => $rs->suc_id,
                                "name" => mb_strtoupper(mb_convert_encoding($rs->suc_nom, 'UTF-8', 'UTF-8')),
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

    /**
     * Display update branch
     * @return type
     */
    public function update_branch(Request $request) {
        try {
            $this->audit('Actualizar datos  de la sucursal ' . $request['id']);
            if (isset($request['suc_pfot'])) {


                $request->validate([
                    'suc_pfot' => 'mimes:jpeg,png,jpg|required|file|max:40960'
                ]);

                $path = env('PATH_BRANCH');
                $pic_name = $_FILES['suc_pfot']['name'];
                $temp = $_FILES['suc_pfot']['tmp_name'];
                $upload = $path . $pic_name;
                move_uploaded_file($temp, $upload);
                $name = explode('.', $pic_name);
                $name = strtolower($name[0]);
                $name = mb_strtolower($name, 'UTF-8');
                $name = str_replace('á', 'a', $name);
                $name = str_replace('é', 'e', $name);
                $name = str_replace('í', 'i', $name);
                $name = str_replace('ó', 'o', $name);
                $name = str_replace('ú;', 'u', $name);
                $name = str_replace('ñ', 'n', $name);
                $name = preg_replace('([^A-Za-z0-9])', '', $name);
                $name = str_replace('-', '_', $name);
                $name = str_replace(' ', '_', $name);
                $type = strtolower(strrchr($pic_name, "."));
                $pic_new = $name . "_" . time() . $type;
                $upload_new = $path . $pic_new;
                rename($upload, $upload_new);
                $img = Image::make(file_get_contents($path . $pic_new));
                $img->resize(200, 200)->save($path . $pic_new, 90);
                $branch = Branch::find($request['id']);
                $branch->fill([
                    'suc_pfot' => $pic_new,
                ]);
                $branch->save();
            }
            $branch = Branch::find($request['id']);
            $branch->fill([
                'suc_nom' => $request['suc_nom'],
                'suc_email' => $request['suc_email'],
                'suc_tel' => $request['suc_tel'],
                'suc_cont' => $request['suc_cont'],
                'suc_pais' => $request['suc_pais'],
                'suc_dom' => $request['suc_dom'],
                'suc_dompiso' => $request['suc_dompiso'],
                'suc_dompnum' => $request['suc_dompnum'],
                'suc_doment' => $request['suc_doment'],
                'suc_domcp' => $request['suc_domcp'],
                'suc_hor' => $request['suc_hor']
            ]);
            $branch->save();
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display create branch
     * @return type
     */
    public function create_branch(Request $request) {
        try {
            $pic_new = '';
            if (isset($request['suc_pfot'])) {


                $request->validate([
                    'suc_pfot' => 'mimes:jpeg,png,jpg|required|file|max:40960'
                ]);
                
                $path = env('PATH_BRANCH');
                $pic_name = $_FILES['suc_pfot']['name'];
                $temp = $_FILES['suc_pfot']['tmp_name'];
                $upload = $path . $pic_name;
                move_uploaded_file($temp, $upload);
                $name = explode('.', $pic_name);
                $name = strtolower($name[0]);
                $name = mb_strtolower($name, 'UTF-8');
                $name = str_replace('á', 'a', $name);
                $name = str_replace('é', 'e', $name);
                $name = str_replace('í', 'i', $name);
                $name = str_replace('ó', 'o', $name);
                $name = str_replace('ú;', 'u', $name);
                $name = str_replace('ñ', 'n', $name);
                $name = preg_replace('([^A-Za-z0-9])', '', $name);
                $name = str_replace('-', '_', $name);
                $name = str_replace(' ', '_', $name);
                $type = strtolower(strrchr($pic_name, "."));
                $pic_new = $name . "_" . time() . $type;
                $upload_new = $path . $pic_new;
                rename($upload, $upload_new);
                $img = Image::make($path . $pic_new);
                $img->resize(200, 200)->save($path . $pic_new, 90);
            }
            Branch::create([
                'suc_empid' => $request['id'],
                'suc_uscid' => $this->getIdBusiness(),
                'suc_nom' => $request['suc_nom'],
                'suc_email' => $request['suc_email'],
                'suc_tel' => $request['suc_tel'],
                'suc_cont' => $request['suc_cont'],
                'suc_pais' => $request['suc_pais'],
                'suc_dom' => $request['suc_dom'],
                'suc_dompiso' => $request['suc_dompiso'],
                'suc_dompnum' => $request['suc_dompnum'],
                'suc_doment' => $request['suc_doment'],
                'suc_domcp' => $request['suc_domcp'],
                'suc_hor' => $request['suc_hor'],
                'suc_pfot' => $pic_new,
            ]);
            $branch = Business::all();
            $branch = $branch->last();


            $gt_settingbs = SettingsBusiness::where('emp_id', $request['id'])->where('suc_id', '0')->where('pres_id', '0')->first();
            if (isset($gt_settingbs) != 0) {

                SettingsBusiness::create([
                    'emp_id' => $request['id'],
                    'suc_id' => $branch->suc_id,
                    'pres_id' => '0',
                    'cf_simtu' => $gt_settingbs->cf_simtu,
                    'cf_days' => $gt_settingbs->cf_days,
                    'cf_daysp' => $gt_settingbs->cf_daysp,
                    'cf_tcan' => $gt_settingbs->cf_tcan,
                    'cf_tipval' => $gt_settingbs->cf_tipval,
                    'cf_turt' => $gt_settingbs->cf_turt,
                    'cf_daysp_all' => $gt_settingbs->cf_daysp_all,
                    'cf_days_all' => $gt_settingbs->cf_days_all,
                    'cf_tcan_all' => $gt_settingbs->cf_tcan_all
                ]);
            }

            $this->audit('Registro de la sucursal ' . $branch->suc_id);
            return response()->json(["msg" => "updated", "id" => $branch->suc_id]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display update notifications
     * @return type
     */
    public function update_notifications(Request $request) {
        try {
            $this->audit('Actualización de configuración de notificaciones de la sucursal ');

            if (isset($request['pc_emp_msg'])) {
                $pc_emp_msg = 1;
            } else {
                $pc_emp_msg = 0;
            }
            LenderNotifications::where('pc_sucid', $request['id'])->where('pc_presid', null)->update([
                'pc_msg' => $request['pc_msg'],
                'pc_emp_msg' => $pc_emp_msg
            ]);
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display update settings
     * @return type
     */
    public function update_settings(Request $request) {
        try {
            $this->audit('Actualización de configuración de la sucursal ' . $request['suc_id']);
            if (isset($request['cf_daysp_all'])) {
                $cf_daysp_all = '1';
            } else {
                $cf_daysp_all = '0';
            }
            if (isset($request['cf_days_all'])) {
                $cf_days_all = '1';
            } else {
                $cf_days_all = '0';
            }
            if (isset($request['cf_tcan_all'])) {
                $cf_tcan_all = '1';
            } else {
                $cf_tcan_all = '0';
            }
            $date = $request['hours'] . ":" . $request['minutes'] . ":00";

            if ($cf_tcan_all == '1') {

                SettingsBusiness::where('suc_id', $request['suc_id'])->update([
                    'cf_tcan' => $request['cf_tcan'],
                ]);
            }
            if ($cf_daysp_all == '1') {

                SettingsBusiness::where('suc_id', $request['suc_id'])->update([
                    'cf_daysp' => $request['cf_daysp'],
                ]);
            }

            if ($cf_days_all == '1') {

                SettingsBusiness::where('suc_id', $request['suc_id'])->update([
                    'cf_days' => $request['cf_days'],
                ]);
            }


            $settings = SettingsBusiness::find($request['id']);
            $settings->fill([
                'cf_simtu' => $request['cf_simtu'],
                'cf_daysp' => $request['cf_daysp'],
                'cf_days' => $request['cf_days'],
                'cf_tcan' => $request['cf_tcan'],
                'cf_tipval' => $request['cf_tipval'],
                'cf_turt' => date("H:i:s", strtotime($date)),
                'cf_daysp_all' => $cf_daysp_all,
                'cf_days_all' => $cf_days_all,
                'cf_tcan_all' => $cf_tcan_all
            ]);
            $settings->save();
            Artisan::call('optimize:clear');


            return response()->json(["msg" => "updated", "clear" => Artisan::output()]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display update status
     * @return type
     */
    public function update_status(Request $request) {
        try {
            $this->audit('Dar de baja a la sucursal ' . $request['id']);
            if (isset($request['turnos'])) {
                $status_event = '0';
                $shift = Shift::where('suc_id', $request['id'])
                        ->where('tu_fec', '>=', date("Y-m-d"))
                        ->update([
                    'tu_estado' => 'BAJA'
                ]);
            }
            $branch = Branch::find($request['id']);
            $branch->fill([
                'suc_estado' => 'BAJA'
            ]);
            $branch->save();


            $lenders = Lenders::where('suc_id', $request['id'])->get();
            foreach ($lenders as $rs) {

                $lender = Lenders::find($rs->tmsp_id);
                $lender->fill([
                    'tmsp_estado' => 'BAJA'
                ]);
                $lender->save();
            }

            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display alta_branch
     * @return type
     */
    public function alta_branch(Request $request) {
        try {
            $this->audit('Dar de alta a la sucursal ' . $request['id']);

            $branch = Branch::find($request['id']);
            $branch->fill([
                'suc_estado' => 'ALTA'
            ]);
            $branch->save();
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display update shedules
     * @return type
     */
    public function update_shedules(Request $request) {
        try {
            Shedules::where('lab_empid', $request['em_id'])->where('lab_sucid', $request['id'])->where('lab_presid', 0)->delete();
            for ($i = 0; $i < count($request['reg_init']); $i++) {
                if ($request['reg_init'][$i] != "") {

                    if ($request['reg_init_2'][$i] == "") {
                        $lab_hin2 = '00:00:00';
                    } else {
                        $lab_hin2 = $request['reg_init_2'][$i];
                    }
                    if ($request['reg_end_2'][$i] == "") {
                        $lab_hou2 = '00:00:00';
                    } else {
                        $lab_hou2 = $request['reg_end_2'][$i];
                    }
                    Shedules::insert([
                        'lab_empid' => $request['em_id'],
                        'lab_sucid' => $request['id'],
                        'lab_presid' => 0,
                        'lab_dian' => $i,
                        'lab_hin' => $request['reg_init'][$i],
                        'lab_hou' => $request['reg_end'][$i],
                        'lab_hin2' => $lab_hin2,
                        'lab_hou2' => $lab_hou2,
                    ]);
                }
            }
            Artisan::call('optimize:clear');
            return response()->json(["msg" => "updated", "clear" => Artisan::output()]);
            $this->audit('Actualización de configuración de horarios de la sucursal ' . $request['id']);
        } catch (Exception $ex) {
            return false;
        }
        return false;
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

    public function getIdBusiness() {

        if (Auth::guard('user')->User()->level == 1) {
            return Auth::guard('user')->User()->us_id;
        } else {

            $get_business = Business::find(Auth::guard('user')->User()->emp_id);
            return $get_business->em_uscid;
        }
    }

}

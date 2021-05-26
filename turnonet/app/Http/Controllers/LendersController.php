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
use Artisan;
use App\Branch;
use App\Country;
use App\SocialWork;
use App\UsersApp;
use App\Directory;
use App\Lenders;
use App\Shift;
use App\Shedules;
use Redirect;
use DB;
use Cache;
use Mail;
use Session;
use Image;
use Auth;
use URL;
use App\Http\Requests;

class LendersController extends Controller {

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
                $subtitle = " - Prestadores";
                $this->visits('Prestadores');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.lenders_lists', ['subtitle' => $subtitle, 'business' => $business, 'act_lender' => '1']);
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

                $get_branch = Branch::find($id);
                if (isset($get_branch) == 0)
                    return redirect::to('/sucursales');
                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursales');

                $subtitle = " - Sucursales - Nuevo Prestador";
                $this->visits('Sucursales - Nuevo Prestador');

                $get_business = Business::find($get_branch->suc_empid);
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();


                return view('frontend.create_lender', ['subtitle' => $subtitle, 'get_branch' => $get_branch, 'business' => $business, 'get_business' => $get_business, 'act_branch' => '1', 'menu_new_lender' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display shedules
     * @return type
     */
    public function create_shedules(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Prestadores - Días y horarios de atención";
                $this->visits('Prestadores - Días y horarios de atención');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.create_shedules_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_lender' => '1']);
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


                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');


                if (SettingsBusiness::where('pres_id', $id)->count() == 0) {
                    SettingsBusiness::create([
                        'emp_id' => $get_business->em_id,
                        'suc_id' => $get_branch->suc_id,
                        'pres_id' => $id
                    ]);
                }
                $settings = SettingsBusiness::where('pres_id', $id)->first();
                $subtitle = " - Prestadores - Agenda";
                $this->visits('Prestadores - Agenda');
                $lenders = Lenders::where('emp_id', $get_business->em_id)->where('tmsp_estado', 'ALTA')->get();
                $branch = Branch::where('suc_empid', $get_business->em_id)->where('suc_estado', 'ALTA')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.diary_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'settings' => $settings, 'act_lender' => '1', 'menu_diary' => '1', 'lenders' => $lenders, 'branch' => $branch]);
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

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $subtitle = " - Prestadores - Editar Prestador";
                $this->visits('Prestadores - Editar Prestador');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();

                return view('frontend.edit_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'act_lender' => '1', 'menu_editar' => '1']);
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

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');


                if (LenderNotifications::where('pc_presid', $id)->count() == 0) {
                    LenderNotifications::create([
                        'pc_empid' => $get_business->em_id,
                        'pc_sucid' => $get_branch->suc_id,
                        'pc_presid' => $id
                    ]);
                }
                $settings = LenderNotifications::where('pc_presid', $id)->first();

                $subtitle = " - Prestadores - Notificaciones";
                $this->visits('Prestadores - Notificaciones');

                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.notifications_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'settings' => $settings, 'act_lender' => '1', 'menu_notifications' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display send reports
     * @return type
     */
    public function send_reports(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');


                $subtitle = " - Prestadores - Enviar Reportes";
                $this->visits('Prestadores - Enviar Reportes');

                if (ReportsBusiness::where('emp_id', $get_business->em_id)->count() == 0) {
                    ReportsBusiness::create([
                        'emp_id' => $get_business->em_id
                    ]);
                }
                $settings = ReportsBusiness::where('emp_id', $get_business->em_id)->first();

                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.send_reports_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'menu_reports' => '1', 'menu_notifications' => '1', 'settings' => $settings]);
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


                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');


                if (SettingsBusiness::where('pres_id', $id)->count() == 0) {

                	$gt_settingbs=SettingsBusiness::where('emp_id', $get_business->em_id)->where('suc_id', '0')->where('pres_id', '0')->first();

                	if(isset($gt_settingbs)!=0){

                		SettingsBusiness::create([
                			'emp_id' => $get_business->em_id,
                			'suc_id' => $get_branch->suc_id,
                			'pres_id' => $id,
                			'cf_simtu' => $gt_settingbs->cf_simtu,
                			'cf_daysp' => $gt_settingbs->cf_daysp,
                			'cf_days' => $gt_settingbs->cf_days,
                			'cf_tcan' => $gt_settingbs->cf_tcan,
                			'cf_tipval' => $gt_settingbs->cf_tipval,
                			'cf_turt' => $gt_settingbs->cf_turt,
                			'cf_daysp_all' => $gt_settingbs->cf_daysp_all,
                			'cf_days_all' => $gt_settingbs->cf_days_all,
                			'cf_tcan_all' => $gt_settingbs->cf_tcan_all
                		]);

                	}else{
                		SettingsBusiness::create([
                			'emp_id' => $get_business->em_id,
                			'suc_id' => $get_branch->suc_id,
                			'pres_id' => $id
                		]);
                	}
                }
                $settings = SettingsBusiness::where('pres_id', $id)->first();
                $subtitle = " - Prestadores - Configuración";
                $this->visits('Prestadores - Configuración');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.settings_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'settings' => $settings, 'act_lender' => '1', 'menu_settings' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display social work
     * @return type
     */
    public function social_work(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $subtitle = " - Prestadores - Obras Sociales";
                $this->visits('Prestadores - Obras Sociales');
                $social_works = SocialWork::orderby('os_nomp', 'asc')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.social_work_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'social_works' => $social_works, 'act_lender' => '1', 'menu_social_works' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display shedules
     * @return type
     */
    public function shedules(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $subtitle = " - Prestadores - Días y horarios de atención";
                $this->visits('Prestadores - Días y horarios de atención');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.shedules_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'act_lender' => '1', 'menu_shedules' => '1']);
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

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $subtitle = " - Prestadores - Días no laborables";
                $this->visits('Prestadores - Días no laborables');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.not_working_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'act_lender' => '1', 'menu_not_working' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display notes
     * @return type
     */
    public function notes(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $subtitle = " - Prestadores - Observaciones";
                $this->visits('Prestadores - Observaciones');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();

                $lists_customer=$this->lists_customer($get_business->em_id);
                return view('frontend.notes', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'act_lender' => '1', 'menu_notes' => '1','lists_customer'=>$lists_customer]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display services
     * @return type
     */
    public function services(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $subtitle = " - Prestadores - Servicios";
                $this->visits('Prestadores - Servicios');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.services', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'act_lender' => '1', 'menu_servicios' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display specialty
     * @return type
     */
    public function speciality(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $subtitle = " - Prestadores - Especialidades";
                $this->visits('Prestadores - Especialidades');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.speciality', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'act_lender' => '1', 'menu_especialidades' => '1']);
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


                $get_lender = Lenders::find($id);
                if (isset($get_lender) == 0)
                    return redirect::to('/prestadores');


                $get_branch = Branch::find($get_lender->suc_id);
                if (isset($get_branch) == 0)
                    return redirect::to('/prestadores');

                if ($get_branch->suc_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $get_business = Business::find($get_branch->suc_empid);
                if (isset($get_business) == 0)
                    return redirect::to('/prestadores');

                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/prestadores');

                $subtitle = " - Prestadores - Eliminar Prestador";
                $this->visits('Prestadores - Eliminar Prestador');

                $shift = Shift::where('pres_id', $id)->where('tu_fec', '>=', date("Y-m-d"))->where('tu_estado', 'ALTA')->count();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();

                return view('frontend.baja_lender', ['subtitle' => $subtitle, 'get_business' => $get_business, 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business' => $business, 'shift' => $shift, 'act_lender' => '1', 'menu_delete' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list business
     * @return type
     */
    public function lists($id, $status) {
        try {
            $json = array();
            $lenders = Lenders::where('suc_id', $id)->where('tmsp_estado', $status)->orderby('position', 'asc')->get();
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
                if (isset($business) != 0) {
                    $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                                return Branch::where('suc_id', $branch_id)->first();
                            });

                    if ($business->em_pfot != "") {
                        $image = "https://www.turnonet.com/fotos/empresas/" . $business->em_pfot;
                    }
                    if (isset($sucursal) != 0 && $sucursal->suc_pfot != "") {
                        $image = "https://www.turnonet.com/fotos/sucursales/" . $sucursal->suc_pfot;
                    }
                    if ($rs->tmsp_pfot != "") {
                        $image = "https://www.turnonet.com/fotos/prestadores/" . $rs->tmsp_pfot;
                    }

                    $shift = Shift::where('pres_id', $rs->tmsp_id)->count();

                    if (Auth::guard('user')->User()->level == '1' || Auth::guard('user')->User()->rol != '1') {

                        $json[] = array(
                            "empresa" => $id,
                            "image" => $image,
                            "id" => $rs->tmsp_id,
                            "services" => $services,
                            "title" => $rs->tmsp_tit,
                            "shift" => $shift,
                            "zoom"=>$rs->activate_zoom,
                            "tmsp_estado" => $rs->tmsp_estado,
                            "mp" => $rs->tmsp_pagoA,
                            "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                            "email" => strtolower(mb_convert_encoding($email, 'UTF-8', 'UTF-8')),
                        );
                    } else {

                        if (isset(Auth::guard('user')->User()->pres_id) && Auth::guard('user')->User()->pres_id != "" && Auth::guard('user')->User()->pres_id != "0") {

                            if (Auth::guard('user')->User()->pres_id == $rs->tmsp_id) {

                                $json[] = array(
                                    "empresa" => $id,
                                    "image" => $image,
                                    "id" => $rs->tmsp_id,
                                    "services" => $services,
                                    "title" => $rs->tmsp_tit,
                                    "shift" => $shift,
                                    "zoom"=>$rs->activate_zoom,
                                    "tmsp_estado" => $rs->tmsp_estado,
                                    "mp" => $rs->tmsp_pagoA,
                                    "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                                    "email" => strtolower(mb_convert_encoding($email, 'UTF-8', 'UTF-8')),
                                );
                            }
                        } else {

                            $json[] = array(
                                "empresa" => $id,
                                "image" => $image,
                                "id" => $rs->tmsp_id,
                                "services" => $services,
                                "title" => $rs->tmsp_tit,
                                "shift" => $shift,
                                "zoom"=>$rs->activate_zoom,
                                "tmsp_estado" => $rs->tmsp_estado,
                                "mp" => $rs->tmsp_pagoA,
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

    /**
     * Display list bussiness resources
     * @return type
     */
    public function lists_business($id) {
        try {
            $json = array();
            $lenders = Lenders::where('emp_id', $id)->where('tmsp_estado', 'ALTA')->orderby('position', 'asc')->get();
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

                if (isset($business) != 0 && $business->em_pfot != "") {
                    $image = "https://www.turnonet.com/fotos/empresas/" . $business->em_pfot;
                }
                if (isset($sucursal) != 0 && $sucursal->suc_pfot != "") {
                    $image = "https://www.turnonet.com/fotos/sucursales/" . $sucursal->suc_pfot;
                }
                if ($rs->tmsp_pfot != "") {
                    $image = "https://www.turnonet.com/fotos/prestadores/" . $rs->tmsp_pfot;
                }

                $shift = Shift::where('pres_id', $rs->tmsp_id)->count();


                if (Auth::guard('user')->User()->level == '1' || Auth::guard('user')->User()->rol != '1') {

                    $json[] = array(
                        "empresa" => $id,
                        "image" => $image,
                        "id" => $rs->tmsp_id,
                        "services" => $services,
                        "title" => $rs->tmsp_tit,
                        "zoom"=>$rs->activate_zoom,
                        "shift" => $shift,
                        "mp" => $rs->tmsp_pagoA,
                        "tmsp_estado" => $rs->tmsp_estado,
                        "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                        "email" => strtolower(mb_convert_encoding($email, 'UTF-8', 'UTF-8')),
                    );
                } else {

                    if (isset(Auth::guard('user')->User()->pres_id) && Auth::guard('user')->User()->pres_id != "" && Auth::guard('user')->User()->pres_id != "0") {

                        if (Auth::guard('user')->User()->pres_id == $rs->tmsp_id) {

                            $json[] = array(
                                "empresa" => $id,
                                "image" => $image,
                                "id" => $rs->tmsp_id,
                                "services" => $services,
                                "title" => $rs->tmsp_tit,
                                "shift" => $shift,
                                "mp" => $rs->tmsp_pagoA,
                                "zoom"=>$rs->activate_zoom,
                                "tmsp_estado" => $rs->tmsp_estado,
                                "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                                "email" => strtolower(mb_convert_encoding($email, 'UTF-8', 'UTF-8')),
                            );
                        }
                    } else {

                        $json[] = array(
                            "empresa" => $id,
                            "image" => $image,
                            "id" => $rs->tmsp_id,
                            "services" => $services,
                            "title" => $rs->tmsp_tit,
                            "shift" => $shift,
                            "zoom"=>$rs->activate_zoom,
                            "mp" => $rs->tmsp_pagoA,
                            "tmsp_estado" => $rs->tmsp_estado,
                            "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                            "email" => strtolower(mb_convert_encoding($email, 'UTF-8', 'UTF-8')),
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
    public function lista_branch($id) {
        try {

            if (Auth::guard('user')->User()->us_id == "474536") {

                $lenders = Lenders::where('suc_id', $id)->where('tmsp_estado', 'ALTA')->orderby('tmsp_id', 'asc')->get();
                $json = array();
                foreach ($lenders as $rs) {

                    $json[] = array(
                        "id" => $rs->tmsp_id,
                        "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                    );
                }
            } else {

                $lenders = Lenders::where('suc_id', $id)->where('tmsp_estado', 'ALTA')->orderby('tmsp_id', 'asc')->get();
                $json = array();
                foreach ($lenders as $rs) {

                    if (Auth::guard('user')->User()->level == '1' || Auth::guard('user')->User()->rol != '1') {
                        $json[] = array(
                            "id" => $rs->tmsp_id,
                            "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                        );
                    } else {
                        if (isset(Auth::guard('user')->User()->pres_id) && Auth::guard('user')->User()->pres_id != "" && Auth::guard('user')->User()->pres_id != "0") {

                            if (Auth::guard('user')->User()->pres_id == $rs->tmsp_id) {
                                $json[] = array(
                                    "id" => $rs->tmsp_id,
                                    "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                                );
                            } else {
                                $json[] = array(
                                    "id" => $rs->tmsp_id,
                                    "name" => mb_substr(mb_strtoupper(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8')), 0, 40),
                                );
                            }
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
     * Display update business
     * @return type
     */
    public function update_lender(Request $request) {
        try {
            $date = $request['year'] . "-" . $request['month'] . "-" . $request['day'];
            $this->audit('Actualizar datos  del prestador ' . $request['id']);
            if (isset($request['tmsp_pfot'])) {

                $request->validate([
                    'tmsp_pfot' => 'mimes:jpeg,png,jpg|required|file|max:40960'
                ]);
                
                $path = env('PATH_LENDER');
                $pic_name = $_FILES['tmsp_pfot']['name'];
                $temp = $_FILES['tmsp_pfot']['tmp_name'];
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
                $lender = Lenders::find($request['id']);
                $lender->fill([
                    'tmsp_pfot' => $pic_new,
                ]);
                $lender->save();
            }
            $lender = Lenders::find($request['id']);
            $lender->fill([
                'tmsp_pnom' => $request['tmsp_pnom'],
                'url' => $request['em_id'] . ' ' . $request['tmsp_pnom'],
                'tmsp_pmail' => $request['tmsp_pmail'],
                'tmsp_tel' => $request['tmsp_tel'],
                'tmsp_tit' => $request['tmsp_tit'],
                'tmsp_pcel' => $request['tmsp_pcel'],
                'tmsp_dias' => $request['tmsp_dias'],
                'tmsp_fnac' => $date,
            ]);
            $lender->save();
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display create lender
     * @return type
     */
    public function create_lender(Request $request) {
        try {

            $date = $request['year'] . "-" . $request['month'] . "-" . $request['day'];
            $pic_new = '';
            if (isset($request['tmsp_pfot'])) {

                $request->validate([
                    'tmsp_pfot' => 'mimes:jpeg,png,jpg|required|file|max:40960'
                ]);


                $path = env('PATH_LENDER');
                $pic_name = $_FILES['tmsp_pfot']['name'];
                $temp = $_FILES['tmsp_pfot']['tmp_name'];
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
            }
            Lenders::create([
                'emp_id' => $request['em_id'],
                'suc_id' => $request['id'],
                'url' => $request['em_id'] . ' ' . $request['tmsp_pnom'],
                'tmsp_pnom' => $request['tmsp_pnom'],
                'tmsp_pmail' => $request['tmsp_pmail'],
                'tmsp_tel' => $request['tmsp_tel'],
                'tmsp_tit' => $request['tmsp_tit'],
                'tmsp_pcel' => $request['tmsp_pcel'],
                'tmsp_dias' => $request['tmsp_dias'],
                'tmsp_fnac' => $date,
                'tmsp_pfot' => $pic_new,
            ]);
            $lenders = Lenders::all();
            $lender = $lenders->last();

            $gt_settingbs=SettingsBusiness::where('emp_id', $request['em_id'])->where('suc_id', '0')->where('pres_id', '0')->first();

            if(isset($gt_settingbs)!=0){

            	SettingsBusiness::create([
            		'emp_id' => $request['em_id'],
            		'suc_id' => $request['id'],
            		'pres_id' => $lender->tmsp_id,
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

            $this->audit('Registro de prestador ' . $lender->tmsp_id);
            return response()->json(["msg" => "updated", "id" => $lender->tmsp_id]);
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
            $this->audit('Actualización de configuración de notificaciones del prestador ');
            if (isset($request['pc_emp_msg'])) {
                $pc_emp_msg = 1;
            } else {
                $pc_emp_msg = 0;
            }
            if (isset($request['pc_suc_msg'])) {
                $pc_suc_msg = 1;
            } else {
                $pc_suc_msg = 0;
            }
            LenderNotifications::where('pc_presid', $request['id'])->update([
                'pc_msg' => $request['pc_msg'],
                'pc_emp_msg' => $pc_emp_msg,
                'pc_suc_msg' => $pc_suc_msg
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
            $this->audit('Actualización de configuración del prestador ' . $request['em_id']);
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


             if (isset($request['activate_zoom'])) {
                $activate_zoom = '1';
            } else {
                $activate_zoom = '0';
            }


            $date = $request['hours'] . ":" . $request['minutes'] . ":00";
            $settings = SettingsBusiness::find($request['id']);
            $settings->fill([
                'cf_simtu' => $request['cf_simtu'],
                'cf_daysp' => $request['cf_daysp'],
                'cf_days' => $request['cf_days'],
                'cf_tcan' => $request['cf_tcan'],
                'cf_tipval' => $request['cf_tipval'],
                'cf_turt' => date("H:i:s", strtotime($date))
            ]);
            $settings->save();

            if (isset($request['tmsp_pagoA'])) {

                $tmsp_pagoA = 'ALTA';
            } else {
                $tmsp_pagoA = 'BAJA';
            }
            $lender = Lenders::find($request['pres_id']);
            $lender->fill([
                'tmsp_pagoA' => $tmsp_pagoA,
                'activate_zoom'=>$activate_zoom
            ]);
            $lender->save();


            Artisan::call('optimize:clear');
            
            return response()->json(["msg" => "updated","clear"=>Artisan::output()]);
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
            $this->audit('Dar de baja al prestador ' . $request['id']);
            if (isset($request['turnos'])) {
                $status_event = '0';
                $shift = Shift::where('pres_id', $request['id'])
                        ->where('tu_fec', '>=', date("Y-m-d"))
                        ->update([
                    'tu_estado' => 'BAJA'
                ]);
            }
            $lender = Lenders::find($request['id']);
            $lender->fill([
                'tmsp_estado' => 'BAJA'
            ]);
            $lender->save();
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display alta lender
     * @return type
     */
    public function alta_lender(Request $request) {
        try {
            $this->audit('Dar de alta al prestador ' . $request['id']);

            $lender = Lenders::find($request['id']);
            $lender->fill([
                'tmsp_estado' => 'ALTA'
            ]);
            $lender->save();
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display update works
     * @return type
     */
    public function update_works(Request $request) {
        try {
            $this->audit('Actualizar las obras sociales del prestador ' . $request['id']);
            WorkSocialBusiness::where('eob_presid', $request['id'])->delete();
            for ($i = 0; $i < count($request['social']); $i++) {
                WorkSocialBusiness::insert([
                    'eob_empid' => $request['em_id'],
                    'eob_sucid' => $request['suc_id'],
                    'eob_presid' => $request['id'],
                    'eob_obid' => $request['social'][$i]
                ]);
            }
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
            Shedules::where('lab_empid', $request['em_id'])->where('lab_sucid', $request['suc_id'])->where('lab_presid', $request['id'])->delete();
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
                        'lab_sucid' => $request['suc_id'],
                        'lab_presid' => $request['id'],
                        'lab_dian' => $i,
                        'lab_hin' => $request['reg_init'][$i],
                        'lab_hou' => $request['reg_end'][$i],
                        'lab_hin2' => $lab_hin2,
                        'lab_hou2' => $lab_hou2,
                    ]);
                }
            }
             Artisan::call('optimize:clear');
             
            return response()->json(["msg" => "updated","clear"=>Artisan::output()]);
            $this->audit('Actualización de configuración de horarios del prestador ' . $request['id']);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list subcategories
     * @return type
     */
    public function subcategories($name) {
        try {
            $subcategories = Categories::where('rub_cat', $name)->where('rub_nom', '!=', null)->get();
            return response()->json($subcategories);
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
     * Move items lender
     * @param Request $request
     * @return boolean
     */
    public function move_lender(Request $request) {
        try {
            foreach ($request['item'] as $key => $value) {
                $lender = Lenders::find($value);
                $lender->fill([
                    'position' => $key
                ]);
                $lender->save();
            }
            $this->audit('Ordenar prestadores');
            return response()->json(["msg" => "movido"]);
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


    /**
     * Display list resources
     * @return type
     */
    public function lists_customer($business) {
        try {
            $json = array();
            $users = DB::table('directory')->where('emp_id', $business)->where('status','1')->offset(0)->limit(5000)->orderby('name', 'asc')->get();
            foreach ($users as $rs) {
                if ($rs->name != "") {
                    
                    $json[] = array(
                        "id" => $rs->id,
                        "name" => mb_strtolower($rs->name),
                        "email"=>mb_strtolower($rs->email),
                         "us_id" => $rs->us_id,
                    );
                }
            }
            return $this->super_unique($json, 'us_id');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }


    /**
     * Set array
     * @param type $phone
     * @return boolean|string
     */
    public function super_unique($array, $key) {
        $temp_array = array();
        foreach ($array as &$v) {
            if (!isset($temp_array[$v[$key]]))
                $temp_array[$v[$key]] = & $v;
        }
        $array = array_values($temp_array);
        return $array;
    }

}

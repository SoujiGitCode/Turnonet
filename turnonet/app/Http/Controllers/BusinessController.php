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
use App\Frame;
use App\Shedules;
use Redirect;
use Artisan;
use DB;
use Cache;
use Mail;
use Session;
use Image;
use Auth;
use URL;
use App\Http\Requests;

class BusinessController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('user');
        $this->middleware('VerifyShift', ['except' => ['lists', 'send_reports']]);
    }

    /**
     * Display dashboard
     * @return type
     */
    public function index(Request $request) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Empresas";
                $this->visits('Empresas');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.business_lists', ['subtitle' => $subtitle, 'business' => $business, 'act_business' => '1']);
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
    public function create(Request $request) {
        try {
            if (!Auth::guard('user')->guest()) {
                $subtitle = " - Empresas - Nueva Empresa";
                $this->visits('Empresas - Nueva Empresa');
                $countries = Country::orderby('pa_nom', 'asc')->get();
                $em_rub = Categories::select('rub_cat')->distinct()->get();
                return view('frontend.create_business', ['subtitle' => $subtitle, 'em_rub' => $em_rub, 'countries' => $countries, 'act_business' => '1']);
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
                $subtitle = " - Empresas - Días y horarios de atención";
                $this->visits('Empresas - Días y horarios de atención');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.create_shedules_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_business' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display branch
     * @return type
     */
    public function create_branch(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/sucursals');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/sucursals');
                $subtitle = " - Empresas - Nueva Sucursal";
                $this->visits('Empresas - Nueva Sucursal');
                $countries = Country::orderby('pa_nom', 'asc')->get();
                $em_rub = Categories::select('rub_cat')->distinct()->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.create_branch_new', ['subtitle' => $subtitle, 'em_rub' => $em_rub, 'countries' => $countries, 'business' => $business, 'get_business' => $get_business, 'act_business' => '1', 'menu_sucursal' => '1']);
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
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Editar Empresa";
                $this->visits('Empresas - Editar Empresa');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                $countries = Country::orderby('pa_nom', 'asc')->get();
                $em_rub = Categories::select('rub_cat')->distinct()->get();
                $em_rubs = Categories::where('rub_cat', $get_business->em_rub)->where('rub_nom', '!=', null)->get();
                return view('frontend.edit_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'em_rub' => $em_rub, 'em_rubs' => $em_rubs, 'countries' => $countries, 'act_business' => '1', 'menu_editar' => '1']);
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
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                if (LenderNotifications::where('pc_empid', $id)->count() == 0) {
                    LenderNotifications::create([
                        'pc_empid' => $id
                    ]);
                }
                $settings = LenderNotifications::where('pc_empid', $id)->first();
                $subtitle = " - Empresas - Notificaciones";
                $this->visits('Empresas - Notificaciones');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.notifications_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'settings' => $settings, 'act_business' => '1', 'menu_notifications' => '1']);
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
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                if (SettingsBusiness::where('emp_id', $id)->where('suc_id', '0')->where('pres_id', '0')->count() == 0) {
                    SettingsBusiness::create([
                        'emp_id' => $id
                    ]);
                }
                $settings = SettingsBusiness::where('emp_id', $id)->where('suc_id', '0')->where('pres_id', '0')->first();
                $subtitle = " - Empresas - Configuración";
                $this->visits('Empresas - Configuración');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.settings_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'settings' => $settings, 'act_business' => '1', 'menu_settings' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display reports
     * @return type
     */
    public function reports(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                if (ReportsBusiness::where('emp_id', $id)->count() == 0) {
                    ReportsBusiness::create([
                        'emp_id' => $id
                    ]);
                }
                $settings = ReportsBusiness::where('emp_id', $id)->first();
                $subtitle = " - Empresas - Reportes";
                $this->visits('Empresas - Reportes');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.reports_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'settings' => $settings, 'act_business' => '1', 'menu_report' => '1']);
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
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                if (ReportsBusiness::where('emp_id', $id)->count() == 0) {
                    ReportsBusiness::create([
                        'emp_id' => $id
                    ]);
                }
                $settings = ReportsBusiness::where('emp_id', $id)->first();
                $subtitle = " - Empresas - Enviar Reportes";
                $this->visits('Empresas - Enviar Reportes');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();


                if (Auth::guard('user')->User()->level == '2' && Auth::guard('user')->User()->rol == '1' && isset(Auth::guard('user')->User()->pres_id) && Auth::guard('user')->User()->pres_id != "" && Auth::guard('user')->User()->pres_id != "0") {

                    $get_lender = Lenders::find(Auth::guard('user')->User()->pres_id);
                    if (isset($get_lender) == 0)
                        return redirect::to('/prestadores');


                    $get_branch = Branch::find(Auth::guard('user')->User()->suc_id);
                    if (isset($get_branch) == 0)
                        return redirect::to('/prestadores');

                    if ($get_branch->suc_uscid != $this->getIdBusiness())
                        return redirect::to('/prestadores');


                    return view('frontend.send_reports_lender2', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'settings' => $settings, 'act_business' => '1', 'send_report' => '1', 'get_branch' => $get_branch, 'get_lender' => $get_lender, 'business']);
                } else {

                    return view('frontend.send_reports_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'settings' => $settings, 'act_business' => '1', 'send_report' => '1']);
                }
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display required_fields
     * @return type
     */
    public function required_fields(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Datos requeridos";
                $this->visits('Empresas - Datos requeridos');
                if (BusinessFields::where('mi_empid', $id)->orderby('mi_tipo', 'asc')->count() == 0) {
                    BusinessFields::insert([
                        'mi_empid' => $id
                    ]);
                }
                $inputs_add = BusinessFields::where('mi_empid', $id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.required_fields', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'inputs_add' => $inputs_add, 'act_business' => '1', 'menu_required' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display create mercado pago
     * @return type
     */
    public function create_mercado_pago(Request $request, $id) {

        try {
            Session()->flash('mp-create', 'No hay resultados');
            return redirect::to('/empresa/mercado-pago/' . $id);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display mercado_pago
     * @return type
     */
    public function mercado_pago(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Mercado Pago";
                $this->visits('Empresas - Mercado Pago');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.mercado_pago', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_business' => '1', 'menu_mercadopago' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display create mercado pago
     * @return type
     */
    public function renew_mercado_pago(Request $request) {

        try {

            $site = DB::table('tu_settingsmp')->where('id', '1')->first();

            $client_id = $site->client_id;
            $client_secret = $site->client_secret;


            $business = Business::find($request['id']);
            $refresh_token = $business->refresh_token;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/oauth/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "client_secret=" . $client_secret . "&grant_type=refresh_token&refresh_token=" . $refresh_token . "");
            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            $jsonData = json_decode($response, true);

            if (isset($jsonData['error'])) {


                return response()->json(["response" => "error", "msg" => $jsonData['message']]);
            } else {

                $created_at = date("Y-m-d");
                $expired_mp = date("Y-m-d", strtotime($created_at . "+ 6 month"));
                $access_token = $jsonData['access_token'];
                $public_key = $jsonData['public_key'];
                $refresh_token = $jsonData['refresh_token'];
                $business = Business::find($request['id']);
                $business->fill([
                    'access_token' => $access_token,
                    'public_key' => $public_key,
                    'refresh_token' => $refresh_token,
                    'expired_mp' => $expired_mp,
                ]);
                $business->save();

                return response()->json(["msg" => "updated"]);
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
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Obras Sociales";
                $this->visits('Empresas - Obras Sociales');
                $social_works = SocialWork::orderby('os_nomp', 'asc')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.social_work', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'social_works' => $social_works, 'act_business' => '1', 'menu_social_work' => '1']);
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
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Días y horarios de atención";
                $this->visits('Empresas - Días y horarios de atención');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.shedules_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_business' => '1', 'menu_shedules' => '1']);
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
    public function frame(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');

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
                $subtitle = " - Empresas - Turnonet en mi Website";
                $this->visits('Empresas - Turnonet en mi Website');
                $settings = Frame::where('emp_id', $get_business->em_id)->first();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.frame', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_business' => '1', 'menu_frame' => '1', 'settings' => $settings]);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display frame theme
     * @return type
     */
    public function frame_theme(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');

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
                $subtitle = " - Empresas - Turnonet en mi Website - Personalizar";
                $this->visits('Empresas - Turnonet en mi Website - Personalizar');
                $fonts = DB::table('fonts')->orderBy('name', 'asc')->get();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();

                $settings = Frame::where('emp_id', $get_business->em_id)->first();

                return view('frontend.frame_theme', ['subtitle' => $subtitle, 'fonts' => $fonts, 'get_business' => $get_business, 'business' => $business, 'act_business' => '1', 'menu_frame' => '1', 'settings' => $settings]);
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
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Días no laborables";
                $this->visits('Empresas - Días no laborables');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.not_working_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_business' => '1', 'menu_not_working' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display frame for speciality
     * @return type
     */
    public function frame_speciality(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');

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
                $speciality = Services::select('serv_id', 'serv_nom', 'url')->where('serv_empid', $id)->where('serv_tipo', '2')->where('serv_estado', '1')->groupBy('serv_nom')->get();
                if (count($speciality) == 0)
                    return redirect::to('/empresa/frame/' . $id);
                $subtitle = " - Empresas - Turnonet en mi Website";
                $this->visits('Empresas - Turnonet en mi Website');
                $settings = Frame::where('emp_id', $get_business->em_id)->first();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.frame_speciality', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'speciality' => $speciality, 'act_business' => '1', 'menu_frame' => '1', 'settings' => $settings]);
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
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Eliminar Empresa";
                $this->visits('Empresas - Eliminar Empresa');
                $shift = Shift::where('emp_id', $id)->where('tu_fec', '>=', date("Y-m-d"))->where('tu_estado', 'ALTA')->count();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.baja_business', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'shift' => $shift, 'act_business' => '1', 'menu_delete' => '1']);
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
    public function blacklist(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Lista Negra";
                $this->visits('Empresas - Lista Negra');
                $shift = Shift::where('emp_id', $id)->where('tu_fec', '>=', date("Y-m-d"))->where('tu_estado', 'ALTA')->count();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.blacklist', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'shift' => $shift, 'act_business' => '1', 'blacklist' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display admins
     * @return type
     */
    public function admins(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Administradores";
                $this->visits('Empresas - Administradores');
                $shift = Shift::where('emp_id', $id)->where('tu_fec', '>=', date("Y-m-d"))->where('tu_estado', 'ALTA')->count();
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.admins', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'shift' => $shift, 'act_business' => '1', 'menu_admin' => '1']);
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
    public function statistics(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0){
                    return redirect::to('/empresas');
                }
                if ($get_business->em_uscid != $this->getIdBusiness()){
                    return redirect::to('/empresas');
                }
                $subtitle = " - Empresas - Estadisticas";
                $this->visits('Empresas - Estadisticas');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                $init = date("Y") . "-" . date("m") . "-01";
                $end = date('Y-m-d');
                $shifts = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->count();
                $actives = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_estado', 'ALTA')
                        ->count();
                $cancel = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_estado', 'BAJA')
                        ->count();
                $overturn = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_st', '1')
                        ->count();
                $asistencia = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_asist', '1')
                        ->count();
                $ausencia = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_asist', '0')
                        ->count();
                $parcial = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_asist', '2')
                        ->count();
                $no_defined = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_asist', '3')
                        ->count();
                $days = Shift::
                        select('tu_fec', DB::raw('count(*) as total'))
                        ->where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->groupBy('tu_fec')
                        ->orderby('tu_fec', 'asc')
                        ->get();
                $hours = Shift::
                        select('tu_hora', DB::raw('count(*) as total'))
                        ->where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->groupBy('tu_hora')
                        ->orderby('tu_hora', 'asc')
                        ->get();
                $lenders = Shift::
                        select('pres_id', DB::raw('count(*) as total'))
                        ->where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->groupBy('pres_id')
                        ->offset(0)
                        ->limit(5)
                        ->orderby('total', 'desc')
                        ->get();
                return view('frontend.statistics',
                        ['subtitle' => $subtitle,
                            'get_business' => $get_business,
                            'business' => $business,
                            'days' => $days,
                            'hours' => $hours,
                            "lenders" => $lenders,
                            'shifts' => $shifts,
                            "overturn" => $overturn,
                            'actives' => $actives,
                            'asistencia' => $asistencia,
                            'ausencia' => $ausencia,
                            'parcial' => $parcial,
                            'no_defined' => $no_defined,
                            'cancels' => $cancel,
                            'init' => $init,
                            'end' => $end,
                            'act_business' => '1',
                            'menu_stadistica' => '1'
                ]);
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
    public function statistics_date(Request $request, $id, $init, $end) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/empresas');
                $subtitle = " - Empresas - Estadisticas";
                $this->visits('Empresas - Estadisticas');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();

                if ($init != '') {
                	$init = explode('-', $init);
                	if(isset($init[2]) && isset($init[1]) && isset($init[0])){
                		$init = $init[2] . "-" . $init[1] . "-" . $init[0];
                	}
                }
                if ($end != '') {
                	$end = explode('-', $end);
                	if(isset($end[2]) && isset($end[1]) && isset($end[0])){
                		$end = $end[2] . "-" . $end[1] . "-" . $end[0];
                	}

                }

                $shifts = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->count();
                $actives = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_estado', 'ALTA')
                        ->count();
                $cancel = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_estado', 'BAJA')
                        ->count();
                $overturn = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_st', '1')
                        ->count();
                $asistencia = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_asist', '1')
                        ->count();
                $ausencia = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_asist', '0')
                        ->count();
                $parcial = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_asist', '2')
                        ->count();
                $no_defined = Shift::
                        where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->where('tu_asist', '3')
                        ->count();
                $days = Shift::
                        select('tu_fec', DB::raw('count(*) as total'))
                        ->where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->groupBy('tu_fec')
                        ->orderby('tu_fec', 'asc')
                        ->get();
                $hours = Shift::
                        select('tu_hora', DB::raw('count(*) as total'))
                        ->where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->groupBy('tu_hora')
                        ->orderby('tu_hora', 'asc')
                        ->get();
                $lenders = Shift::
                        select('pres_id', DB::raw('count(*) as total'))
                        ->where('emp_id', $id)
                        ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                        ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                        ->groupBy('pres_id')
                        ->offset(0)
                        ->limit(5)
                        ->orderby('total', 'desc')
                        ->get();
                return view('frontend.statistics',
                        ['subtitle' => $subtitle,
                            'get_business' => $get_business,
                            'business' => $business,
                            'days' => $days,
                            'hours' => $hours,
                            "lenders" => $lenders,
                            'shifts' => $shifts,
                            "overturn" => $overturn,
                            'actives' => $actives,
                            'asistencia' => $asistencia,
                            'ausencia' => $ausencia,
                            'parcial' => $parcial,
                            'no_defined' => $no_defined,
                            'cancels' => $cancel,
                            'init' => $init,
                            'end' => $end,
                            'act_business' => '1']);
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
    public function lists() {
        try {
            $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_id', 'desc')->get();
            $json = array();
            foreach ($business as $rs) {

                $image = url('/') . '/img/empty.jpg';
                if ($rs->em_pfot != "") {
                    $image = "https://www.turnonet.com/fotos/empresas/" . $rs->em_pfot;
                }
                $lenders = Lenders::where('emp_id', $rs->em_id)->where('tmsp_estado', 'ALTA')->count();
                $shift = Shift::where('emp_id', $rs->em_id)->count();
                $branch = Branch::where('suc_empid', $rs->em_id)->where('suc_estado', 'ALTA')->count();
                $json[] = array(
                    "id" => $rs->em_id,
                    "image" => $image,
                    "lenders" => $lenders,
                    "shift" => $shift,
                    "branch" => $branch,
                    "name" => mb_substr(mb_convert_encoding($rs->em_nomfan, 'UTF-8', 'UTF-8'), 0, 40),
                    "email" => strtolower($rs->em_email),
                );
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
    public function update_business(Request $request) {
        try {
            $this->audit('Actualizar datos  de la empresa ' . $request['id']);
            if (isset($request['em_pfot'])) {


                $request->validate([
                    'em_pfot' => 'mimes:jpeg,png,jpg|required|file|max:40960'
                ]);
                
                $path = env('PATH_BUSINESS');
                $pic_name = $_FILES['em_pfot']['name'];
                $temp = $_FILES['em_pfot']['tmp_name'];
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
                $business = Business::find($request['id']);
                $business->fill([
                    'em_pfot' => $pic_new,
                ]);
                $business->save();
            }
            $business = Business::find($request['id']);
            $business->fill([
                'em_nomfan' => $request['em_nomfan'],
                'em_email' => $request['em_email'],
                'em_tel' => $request['em_tel'],
                'em_cont' => $request['em_cont'],
                'em_pais' => $request['em_pais'],
                'em_rub' => $request['em_rub'],
                'em_rubs' => $request['em_rubs'],
                'em_domleg' => $request['em_domleg']
            ]);
            $business->save();

            $title = ucwords(mb_strtolower($request['em_nomfan']));
            $settings = Frame::where('emp_id', $request['id'])->first();
            $settings->fill([
                'title' => "Turnonet - " . $title,
                'url' => $this->updateUrl(trim($business->em_id . " " . $request['em_nomfan'])),
            ]);
            $settings->save();
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display create business
     * @return type
     */
    public function create_business(Request $request) {
        try {
            $pic_new = '';
            if (isset($request['em_pfot'])) {


                $request->validate([
                    'em_pfot' => 'mimes:jpeg,png,jpg|required|file|max:40960'
                ]);


                $path = env('PATH_BUSINESS');
                $pic_name = $_FILES['em_pfot']['name'];
                $temp = $_FILES['em_pfot']['tmp_name'];
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
            Business::create([
                'em_uscid' => $this->getIdBusiness(),
                'em_nomfan' => $request['em_nomfan'],
                'em_email' => $request['em_email'],
                'em_tel' => $request['em_tel'],
                'em_cont' => $request['em_cont'],
                'em_pais' => $request['em_pais'],
                'em_rub' => $request['em_rub'],
                'em_rubs' => $request['em_rubs'],
                'em_domleg' => $request['em_domleg'],
                'em_valcod' => $this->getPassword(),
                'em_pfot' => $pic_new,
            ]);
            $business = Business::all();
            $business = $business->last();
            SettingsBusiness::create([
                'emp_id' => $business->em_id
            ]);
            ReportsBusiness::create([
                'emp_id' => $business->em_id
            ]);
            LenderNotifications::create([
                'pc_empid' => $business->em_id
            ]);

            $title = ucwords(mb_strtolower($request['em_nomfan']));
            Frame::create([
                'emp_id' => $business->em_id,
                'font_1' => '1',
                'font_2' => '2',
                'font_3' => '3',
                'title' => "Turnonet - " . $title,
                'url' => $this->updateUrl(trim($business->em_id . " " . $request['em_nomfan'])),
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
            $this->audit('Registro de la empresa ' . $business->em_id);
            return response()->json(["msg" => "updated", "id" => $business->em_id]);
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
            $this->audit('Actualización de configuración de notificaciones de la empresa ');
            $notifications = LenderNotifications::find($request['id']);
            $notifications->fill([
                'pc_mailr' => $request['pc_mailr'],
                'pc_msg' => $request['pc_msg'],
                'pc_mailc' => $request['pc_mailc'],
                'pc_mailn' => $request['pc_mailn'],
            ]);
            $notifications->save();
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display update reports
     * @return type
     */
    public function update_reports(Request $request) {
        try {
            $this->audit('Actualización de configuración de reportes de la empresa ');
            if (isset($request['rep_recemp'])) {
                $rep_recemp = '1';
            } else {
                $rep_recemp = '0';
            }
            if (isset($request['rep_recsuc'])) {
                $rep_recsuc = '1';
            } else {
                $rep_recsuc = '0';
            }
            if (isset($request['rep_recpre'])) {
                $rep_recpre = '1';
            } else {
                $rep_recpre = '0';
            }
            $reports = ReportsBusiness::find($request['id']);
            $reports->fill([
                'rep_hora' => $request['rep_hora'],
                'rep_recsuc' => $rep_recsuc,
                'rep_recpre' => $rep_recpre,
                'rep_recemp' => $rep_recemp,
                'rep_type' => $request['rep_type']
            ]);
            $reports->save();
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
            $this->audit('Actualización de configuración de la empresa ' . $request['em_id']);
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

             if (isset($request['reminder_1'])) {
                $reminder_1 = '1';
            } else {
                $reminder_1 = '0';
            }

             if (isset($request['reminder_5'])) {
                $reminder_5 = '1';
            } else {
                $reminder_5 = '0';
            }

            
            if ($cf_tcan_all == '1') {

                SettingsBusiness::where('emp_id', $request['em_id'])->update([
                    'cf_tcan' => $request['cf_tcan'],
                ]);
            }
            if ($cf_daysp_all == '1') {

                SettingsBusiness::where('emp_id', $request['em_id'])->update([
                    'cf_daysp' => $request['cf_daysp'],
                ]);
            }

            if ($cf_days_all == '1') {

                SettingsBusiness::where('emp_id', $request['em_id'])->update([
                    'cf_days' => $request['cf_days'],
                ]);
            }
            $date = $request['hours'] . ":" . $request['minutes'] . ":00";
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
            $business = Business::find($request['em_id']);
            $business->fill([
                'em_presxag' => $request['em_presxag'],
                'reminder_5'=>$reminder_5,
                'reminder_1'=>$reminder_1,
                'shift_user'=>$request['shift_user'],
                'ip_user'=>$request['ip_user']
            ]);
            $business->save();
            Artisan::call('optimize:clear');

            return response()->json(["msg" => "updated", "clear" => Artisan::output()]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display update theme
     * @return type
     */
    public function update_theme(Request $request) {
        try {

            if (isset($request['footer'])) {
                $footer = '1';
            } else {
                $footer = '0';
            }

            if (isset($request['header'])) {
                $header = '1';
            } else {
                $header = '0';
            }

            if (isset($request['marca'])) {
                $marca = '1';
            } else {
                $marca = '0';
            }
            if (isset($request['name'])) {
                $name = '1';
            } else {
                $name = '0';
            }
            if (isset($request['searchbar'])) {
                $searchbar = '1';
            } else {
                $searchbar = '0';
            }

            if ($request['url_oll'] != $request['url']) {

                if (Frame::where('url', $request['url'])->count() != 0) {

                    return response()->json(["msg" => "error"]);
                }
            }

            $settings = Frame::find($request['id']);
            $settings->fill([
                'font_1' => $request['font_1'],
                'font_2' => $request['font_2'],
                'font_3' => $request['font_3'],
                'color_1' => $request['color_1'],
                'color_2' => $request['color_2'],
                'color_3' => $request['color_3'],
                'color_4' => $request['color_4'],
                'color_5' => $request['color_5'],
                'color_6' => $request['color_6'],
                'color_7' => $request['color_7'],
                'color_8' => $request['color_8'],
                'color_9' => $request['color_9'],
                'name' => $request['name'],
                'title' => $request['title'],
                'url' => trim($request['url']),
                'keywords' => $request['keywords'],
                'description' => $request['description'],
                'searchbar' => $searchbar,
                'footer' => $footer,
                'header' => $header,
                'marca' => $marca,
                'style' => $request['style']
            ]);
            $settings->save();

            $this->audit('Actualización del tema de la empresa ' . $request['em_id']);

            return response()->json(["msg" => "updated"]);
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
            $this->audit('Dar de baja a la empresa ' . $request['id']);
            if (isset($request['turnos'])) {
                $status_event = '0';
                $shift = Shift::where('emp_id', $request['id'])
                        ->where('tu_fec', '>=', date("Y-m-d"))
                        ->update([
                    'tu_estado' => 'BAJA'
                ]);
            }
            $business = Business::find($request['id']);
            $business->fill([
                'em_estado' => 'BAJA'
            ]);
            $business->save();


            $branchs = Branch::where('suc_empid', $request['id'])->get();

            foreach ($branchs as $row) {


                $branch = Branch::find($row->suc_id);
                $branch->fill(['suc_estado' => 'BAJA']);
                $branch->save();


                $lenders = Lenders::where('suc_id', $row->suc_id)->get();
                foreach ($lenders as $rs) {

                    $lender = Lenders::find($rs->tmsp_id);
                    $lender->fill([
                        'tmsp_estado' => 'BAJA'
                    ]);
                    $lender->save();
                }
            }
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
            $this->audit('Actualizar las obras sociales de la  empresa ' . $request['id']);
            WorkSocialBusiness::where('eob_empid', $request['id'])->delete();
            if(is_array($request['social'])){

                for ($i = 0; $i < count($request['social']); $i++) {
                WorkSocialBusiness::insert([
                    'eob_empid' => $request['id'],
                    'eob_obid' => $request['social'][$i]
                ]);
            }

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
            Shedules::where('lab_empid', $request['id'])->where('lab_sucid', 0)->where('lab_presid', 0)->delete();
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
                        'lab_empid' => $request['id'],
                        'lab_sucid' => 0,
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
            $this->audit('Actualización de configuración de horarios de la empresa ' . $request['id']);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display update required
     * @return type
     */
    public function update_required(Request $request) {
        try {
            BusinessFields::where('mi_empid', $request['id'])->delete();
            for ($i = 0; $i < count($request['input']); $i++) {
                if ($request['input'][$i] != "") {
                    $mi_ob = 0;
                    $mi_tipofield = 0;
                    $field_report = 0;
                    if (isset($request['op_' . $i])) {
                        $mi_ob = 1;
                    }
                    if (isset($request['report_' . $i])) {
                        $field_report = 1;
                    }
                    if ($i > 6) {
                        if (isset($request['optipo_' . $i])) {
                            $mi_tipofield = $request['optipo_' . $i];
                        }
                        $input = $i;
                        BusinessFields::insert([
                            'mi_empid' => $request['id'],
                            'mi_sucid' => 0,
                            'mi_presid' => 0,
                            "mi_ob" => $mi_ob,
                            "mi_ord" => $input + 2,
                            "mi_tipofield" => $mi_tipofield,
                            "field_report" => $field_report,
                            "mi_gentxt" => $request['input'][$i],
                            'mi_field' => $input + 2,
                        ]);
                    } else {
                        $field = DB::table('tu_obfield')->where('obf_id', $request['input'][$i])->first();
                        if (isset($field) != 0) {
                            if (BusinessFields::where('mi_empid', $request['id'])->where('mi_sucid', 0)->where('mi_presid', 0)->where('mi_field', $request['input'][$i])->count() == 0) {
                                if ($request['input'][$i] == 3) {
                                    if (isset($request['social'])) {
                                        $mi_tipofield = $request['social'];
                                    } else {
                                        $mi_tipofield = 0;
                                    }
                                } else {
                                    $mi_tipofield = 0;
                                }
                                BusinessFields::insert([
                                    'mi_empid' => $request['id'],
                                    'mi_sucid' => 0,
                                    'mi_presid' => 0,
                                    "mi_ob" => $mi_ob,
                                    "mi_ord" => $field->obf_ord,
                                    "field_report" => $field_report,
                                    "mi_tipofield" => $mi_tipofield,
                                    'mi_field' => $request['input'][$i],
                                ]);
                            }
                        }
                    }
                }
            }
            $this->audit('Actualización de campos requeridos de la empresa ' . $request['id']);
            return response()->json(["msg" => "updated"]);
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
     * Get password
     * @param type $length
     * @param type $uc
     * @param type $n
     * @param type $sc
     * @return boolean
     */
    public function getPassword($length = 10, $uc = TRUE, $n = TRUE, $sc = FALSE) {
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

    public function getIdBusiness() {

        if (Auth::guard('user')->User()->level == 1) {
            return Auth::guard('user')->User()->us_id;
        } else {

            $get_business = Business::find(Auth::guard('user')->User()->emp_id);
            return $get_business->em_uscid;
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TuMail;
use App\Mail\TuMailBtn;
use App\UsersApp;
use App\Activities;
use App\Visits;
use App\Shift;
use App\Services;
use App\Branch;
use App\Directory;
use App\Lenders;
use App\Business;
use App\ClientsCustomization;
use App\BusinessFields;
use App\ServicesWidgets;
use Route;
use Mail;
use Redirect;
use Session;
use Cache;
use DB;
use App\Customers;
use App\Faqs;
use App\Widgets;
use App\Plans;
use Auth;
use Log;
use App\Http\Requests;

class FrontendController extends Controller {

    /**
     * Display index
     * @return type
     */
    public function index() {

        try {
            $customers = Customers::orderBy('status', 'asc')->get();
            $widgets = Widgets::orderBy('position', 'asc')->get();
            $faqs = Faqs::orderBy('position', 'asc')->get();
             $plans = Plans::orderBy('position', 'asc')->get();
             $services = ServicesWidgets::orderBy('position', 'asc')->get();
            return view('landing.home', ['customers' => $customers, 'widgets' => $widgets, 'faqs' => $faqs,'plans'=>$plans,'services'=>$services]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function index_2(Request $request){

 //Log::info($request->url());
  //Log::info($request->all());
 //Log::info($request->headers->all());


    }

     public function index_3(Request $request,$item1,$item2,$item3,$item4,$item5,$item6,$item7){

//Log::info($request->url());
 //Log::info($request->headers->all());

    }

    /**
     * Display sigin
     * @return type
     */
    public function sigin() {
        try {
            if (Auth::guard('user')->guest()) {
                $subtitle = " - Iniciar sesión";
                $this->visits('Iniciar sesión');
                return view('frontend.signin', ['subtitle' => $subtitle]);
            } else {
                return Redirect::to('escritorio');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * store_signin
     * @param Request $request
     * @return type
     */
    public function store_signin(Request $request) {
        try {
            /**
             * Validate request with ajax
             */
            if ($request->ajax()) {




                $user = UsersApp::where('us_mail', mb_convert_encoding($request['email'], 'UTF-8', 'UTF-8'))->where('us_contra', md5($request['password']))->first();
                if (isset($user) != 0) {
                    if ($user->us_esta == 'PENDIENTE') {

                         $code = $this->getPassword();
                    $user->fill([
                     'us_valicode' => $code,
                    ]);
                    $user->save();

                   
                    $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))) . '
                        <br/>
                        Le informamos a través de este correo electrónico que debes activar tu cuenta haciendo es el siguiente enlace:<br />';
                    $title = 'Activa tu cuenta';
                    $btn = url('/') . '/activar-cuenta/' . $code;
                        Mail::to($user->us_mail, $user->us_nom)->send(new TuMailBtn('email_single_act', $title, $content, $btn));
                        return response()->json(["response" => "inactive"]);
                    }
                    if ($user->us_esta == 'ALTA') {
                        Auth::guard('user')->login($user);
                        $this->audit('Inicio de sesión', $user->us_id);
                        if ($user->date_new == "0000-00-00") {

                            $user_log = UsersApp::find($user->us_id);
                            $user_log->fill([
                                'date_new' => date("Y-m-d"),
                            ]);
                            $user_log->save();
                        }

                        return response()->json(["response" => "true"]);
                    } else {
                        return response()->json(["response" => "invalid"]);
                    }
                    return response()->json(["response" => "true"]);
                } else {
                    return response()->json(["response" => "false"]);
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display send_code
     * @return type
     */
    public function send_code() {
        try {
            if (Auth::guard('user')->guest()) {
                $subtitle = " - Enviar código";
                $this->visits('Enviar código');
                return view('frontend.send_code', ['subtitle' => $subtitle]);
            } else {
                return Redirect::to('escritorio');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_register(Request $request) {
        try {

            $user = UsersApp::where('us_mail', mb_convert_encoding($request['email'], 'UTF-8', 'UTF-8'))->first();
            if (isset($user) != 0) {
                if ($user->us_esta == 'PENDIENTE') {

                    $code = $this->getPassword();
                    $user->fill([
                     'us_valicode' => $code,
                    ]);
                    $user->save();

                   
                    $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))) . '
                        <br/>
                        Le informamos a través de este correo electrónico que debes activar tu cuenta haciendo es el siguiente enlace:<br />';
                    $title = 'Activa tu cuenta';
                    $btn = url('/') . '/activar-cuenta/' . $code;
                    Mail::to($user->us_mail, $user->us_nom)->send(new TuMailBtn('email_single_act', $title, $content, $btn));
                    return response()->json(["msg" => "inactive"]);
                }
                if ($user->us_esta == 'ALTA') {
                    return response()->json(["msg" => "error"]);
                }
            } else {

                $password = $request['password'];
                $code = $this->getPassword();

                UsersApp::create([
                'us_nom' => $request['name'],
                'us_mail' => $request['email'],
                'us_contra' => md5($password),
                'us_recon' => $password,
                'us_esta' => 'PENDIENTE',
                'us_altfec' => date("Y-m-d"),
                'us_valicode' => $code,
                'us_membresia' => '1',
                'status' => '1',
                'type' => '1',
                'password' => $request['password'],
                ]);
               


                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($request['name'], 'UTF-8', 'UTF-8'))) . '.<br/>
              <br/>
              Su registro en la plataforma ha sido procesado con éxito. Los datos que hemos recibido son los siguientes:<br />
              <br />
              <strong>Nombre y apellido:</strong>  ' . $request['name'] . '<br/>
              <strong>Correo electrónico:</strong>  ' . $request['email'] . '<br/>
              <strong>Contraseña:</strong>  ' . $password . '<br/>
              Debes activar tu cuenta haciendo es el siguiente enlace:<br>';

                $title = 'Registro exitoso';
                $btn = url('/') . '/activar-cuenta/' . $code;
                Mail::to($request['email'], $request['name'])->send(new TuMailBtn('email_single_act', $title, $content, $btn));

                 return response()->json(["msg" => "success"]);
            }

        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Store support
     * @param Request $request
     * @return type
     */
    public function store_message(Request $request) {
        try {
            if ($request->ajax()) {


                $isHuman = $this->validate_captcha_google($request['captcha']);
                if ($request['captcha'] == '') {
                    return response()->json(["response" => "no-catpcha"]);
                }
                if ($isHuman) {

                    $subject = $request['subject'];
                    $message = $request['message'];



                    $content = 'Hola, Administrador Turnonet.<br/>
            <br/>
            Le informamos a través de este correo electrónico que tiene una nueva consulta por revisar. Los datos que hemos recibido son los siguientes:<br />
            <br />
            <strong>Nombre y Apellido:</strong>  ' . $request['name'] . '<br/>
            <strong>Correo electrónico:</strong>  ' . $request['email'] . '<br/>
            <strong>Asunto:</strong>  ' . $subject . '<br/>
            <strong>Mensaje:</strong>  ' . $message . '<br/>';
                    $title = "Nuevo Mensaje";

                    Mail::to('alfredo@santabros.com.ar', 'Turnonet')->send(new TuMail('email', $title, $content));


                    return response()->json(["msg" => "Contacto Enviado"]);
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * store_code
     * @param Request $request
     * @return type
     */
    public function store_code(Request $request) {
        try {
            /**
             * Validate request with ajax
             */
            if ($request->ajax()) {
                $email = $request['email'];
                if (UsersApp::where('us_mail', $email)->count() != 0) {
                    $code = $this->getPassword();
                    $user = UsersApp::where('us_mail', $email)->first();
                    $this->audit('Solicitud de código de validación', $user->us_id);
                    $user = UsersApp::where('us_mail', $email)->first();
                    $user->fill(['us_valicode' => $code]);
                    $user->save();
                    $user = UsersApp::where('us_mail', $email)->first();
                    $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))) . '
                    <br/>
                    Le informamos a través de este correo electrónico que sus código de validación es el siguiente:<br />
                    <br />
                    <strong>Código:</strong>  ' . $code . '<br/>';
                    $title = 'Código de Validación';
                    Mail::to($user->us_mail, mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))->send(new TuMail('email_single', $title, $content));
                    return response()->json(["response" => "true", "code" => $code]);
                } else {
                    return response()->json(["response" => "false", "message" => "Este correo electrónico no existe"]);
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display reset_password
     * @return type
     */
    public function reset_password() {
        try {
            if (Auth::guard('user')->guest()) {
                $subtitle = " - Cambiar clave";
                $this->visits('Cambiar clave');
                return view('frontend.reset_password', ['subtitle' => $subtitle]);
            } else {
                return Redirect::to('escritorio');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display active account
     * @return type
     */
    public function active_account($code) {
        try {
            if (Auth::guard('user')->guest()) {
                if (UsersApp::where('us_valicode', $code)->count() != 0) {
                    $user = UsersApp::where('us_valicode', $code)->first();
                    $user->fill([
                        'us_esta' => 'ALTA'
                    ]);
                    $user->save();
                    $active = '1';
                } else {
                    $active = '2';
                }
                $subtitle = " - Iniciar sesión";
                $this->visits('Iniciar sesión');
                return view('frontend.active_account', ['subtitle' => $subtitle, 'active' => $active]);
            } else {
                return Redirect::to('escritorio');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function email() {

        $content = 'Hola, Alfredo.<br>
Te informamos a través de este correo electrónico que el turno que has solicitado para el Miércoles 31 de Diciembre del 1969 a las 09:00 AM ha sido agendado con éxito.<br>
DATOS DEL TURNO:
Cliente: ALFREDO
Código: 1847213530581115030
Empresa: CONSULTORIO TURNONET
Prestador: MARIELA SUAREZ
Dirección: Carlos H. Perette y Calle 10 `Barrio 31`
<br>Valor de la Consulta:

Particulares $950

SOCIOS APCJ $650

IOSFA 850

OSECAC $700 con bono de consulta

PAMI $800


Sin cargo:

BRAMED
CAJA NOTARIAL
CABOTAJE - SAN NICOLAS
CASA
COMEI
DOCTHOS
ECLESIASTICA SAN PEDRO
FEDERADA SALUD Grupo 2
GALENO
JERARQUICOS SALUD
MEDICUS
MEDIFE
OMINT
OSAPM (Agentes Propaganda Medica)
OSCOEMA - SANTE MEDICAL
OSDE
OSDIPP
OSPE
OSPEDYC
OSPEGAP
OSSEG
SANCOR (Prevencion Salud)
SWISS MEDICAL
UNS


Abonan $300: 

ACA SALUD
AMFFA
AMSTERDAN
GERDANNA


Abonan $400:

AUSTRAL OMI/UNION PERSONAL
DOSEM
ESCRIBANOS PROV.
IOMA
OSPBB
OSPIM
OSTEL
PODER JUDICIAL
UNION PERSONAL


Abonan $500:

AMEBPBA (Banco Provincia)
DIBPFA
OSFATLYF (Luz y Fuerza)
OSFFENTOS
OSMATA
OSPIA
SOMU


Abonan $600:

ANDAR
DIBPFA
IOSE
PERSONAL DE ESCRIBANIAS
OSARPYH
OSCEARBA
OSDOP
OSETYA
OSPATCA
OSPEGYPE
OSPERYHRA
OSPIF
OSPRERA
TECHINT
TV SALUD
UTA
VETERANOS DE GUERRA


PARTICULARES: $950

Importante:

PERSONAL DE FARMACIA: POR GRAVES IRREGULARIDADES EN LOS PAGOS POR PARTE DE LA OBRA SOCIAL DEBERAN ABONAR COMO PARTICULAR

Si su Obra Social se encuentra cortada, debera abonar como particular.';

        $title = 'Registro Exitoso';





echo "Hola";
    Mail::to('alfredo.geraldo21@gmail.com', 'Santiago Cabral')->send(new TuMail('email', $title, $content));
    }

    /**
     * store_reset
     * @param Request $request
     * @return type
     */
    public function store_reset(Request $request) {
        try {
            /**
             * Validate request with ajax
             */
            if ($request->ajax()) {
                $code = $request['code'];
                $password = $request['password'];
                if (UsersApp::where('us_valicode', $code)->count() != 0) {
                    $user = UsersApp::where('us_valicode', $code)->first();

                    $us_id=$user->us_id;
                    $name=$user->us_nom;
                    $email=$user->us_mail;

                    $user = UsersApp::where('us_valicode', $code)->first();
                    $user->fill([
                        'us_contra' => md5($password),
                        'us_recon' => $password,
                        'us_valicode' => ''
                    ]);
                    $user->save();
                    
                    $this->audit('Actualización de contraseña', $us_id);
                    $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($name, 'UTF-8', 'UTF-8'))) . '
                    <br/>
                    Le informamos a través de este correo electrónico que sus datos de acceso han sido actualizados con éxito. Los datos que hemos recibido son los siguientes:<br />
                    <strong>Contraseña:</strong>  ' . $password . '<br/>';
                    $title = 'Datos Actualizados';
                    Mail::to($email, mb_convert_encoding($name, 'UTF-8', 'UTF-8'))->send(new TuMail('email_single', $title, $content));
                    return response()->json(["response" => "true"]);
                } else {
                    return response()->json(["response" => "false", "message" => "Este código es inválido"]);
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function report_email($type, $rep_type, $id_sq, $emp_id, $date_reports, $content) {

        try {
            if ($type == '1') {
                $shifts = Shift::
                        where('emp_id', $id_sq)
                        ->where('tu_estado', 'ALTA')
                        ->where('tu_fec', date("Y-m-d", strtotime($date_reports)))
                        ->orderBy('suc_id', 'ASC')
                        ->orderBy('pres_id', 'ASC')
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            }
            if ($type == '2') {
                $shifts = Shift::
                        where('suc_id', $id_sq)
                        ->where('tu_estado', 'ALTA')
                        ->where('tu_fec', date("Y-m-d", strtotime($date_reports)))
                        ->orderBy('pres_id', 'ASC')
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            }
            if ($type == '3') {
                $shifts = Shift::
                        where('pres_id', $id_sq)
                        ->where('tu_estado', 'ALTA')
                        ->where('tu_fec', date("Y-m-d", strtotime($date_reports)))
                        ->orderBy('tu_hora', 'ASC')
                        ->get();
            }
            $json = array();

            if (count($shifts) == 0) {
                return Redirect::to('iniciar-sesion');
            }
            foreach ($shifts as $rs) {
                $services = '';
                if ($rs->tu_servid != null) {
                    if (substr_count($rs->tu_servid, '-') <= 0) {
                        $service_id = $rs->tu_servid;
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return Services::where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= $get_service->serv_nom;
                        }
                    } else {
                        for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                            $service = explode('-', $rs->tu_servid);
                            $service_id = $service[$i];
                            $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                        return Services::where('serv_id', $service_id)->first();
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
                            return Business::where('em_id', $business_id)->first();
                        });
                $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                            return Branch::where('suc_id', $branch_id)->first();
                        });
                $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                            return Lenders::where('tmsp_id', $lender_id)->first();
                        });
                $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                            return Directory::where('us_id', $user_id)->where('emp_id', $business_id)->first();
                        });
                if (isset($lender) != 0 && isset($user) != 0) {
                    $detail = ClientsCustomization::where('usm_turid', $rs->tu_id)->first();
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




            $inputs_add = BusinessFields::where('mi_empid', $emp_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();


            return view('email.email_reports', ['title' => $title, 'content' => $this->setUrl($content), 'data' => $json, 'inputs_add' => $inputs_add, 'rep_type' => $rep_type]);
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
    public function audit($activity, $id) {
        try {
            Activities::create([
                'activity' => $activity,
                'ip' => $this->getIp(),
                'id_user' => $id
            ]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Open account user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function open_account($id) {

        try {
            $user = UsersApp::find($id);
            if (isset($user) == 0)
                return Redirect::to('iniciar-sesion');

            Auth::guard('user')->login($user);
            $this->audit('Inicio de sesión', $user->us_id);
            if ($user->date_new == "0000-00-00") {

                $user_log = UsersApp::find($user->us_id);
                $user_log->fill([
                    'date_new' => date("Y-m-d"),
                ]);
                $user_log->save();
            }

            return redirect::to('/escritorio');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function setUrl($url) {

        $value = str_replace("-", '/', $url);
        $value = str_replace("+", ' ', $value);
        return $value;
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


    public function callbackmp(){


    

         try {

            $id_business= Session::get('id_empresa');

            $site = DB::table('tu_settingsmp')->where('id', '1')->first();

            $client_id = $site->client_id;
            $client_secret = $site->client_secret;


            $code=$_REQUEST['code'];
            $redirect_uri='https://www.turnonet.com/callbackmp';
    //INITIALIZE CURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/oauth/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

    // SET POST 

            curl_setopt($ch, CURLOPT_POSTFIELDS, "client_secret=".$client_secret."&grant_type=authorization_code&code=".$code."&redirect_uri=".$redirect_uri."");

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
                $business = Business::find($id_business);
                $business->fill([
                    'access_token' => $access_token,
                    'public_key' => $public_key,
                    'refresh_token' => $refresh_token,
                    'expired_mp' => $expired_mp,
                ]);
                $business->save();

Session()->flash('mp-create', 'Registro Exitoso');
                return redirect('empresa/mercado-pago/'.$id_business);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;






    }

}

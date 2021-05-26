<?php

namespace App\Http\Controllers;

use App\Mail\TuMail;
use Illuminate\Http\Request;
use Route;
use App\UsersApp;
use App\UsersPersonalData;
use App\Communications;
use App\Notifications;
use App\Activities;
use App\Visits;
use App\State;
use App\City;
use App\Country;
use Redirect;
use DB;
use Mail;
use Session;
use Auth;
use URL;
use App\Http\Requests;

class AccountController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('user');
        $this->middleware('VerifyShift', ['only' => ['upaccount']]);
    }

    /**
     * Display dashboard
     * @return type
     */
    public function index(Request $request) {
        try {
            if (!Auth::guard('user')->guest()) {

                $subtitle = " - Escritorio";
                $this->visits('Escritorio');

                $personalData=UsersPersonalData::where('ud_usid',Auth::guard('user')->User()->us_id)->count();

                return view('frontend.dashboard', ['subtitle' => $subtitle,'personalData'=>$personalData]);
            } else {

                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display profile
     * @return type
     */
    public function profile(Request $request) {
        try {
            if (!Auth::guard('user')->guest()) {


                $subtitle = " - Mi Perfil";
                $this->visits('Mi Perfil');

                $personalData=UsersPersonalData::where('ud_usid',Auth::guard('user')->User()->us_id)->count();

                return view('frontend.profile', ['subtitle' => $subtitle,'personalData'=>$personalData]);
            } else {

                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display up account
     * @return type
     */
    public function upaccount(Request $request) {
        try {


            $subtitle = " - Actualizar cuenta";
          
            $user= UsersPersonalData::where('ud_usid',Auth::guard('user')->User()->us_id)->first();
            $this->visits('Actualizar cuenta');
            $countries = Country::orderby('pa_nom','asc')->get();
            return view('frontend.upaccount', ['subtitle' => $subtitle, 'user' => $user,'countries'=>$countries]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Update data
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function store_update(Request $request) {
        try {
        
        	$email = $request['email'];
        	$country = $request['country'];
        	$province = $request['province'];
        	$location= $request['location'];
        	$dir= $request['dir'];
        	$postalc=$request['postalc'];
        	$phone=$request['phone'];
        	$cellphone=$request['cellphone'];

            $date = $request['year'] . "-" . $request['month'] . "-" . $request['day'];

        	$this->audit('Actualización de datos de la cuenta');

        	if(UsersPersonalData::where('ud_usid',Auth::guard('user')->User()->us_id)->count()!=0){

        		$user= UsersPersonalData::where('ud_usid',Auth::guard('user')->User()->us_id)->update([
                    'ud_emalt' => $email,
                    'ud_pres' => $country,
                    'ud_prov' => $province,
                    'ud_locbar' =>$location,
                    'ud_dire' => $dir,
                    'ud_cp' =>   $postalc,
                    'ud_tel' =>  $phone,
                    'ud_cel' =>  $cellphone,
                    'ud_fnac' => $date
                ]);
        		

        	}else{

        		UsersPersonalData::create([
        			'ud_usid'=>Auth::guard('user')->User()->us_id,
        			'ud_emalt' => $email,
        			'ud_pres' => $country,
        			'ud_prov' => $province,
        			'ud_locbar' =>$location,
        			'ud_dire' => $dir,
        			'ud_cp' =>   $postalc,
        			'ud_tel' =>  $phone,
        			'ud_cel' =>  $cellphone,
        			'ud_fnac' => $date
        		]);

        	}
            $country_mail='';
            $location_mail='';
            $province_mail='';
            $get_loc=DB::table('tu_locbar')->where('loc_id',$location)->first();
            $get_prov=DB::table('tu_prov')->where('prov_id',$province)->first();
            $get_country=Country::where('pa_id',$country)->first();
            if(isset($get_loc)!=0){
                $location_mail=$get_loc->loc_nom;
            }
            if(isset($get_prov)!=0){
                $province_mail=$get_prov->prov_nom;
            }
            if(isset($get_country)!=0){
                $country_mail=$get_country->pa_nom;
            }




             $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))) . '
              <br/>
              Le informamos a través de este correo electrónico que los datos de su cuenta han sido actualizados con éxito. Los datos que hemos recibido son los siguientes:<br />
              <br />
              <strong>Email de Recuperación:</strong>  ' . $email . '<br/>
              <strong>País:</strong>  ' .$country_mail. '<br/>
              <strong>Provincia:</strong>  ' . $province_mail . '<br/>
              <strong>Localidad:</strong>  ' . $location_mail . '<br/>
              <strong>Dirección:</strong>  ' . $dir . '<br/>
              <strong>Código Postal:</strong>  ' . $postalc . '<br/>
              <strong>Teléfono:</strong>  ' . $phone . '<br/>
              <strong>Celular:</strong>  ' . $cellphone . '<br/>';

              $title = 'Datos Actualizados';


              Mail::to(Auth::guard('user')->User()->us_mail, mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))->send(new TuMail('email_single',$title, $content)); 

            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display view_communication
     * @return type
     */
    public function view_communication() {
        try {

            $url = explode("/", $_SERVER["REQUEST_URI"]);
            $post = Communications::where('url', $url[1])->first();

            $subtitle = " - ".$post->title;
            $this->visits($post->title);
            return view('frontend.view_communication', ['subtitle' => $subtitle,'post'=>$post]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display terminos
     * @return type
     */
    public function terminos() {
        try {
            $subtitle = " - Términos y Condiciones";
            $this->visits('Términos y Condiciones');
            return view('frontend.terminos', ['subtitle' => $subtitle]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display politicas
     * @return type
     */
    public function politicas() {
        try {
            $subtitle = " - Política de Privacidad";
            $this->visits('Política de Privacidad');
            return view('frontend.politicas', ['subtitle' => $subtitle]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display up password
     * @return type
     */
    public function uppassword(Request $request) {
        try {



            $subtitle = " - Actualizar contraseña";
            $this->visits('Actualizar contraseña');
            return view('frontend.uppassword', ['subtitle' => $subtitle]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Update  password
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function store_update_password(Request $request) {
        try {
            $password = $request['password'];


            $user = UsersApp::find(Auth::guard('user')->User()->us_id);
            $user->fill([
                'us_contra' => md5($password),
                'us_recon' => $password,
            ]);
            $user->save();


             $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))) . '
              <br/>
              Le informamos a través de este correo electrónico que sus datos de acceso han sido actualizados con éxito. Los datos que hemos recibido son los siguientes:<br />
              <br />
              <strong>Contraseña:</strong>  ' . $password . '<br/>';

              $title = 'Datos Actualizados';


              Mail::to(Auth::guard('user')->User()->us_mail, mb_convert_encoding(Auth::guard('user')->User()->us_nom, 'UTF-8', 'UTF-8'))->send(new TuMail('email_single',$title, $content));

                $this->audit('Actualización de contraseña');

            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Logout
     * @return type
     */
    public function logout() {
        try {
            if (!Auth::guard('user')->guest()) {

                $this->audit('Cerrar sesión');
                Auth::guard('user')->logout();
            }
            return Redirect::to('iniciar-sesion');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * turnonet_v3
     * @return type
     */
    public function turnonet_v3() {
        try {
            if (!Auth::guard('user')->guest()) {


                return response()->json(["email" =>Auth::guard('user')->User()->us_mail,"password"=>Auth::guard('user')->User()->us_recon]);

              
            }else{
               return Redirect::to('iniciar-sesion');
           }
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

     public function delete_noty(Request $request) {
        try {
            Notifications::destroy($request['id']);
            return response()->json(["msg" => "eliminado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function delete_noty_type(Request $request) {
        try {
            Notifications::where('us_id',Auth::guard('user')->User()->us_id)->where('tipo','1')->delete();
            return response()->json(["msg" => "eliminado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function delete_noty_active(Request $request) {
        try {

            Notifications::where('us_id',Auth::guard('user')->User()->us_id)->update([
                        'status' =>'0'
                    ]);

            return response()->json(["msg" => "eliminado"]);
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
     * Get States
     */
    public function getStates(Request $request, $id) {
        if ($request->ajax()) {
            $state = State::where('country', $id)->get();
            return response()->json($state);
        }
    }

    /**
     * Get Cities
     */
    public function getCities(Request $request, $id) {
        if ($request->ajax()) {
            $city = City::where('state', $id)->get();
            return response()->json($city);
        }
    }

}

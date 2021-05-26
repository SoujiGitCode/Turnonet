<?php

namespace App\Http\Controllers;

use App\Mail\TuMail;
use Illuminate\Http\Request;
use Route;
use App\UsersApp;
use App\Business;
use App\Notes;
use App\Activities;
use App\Branch;
use App\Lenders;
use App\Visits;
use Redirect;
use DB;
use Mail;
use Session;
use Auth;
use URL;
use App\Http\Requests;

class AdminsController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {

            if (isset($request['rol'])) {
                $rol = '1';
            } else {
                $rol = '2';
            }

            $branch = 0;
            $lender = 0;
            if ($request['branch'] != "") {
                $branch = $request['branch'];
            }
            if ($request['lender'] != "") {
                $lender = $request['lender'];
            }

            $password = $request['password'];

            UsersApp::create([
                'us_nom' => $request['name'],
                'us_mail' => $request['email'],
                'us_contra' => md5($password),
                'us_recon' => $password,
                'us_esta' => 'ALTA',
                'emp_id' => $request['em_id'],
                'suc_id' => $branch,
                'pres_id' => $lender,
                'rol' => $rol,
                'us_altfec' => date("Y-m-d"),
                'status' => '1',
                'type' => '1',
                'level' => '2',
            ]);


            $this->audit('Registro de usuario');


            $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($request['name'], 'UTF-8', 'UTF-8'))) . '.<br/>
              <br/>
              Su registro en la aplicación ha sido procesado con éxito. Los datos que hemos recibido son los siguientes:<br />
              <br />
              <strong>Nombre y apellido:</strong>  ' . $request['name'] . '<br/>
              <strong>Correo electrónico:</strong>  ' . $request['email'] . '<br/>
              <strong>Contraseña:</strong>  ' . $password . '<br/>';

            $title = 'Registro exitoso';

            Mail::to($request['email'], mb_convert_encoding($request['name'], 'UTF-8', 'UTF-8'))->send(new TuMail('email_user', $title, $content));


            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        try {
            if ($request->ajax()) {


                if (isset($request['rol'])) {
                    $rol = '1';
                } else {
                    $rol = '2';
                }

                $password = $request['password'];

                $branch = 0;
                $lender = 0;
                if ($request['branch'] != "") {
                    $branch = $request['branch'];
                }
                if ($request['lender'] != "") {
                    $lender = $request['lender'];
                }

                $user = UsersApp::find($id);
                $user->fill([
                    'us_nom' => $request['name'],
                    'us_mail' => $request['email'],
                    'us_contra' => md5($password),
                    'us_recon' => $password,
                    'suc_id' => $branch,
                    'pres_id' => $lender,
                    'emp_id' => $request['em_id'],
                    'rol' => $rol,
                ]);
                $user->save();
                $this->audit('Actualización de administrador ID #' . $id);
                return response()->json(["msg" => "updated"]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            $users = UsersApp::find($id);
            $users->fill([
                'us_esta' => 'BAJA',
            ]);
            $users->save();
            $this->audit('Eliminar administrador ID #' . $id);
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
    public function alta_admin(Request $request) {
        try {
            $this->audit('Dar de alta al administrador ' . $request['id']);

            $users = UsersApp::find($request['id']);
            $users->fill([
                'us_esta' => 'ALTA',
            ]);
            $users->save();
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists($id, $status) {
        try {



            $user = UsersApp::orderby('us_id', 'desc')->where('level', '2')->where('emp_id', $id)->where('us_esta', $status)->get();
            $json = array();
            foreach ($user as $rs):

                $name_branch = '';
                $name_lender = '';
                $email = ($rs->us_mail == '') ? 'N/A' : strtolower($rs->us_mail);
                $color = ($rs->us_esta == 'ALTA') ? 'transparent' : '#ffe9e9';
                $created_at = ($rs->us_altfec == null) ? '' : date("Y-m-d", strtotime($rs->us_altfec));

                if ($rs->rol == '1') {
                    $rol = "SOLO TURNOS";
                } else {
                    $rol = "ADMINISTRADOR";
                }

                $branch = Branch::where('suc_id', $rs->suc_id)->first();
                if (isset($branch) != 0) {
                    $name_branch = $branch->suc_nom;
                }

                $lender = Lenders::where('tmsp_id', $rs->pres_id)->first();
                if (isset($lender) != 0) {
                    $name_lender = $lender->tmsp_pnom;
                }

                $json[] = array(
                    "id" => $rs->us_id,
                    "name" => mb_substr($rs->us_nom, 0, 40),
                    "status" => $rs->us_esta,
                    "password" => $rs->us_recon,
                    "color" => $color,
                    "rol" => $rol,
                    "us_esta" => $rs->us_esta,
                    "suc_id" => $rs->suc_id,
                    "pres_id" => $rs->pres_id,
                    "id_rol" => $rs->rol,
                    "name_lender" => $name_lender,
                    "name_branch" => $name_branch,
                    "email" => mb_substr($email, 0, 40),
                    "created_at" => $created_at,
                );

            endforeach;

            return response()->json($json);
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

    public function getmessageLocation($ip) {
        try {
            $message = 'Buenos Aires';
            return $message;
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
                    'message' => $this->getmessageLocation($this->getIp()),
                    'date' => date("Y-m-d"),
                    'year' => date("Y"),
                    'month' => date("m"),
                    'hour' => date("H") . ':00:00',
                    'day' => date("w"),
                    'message_day' => $this->getDay(date("w"))
                ]);
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function up_status_admin(Request $request) {

        try {
            if ($request['id'] != "") {
                for ($i = 1; $i <= substr_count($request['id'], ','); $i++) {
                    $item = explode(',', $request['id']);


                    $user = UsersApp::find($item[$i]);
                    $user->fill([
                        'us_esta' => $request['status']
                    ]);
                    $user->save();
                }
            }
            $this->audit('Dar de alta administrador ID #' . $request['id']);
            return response()->json(["msg" => "updated"]);
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

}

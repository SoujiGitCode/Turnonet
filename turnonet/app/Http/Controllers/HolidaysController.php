<?php

namespace App\Http\Controllers;

use App\Mail\TuMail;
use Illuminate\Http\Request;
use Route;
use App\UsersApp;
use App\Business;
use App\Holidays;
use App\Communications;
use App\Notifications;
use App\Activities;
use App\Lenders;
use App\Visits;
use Redirect;
use DB;
use Mail;
use Session;
use Auth;
use URL;
use App\Http\Requests;

class HolidaysController extends Controller {

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
            $init = $request['fecha_inicial'];
            $end = $request['fecha_final'];
            if ($init != '') {
                $init = explode('-', $init);
                $init = $init[2] . "-" . $init[1] . "-" . $init[0];
            }
            if ($end != '') {
                $end = explode('-', $end);
                $end = $end[2] . "-" . $end[1] . "-" . $end[0];
            }
            if (date("Y-m-d", strtotime($init)) <= date("Y-m-d")) {
                return response()->json(["response" => "error", "msg" => "La fecha desde no debe ser menor o igual al dia actual "]);
            }
            if ($end != "" && date("Y-m-d", strtotime($end)) <= date("Y-m-d")) {
                return response()->json(["response" => "error", "msg" => "La fecha hasta no debe ser menor o igual al dia actual "]);
            }
            if ($end != "" && date("Y-m-d", strtotime($init)) > date("Y-m-d", strtotime($end))) {
                return response()->json(["response" => "error", "msg" => "La fecha hasta no debe ser menor a la fecha de desde "]);
            }
            $lenders = Lenders::where('emp_id', $request['emp_id'])->where('tmsp_estado', 'ALTA')->get();
            foreach ($lenders as $rs) {
                if ($end == '') {
                    if (Holidays::where('fer_empid', $request['emp_id'])->where('fer_presid', $rs->tmsp_id)->where('fer_estado', '1')->where('fer_date', date("Y-m-d", strtotime($init)))->count() == 0) {
                        Holidays::insert([
                            'fer_empid' => $request['emp_id'],
                            'fer_sucid' => $rs->suc_id,
                            'fer_presid' => $rs->tmsp_id,
                            'fer_date' => date("Y-m-d", strtotime($init)),
                            'fer_ani' => date("Y", strtotime($init)),
                            'fer_mes' => date("m", strtotime($init)),
                            'fer_desc' => $request['fer_desc'],
                            'fer_estado' => '1'
                        ]);
                    }
                } else {
                    for ($i = strtotime($init); $i <= strtotime($end); $i += 86400) {
                        if (Holidays::where('fer_empid', $request['emp_id'])->where('fer_presid', $rs->tmsp_id)->where('fer_estado', '1')->where('fer_date', date("Y-m-d", strtotime($i)))->count() == 0) {
                            Holidays::insert([
                                'fer_empid' => $request['emp_id'],
                                'fer_sucid' => $rs->suc_id,
                                'fer_presid' => $rs->tmsp_id,
                                'fer_date' => date("Y-m-d", $i),
                                'fer_ani' => date("Y", $i),
                                'fer_mes' => date("m", $i),
                                'fer_desc' => $request['fer_desc'],
                                'fer_estado' => '1'
                            ]);
                        }
                    }
                }
            }
            $this->audit('Registrar día no laborable a empresa ' . $request['emp_id']);
            return response()->json(["response" => "true"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function store_branch(Request $request) {
        try {
            $init = $request['fecha_inicial'];
            $end = $request['fecha_final'];
            if ($init != '') {
                $init = explode('-', $init);
                $init = $init[2] . "-" . $init[1] . "-" . $init[0];
            }
            if ($end != '') {
                $end = explode('-', $end);
                $end = $end[2] . "-" . $end[1] . "-" . $end[0];
            }
            if (date("Y-m-d", strtotime($init)) <= date("Y-m-d")) {
                return response()->json(["response" => "error", "msg" => "La fecha desde no debe ser menor o igual al dia actual "]);
            }
            if ($end != "" && date("Y-m-d", strtotime($end)) <= date("Y-m-d")) {
                return response()->json(["response" => "error", "msg" => "La fecha hasta no debe ser menor o igual al dia actual "]);
            }
            if ($end != "" && date("Y-m-d", strtotime($init)) > date("Y-m-d", strtotime($end))) {
                return response()->json(["response" => "error", "msg" => "La fecha hasta no debe ser menor a la fecha de desde "]);
            }
            $lenders = Lenders::where('suc_id', $request['suc_id'])->where('tmsp_estado', 'ALTA')->get();
            foreach ($lenders as $rs) {
                if ($end == '') {
                    if (Holidays::where('fer_sucid', $request['suc_id'])->where('fer_presid', $rs->tmsp_id)->where('fer_estado', '1')->where('fer_date', date("Y-m-d", strtotime($init)))->count() == 0) {
                        Holidays::insert([
                            'fer_empid' => $rs->emp_id,
                            'fer_sucid' => $request['suc_id'],
                            'fer_presid' => $rs->tmsp_id,
                            'fer_date' => date("Y-m-d", strtotime($init)),
                            'fer_ani' => date("Y", strtotime($init)),
                            'fer_mes' => date("m", strtotime($init)),
                            'fer_desc' => $request['fer_desc'],
                            'fer_estado' => '1'
                        ]);
                    }
                } else {
                    for ($i = strtotime($init); $i <= strtotime($end); $i += 86400) {
                        if (Holidays::where('fer_sucid', $request['suc_id'])->where('fer_presid', $rs->tmsp_id)->where('fer_estado', '1')->where('fer_date', date("Y-m-d", strtotime($i)))->count() == 0) {
                            Holidays::insert([
                                'fer_empid' => $rs->emp_id,
                                'fer_sucid' => $request['suc_id'],
                                'fer_presid' => $rs->tmsp_id,
                                'fer_date' => date("Y-m-d", $i),
                                'fer_ani' => date("Y", $i),
                                'fer_mes' => date("m", $i),
                                'fer_desc' => $request['fer_desc'],
                                'fer_estado' => '1'
                            ]);
                        }
                    }
                }
            }
            $this->audit('Registrar día no laborable a la sucursal ' . $request['suc_id']);
            return response()->json(["response" => "true"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function store_lender(Request $request) {
        try {
            $init = $request['fecha_inicial'];
            $end = $request['fecha_final'];
            if ($init != '') {
                $init = explode('-', $init);
                $init = $init[2] . "-" . $init[1] . "-" . $init[0];
            }
            if ($end != '') {
                $end = explode('-', $end);
                $end = $end[2] . "-" . $end[1] . "-" . $end[0];
            }
            if (date("Y-m-d", strtotime($init)) <= date("Y-m-d")) {
                return response()->json(["response" => "error", "msg" => "La fecha desde no debe ser menor o igual al dia actual "]);
            }
            if ($end != "" && date("Y-m-d", strtotime($end)) <= date("Y-m-d")) {
                return response()->json(["response" => "error", "msg" => "La fecha hasta no debe ser menor o igual al dia actual "]);
            }
            if ($end != "" && date("Y-m-d", strtotime($init)) > date("Y-m-d", strtotime($end))) {
                return response()->json(["response" => "error", "msg" => "La fecha hasta no debe ser menor a la fecha de desde "]);
            }

            if ($end == '') {
                if (Holidays::where('fer_presid', $request['pres_id'])->where('fer_estado', '1')->where('fer_date', date("Y-m-d", strtotime($init)))->count() == 0) {
                    Holidays::insert([
                        'fer_empid' => $request['em_id'],
                        'fer_sucid' => $request['suc_id'],
                        'fer_presid' => $request['pres_id'],
                        'fer_date' => date("Y-m-d", strtotime($init)),
                        'fer_ani' => date("Y", strtotime($init)),
                        'fer_mes' => date("m", strtotime($init)),
                        'fer_desc' => $request['fer_desc'],
                        'fer_estado' => '1'
                    ]);
                }
            } else {
                for ($i = strtotime($init); $i <= strtotime($end); $i += 86400) {
                    if (Holidays::where('fer_presid', $request['pres_id'])->where('fer_estado', '1')->where('fer_date', date("Y-m-d", strtotime($i)))->count() == 0) {
                        Holidays::insert([
                            'fer_empid' => $request['em_id'],
                            'fer_sucid' => $request['suc_id'],
                            'fer_presid' => $request['pres_id'],
                            'fer_date' => date("Y-m-d", $i),
                            'fer_ani' => date("Y", $i),
                            'fer_mes' => date("m", $i),
                            'fer_desc' => $request['fer_desc'],
                            'fer_estado' => '1'
                        ]);
                    }
                }
            }

            $this->audit('Registrar día no laborable al prestador ' . $request['pres_id']);
            return response()->json(["response" => "true"]);
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


                $init = $request['fecha_inicial'];
                if ($init != '') {
                    $init = explode('-', $init);
                    $init = $init[2] . "-" . $init[1] . "-" . $init[0];
                }

                $holiday = Holidays::find($id);
                $holiday->fill([
                    'fer_date' => date("Y-m-d", strtotime($init)),
                    'fer_ani' => date("Y", strtotime($init)),
                    'fer_mes' => date("m", strtotime($init)),
                    'fer_desc' => $request['fer_desc'],
                ]);
                $holiday->save();
                $this->audit('Actualización de horario ID #' . $id);
                return response()->json(["msg" => "updated"]);
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
    public function lists($id, $status) {
        try {
            $year = date("Y") . '-' . date("m") . '-' . date("d");
            $categories = Holidays::select('fer_date', 'fer_desc', 'fer_id', 'fer_estado')->distinct()->where('fer_empid', $id)->where('fer_estado', $status)->where('fer_date', '>=', $year)->orderBy('fer_date', 'asc')->groupby('fer_date')->get();
            $json = array();
            foreach ($categories as $rs):
                $name = ($rs->fer_desc == '') ? 'Sin descripción' : $rs->fer_desc;
                $lenders = Holidays::where('fer_empid', $id)->where('fer_estado', '1')->where('fer_date', $rs->fer_date)->count();
                $json[] = array(
                    "id" => $rs->fer_id,
                    "name" => $name,
                    "lenders" => $lenders,
                    "status" => $rs->fer_estado,
                    "date_format" => date("d/m/Y", strtotime($rs->fer_date)),
                    "date" => date("Y-m-d", strtotime($rs->fer_date)),
                    "desde" => date("d-m-Y", strtotime($rs->fer_date)),
                );
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists_branch($id, $status) {
        try {
            $year = date("Y") . '-' . date("m") . '-' . date("d");
            $categories = Holidays::distinct()->select('fer_date', 'fer_desc', 'fer_id', 'fer_estado')->where('fer_sucid', $id)->where('fer_estado', $status)->where('fer_date', '>=', $year)->orderBy('fer_date', 'asc')->groupby('fer_date')->get();
            $json = array();
            foreach ($categories as $rs):
                $name = ($rs->fer_desc == '') ? 'Sin descripción' : $rs->fer_desc;
                $lenders = Holidays::where('fer_sucid', $id)->where('fer_estado', '1')->where('fer_date', $rs->fer_date)->count();
                $json[] = array(
                    "id" => $rs->fer_id,
                    "name" => $name,
                    "lenders" => $lenders,
                    "status" => $rs->fer_estado,
                    "date_format" => date("d/m/Y", strtotime($rs->fer_date)),
                    "date" => date("Y-m-d", strtotime($rs->fer_date)),
                    "desde" => date("d-m-Y", strtotime($rs->fer_date)),
                );
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists_lender($id, $status) {
        try {
            $year = date("Y") . '-' . date("m") . '-' . date("d");
            $categories = Holidays::select('fer_date', 'fer_desc', 'fer_id', 'fer_estado')->distinct()->where('fer_presid', $id)->where('fer_estado', $status)->where('fer_date', '>=', $year)->orderBy('fer_date', 'asc')->groupby('fer_date')->get();
            $json = array();
            foreach ($categories as $rs):
                $name = ($rs->fer_desc == '') ? 'Sin descripción' : $rs->fer_desc;
                $json[] = array(
                    "id" => $rs->fer_id,
                    "name" => $name,
                    "status" => $rs->fer_estado,
                    "date_format" => date("d/m/Y", strtotime($rs->fer_date)),
                    "date" => date("Y-m-d", strtotime($rs->fer_date)),
                    "desde" => date("d-m-Y", strtotime($rs->fer_date)),
                );
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function remove(Request $request) {
        try {
            $this->audit('Eliminar día no laborable a empresa ' . $request['business']);
            Holidays::where('fer_empid', $request['business'])->where('fer_date', date("Y-m-d", strtotime($request['date'])))->update([
                'fer_estado' => '2'
            ]);
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function remove_branch(Request $request) {
        try {
            $this->audit('Eliminar día no laborable a sucursal ' . $request['branch']);
            Holidays::where('fer_sucid', $request['branch'])->where('fer_date', date("Y-m-d", strtotime($request['date'])))->update([
                'fer_estado' => '2'
            ]);
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function remove_lender(Request $request) {
        try {
            $this->audit('Eliminar día no laborable del prestador ' . $request['lender']);
            Holidays::where('fer_presid', $request['lender'])->where('fer_date', date("Y-m-d", strtotime($request['date'])))->update([
                'fer_estado' => '2'
            ]);
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function associate(Request $request, $id) {
        try {
            $redirect = 'https://www.turnonet.com/es/callbackmp.php';
            $url = 'https://auth.mercadopago.com.ar/authorization?client_id=' . $id . '&response_type=code&platform_id=mp&redirect_uri=' . $redirect;
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
    public function alta_date(Request $request) {
        try {
            $this->audit('Dar de alta al dia  ' . $request['date']);

            Holidays::where('fer_empid', $request['business'])->where('fer_date', date("Y-m-d", strtotime($request['date'])))->update([
                'fer_estado' => '1'
            ]);
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
    public function alta_date_branch(Request $request) {
        try {
            $this->audit('Dar de alta al dia  ' . $request['date']);

            Holidays::where('fer_sucid', $request['branch'])->where('fer_date', date("Y-m-d", strtotime($request['date'])))->update([
                'fer_estado' => '1'
            ]);
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
    public function alta_date_lender(Request $request) {
        try {



            $this->audit('Dar de alta al dia  ' . $request['date']);

            Holidays::where('fer_presid', $request['lender'])->where('fer_date', date("Y-m-d", strtotime($request['date'])))->update([
                'fer_estado' => '1'
            ]);
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function up_status_working_business(Request $request) {


        try {
            if ($request['id'] != "") {
                for ($i = 1; $i <= substr_count($request['id'], ','); $i++) {

                    $item = explode(',', $request['id']);

                    Holidays::where('fer_empid', $request['business'])->where('fer_date', date("Y-m-d", strtotime($item[$i])))->update([
                        'fer_estado' => $request['status']
                    ]);
                }
            }
            $this->audit('Dar de alta administrador ID #' . $request['id']);
             return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function up_status_working_branch(Request $request) {

       try {
            if ($request['id'] != "") {
                for ($i = 1; $i <= substr_count($request['id'], ','); $i++) {

                    $item = explode(',', $request['id']);

                    Holidays::where('fer_sucid', $request['branch'])->where('fer_date', date("Y-m-d", strtotime($item[$i])))->update([
                        'fer_estado' => $request['status']
                    ]);
                }
            }
            $this->audit('Dar de alta administrador ID #' . $request['id']);
             return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;

    }


    public function up_status_working_lender(Request $request) {

       try {
            if ($request['id'] != "") {
                for ($i = 1; $i <= substr_count($request['id'], ','); $i++) {

                    $item = explode(',', $request['id']);

                    Holidays::where('fer_presid', $request['lender'])->where('fer_date', date("Y-m-d", strtotime($item[$i])))->update([
                        'fer_estado' => $request['status']
                    ]);
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
            Notifications::where('us_id', Auth::guard('user')->User()->us_id)->where('tipo', '1')->delete();
            return response()->json(["msg" => "eliminado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function delete_noty_active(Request $request) {
        try {
            Notifications::where('us_id', Auth::guard('user')->User()->us_id)->update([
                'status' => '0'
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

}

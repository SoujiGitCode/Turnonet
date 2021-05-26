<?php

namespace App\Http\Controllers;

use App\Mail\TuMail;
use Illuminate\Http\Request;
use Route;
use App\UsersApp;
use App\Business;
use App\Notes;
use App\Activities;
use App\Lenders;
use App\Visits;
use Redirect;
use DB;
use Mail;
use Session;
use Auth;
use Cache;
use URL;
use App\Http\Requests;

class NotesController extends Controller {

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
            if ($init != '') {
                $init = explode('-', $init);
                $init = $init[2] . "-" . $init[1] . "-" . $init[0];
            }
            if (Notes::where('not_presid', $request['pres_id'])->where('not_estado', '1')->where('not_caduc', date("Y-m-d", strtotime($init)))->where('not_desc', $request['message'])->where('us_id', $request['us_id'])->count() != 0) {
                return response()->json(["response" => "error", "msg" => "La nota ya se encuentra registrada"]);
            } else {
                Notes::insert([
                    'not_empid' => $request['em_id'],
                    'not_sucid' => $request['suc_id'],
                    'not_presid' => $request['pres_id'],
                    'us_id'=>$request['us_id'],
                    'not_desc' => $request['message'],
                    'not_caduc' => date("Y-m-d", strtotime($init)),
                    'not_ani' => date("Y", strtotime($init)),
                    'not_mes' => date("m", strtotime($init)),
                    'not_estado' => '1'
                ]);
                $this->audit('Registrar nota a prestador' . $request['pres_id']);
                return response()->json(["response" => "true"]);
            }
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

                $notes = Notes::find($id);
                $notes->fill([
                    'not_desc' => $request['message'],
                    'us_id'=>$request['us_id'],
                    'not_caduc' => date("Y-m-d", strtotime($init)),
                    'not_ani' => date("Y", strtotime($init)),
                    'not_mes' => date("m", strtotime($init))
                ]);
                $notes->save();
                $this->audit('Actualización de nota ID #' . $id);
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
            $notes = Notes::find($id);
            $notes->fill([
                'not_estado' => '0'
            ]);
            $notes->save();
            $this->audit('Eliminar nota ID #' . $id);
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function status_notes(Request $request) {

        try {
            if ($request['id'] != "") {
                for ($i = 1; $i <= substr_count($request['id'], ','); $i++) {
                    $item = explode(',', $request['id']);


                    $notes = Notes::find($item[$i]);
                    $notes->fill([
                        'not_estado' => $request['status']
                    ]);
                    $notes->save();
                }
            }
            $this->audit('Dar de alta especialidad ID #' . $request['id']);
            return response()->json(["msg" => "updated"]);
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
    public function alta_notes(Request $request) {
        try {
            $notes = Notes::find($request['id']);
            $notes->fill([
                'not_estado' => '1'
            ]);
            $notes->save();
            $this->audit('Dar de alta nota ID #' . $request['id']);
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
    public function lists($id,$status) {
        try {
            $notes = Notes::where('not_presid', $id)->where('not_estado', $status)->get();
            $json = array();
            foreach ($notes as $rs):

               $user_id = $rs->us_id;

               $user = Cache::rememberForever('user_' . $user_id, function ()use($user_id) {
                return DB::table('directory')->where('id', $user_id)->first();
            });


               if (isset($user) != 0) {

                $json[] = array(
                    "date_format" => date("d/m/Y", strtotime($rs->not_caduc)),
                    "date" => date("d-m-Y", strtotime($rs->not_caduc)),
                    "customer"=>mb_strtoupper($user->name),
                    "email"=>mb_strtolower($user->email),
                    "us_id"=>$rs->us_id,
                    "note" => mb_convert_encoding($rs->not_desc, 'UTF-8', 'UTF-8'),
                    "status"=>$rs->not_estado,
                    "id" => $rs->not_id,
                );
            }
        endforeach;
        return response()->json($json);
    } catch (Exception $ex) {
        return false;
    }
    return false;
    }

    /**
     * Display lists_customer resources
     * @return type
     */
    public function lists_customer($id,$status,$customer) {
        try {
            $notes = Notes::where('not_presid', $id)->where('us_id',$customer)->where('not_estado', $status)->get();
            $json = array();
            foreach ($notes as $rs):

               $user_id = $rs->us_id;

               $user = Cache::rememberForever('user_' . $user_id, function ()use($user_id) {
                return DB::table('directory')->where('id', $user_id)->first();
            });


               if (isset($user) != 0) {

                $json[] = array(
                    "date_format" => date("d/m/Y", strtotime($rs->not_caduc)),
                    "date" => date("d-m-Y", strtotime($rs->not_caduc)),
                    "customer"=>mb_strtoupper($user->name),
                    "email"=>mb_strtolower($user->email),
                    "us_id"=>$rs->us_id,
                    "note" => mb_convert_encoding($rs->not_desc, 'UTF-8', 'UTF-8'),
                    "status"=>$rs->not_estado,
                    "id" => $rs->not_id,
                );
            }
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

    public function getIdBusiness(){

        if(Auth::guard('user')->User()->level==1){
            return Auth::guard('user')->User()->us_id;

        }else{

             $get_business = Business::find(Auth::guard('user')->User()->emp_id);
            return $get_business->em_uscid;
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Mail\TuMail;
use Illuminate\Http\Request;
use Route;
use App\UsersApp;
use App\Directory;
use App\Business;
use App\Communications;
use App\Lenders;
use App\Notifications;
use App\Activities;
use App\Visits;
use App\Shift;
use App\MercadoPago;
use Redirect;
use DB;
use Mail;
use Session;
use Auth;
use URL;
use App\Http\Requests;

class MercadoPagoController extends Controller {

    public function remove(Request $request) {
        try {

            $business = Business::find($request['id']);
            $business->fill([
                'access_token' => '',
                'public_key' => '',
                'expired_mp' => '0000-00-00'
            ]);
            $business->save();

            $lenders = Lenders::where('emp_id', $request['id'])->update([
                'tmsp_pagoA' => 'BAJA',
            ]);
            $this->audit('Desvincular tu cuenta de mercado pago empresa ' . $request['id']);
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function lists($id) {
        try {
            $lists = MercadoPago::where('emp_id', $id)->get();
            $json = array();
            foreach ($lists as $rs) {
                $shift = Shift::find($rs->id_turno);

                if(isset($shift)!=0){

                    $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                    if(isset($user)!=0){

                        $json[] = array(
                            "id" => $rs->tu_id,
                            "id_payment" => $rs->id_payment,
                            "code" => $shift->tu_code,
                            "amount" => number_format($rs->amount, 2, ".", ","),
                            "name" => mb_strtoupper($user->name),
                            "created_at" => date("d/m/Y H:i:s", strtotime($rs->created_at))
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

    public function lists_search($init, $end, $id) {
        try {
            if ($init != '') {
                $init = explode('-', $init);
                $init = $init[2] . "-" . $init[1] . "-" . $init[0];
            }
            if ($end != '') {
                $end = explode('-', $end);
                $end = $end[2] . "-" . $end[1] . "-" . $end[0];
            }
            $lists = MercadoPago::where('emp_id', $id)
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->get();
            $json = array();
            foreach ($lists as $rs) {
                $shift = Shift::find($rs->id_turno);
                $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                $json[] = array(
                    "id" => $rs->tu_id,
                    "id_payment" => $rs->id_payment,
                    "code" => $shift->tu_code,
                    "amount" => number_format($rs->amount, 2, ".", ","),
                    "name" => mb_strtoupper($user->name),
                    "created_at" => date("d/m/Y H:i:s", strtotime($rs->created_at))
                );
            }
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function associate(Request $request, $id) {
        try {
            $redirect = 'https://www.turnonet.com/callbackmp';
            $request->session()->put('id_empresa', $id);

            $site = DB::table('tu_settingsmp')->where('id', '1')->first();

            $client_id = $site->client_id;
            $client_secret = $site->client_secret;
            
            $url = 'https://auth.mercadopago.com.ar/authorization?client_id=' . $client_id . '&response_type=code&platform_id=mp&redirect_uri=' . $redirect;
            return redirect($url);
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
            Notifications::where('us_id', $this->getIdBusiness())->where('tipo', '1')->delete();
            return response()->json(["msg" => "eliminado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function delete_noty_active(Request $request) {
        try {
            Notifications::where('us_id', $this->getIdBusiness())->update([
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

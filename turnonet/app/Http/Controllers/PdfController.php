<?php

namespace App\Http\Controllers;

use App\Mail\TuMail;
use Illuminate\Http\Request;
use Route;
use App\Activities;
use App\Visits;
use App\Business;
use App\Lenders;
use App\UsersApp;
use App\Directory;
use App\Services;
use App\Branch;
use App\BusinessFields;
use App\ClientsCustomization;
use App\Shift;
use Redirect;
use DB;
use PDF;
use Mail;
use Session;
use Cache;
use Auth;
use URL;
use App\Http\Requests;

class PdfController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('user');
    }

    /**
     * Display directory
     * @return type
     */
    public function directory($code,$business,$id) {
        try {
            if (!Auth::guard('user')->guest()) {

                $business = Business::find($business);
                if (isset($business) == 0)
                    return redirect::to('/agenda');
                if ($business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/agenda');
             
                $user = Directory::find($id);
                if (isset($user) == 0)
                    return redirect::to('/agenda');

                 $shift = Shift::where('tu_code', $code)->first();
                if (isset($shift) == 0)
                    return redirect::to('/agenda');

            if (ClientsCustomization::where('usm_usid', $user->us_id)->where('usm_empid',$shift->emp_id)->count() == 0) {
                    ClientsCustomization::insert([
                        'usm_usid' => $user->us_id,
                        'usm_empid' => $user->emp_id
                    ]);
                }
               
                $inputs_add = BusinessFields::where('mi_empid', $shift->emp_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
                $data = ClientsCustomization::where('usm_turid',$shift->tu_id)->first();
               
            $view = view('pdf/customer', ['user' => $user, 'business' => $business, 'inputs_add' => $inputs_add, 'data' => $data]);
            $pdf = PDF::loadHTML($view);
            return $pdf->stream('cliente_' . $id.'.pdf', array("Attachment" => true));


                
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
    public function shift($code) {
        try {
            if (!Auth::guard('user')->guest()) {

               $shift = Shift::where('tu_code', $code)->first();
                if (isset($shift) == 0)
                    return redirect::to('/agenda');
                $lender = Lenders::find($shift->pres_id);
                $business = Business::find($shift->emp_id);
                $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                $services = Services::where('serv_presid', $shift->pres_id)->where('serv_estado', '1')->get();
                $phone = ($user->phone == '') ? '' : $this->format_phone($user->phone);
                 $inputs_add = BusinessFields::where('mi_empid', $shift->emp_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
                $data = ClientsCustomization::where('usm_turid',$shift->tu_id)->first();
               
            $view = view('pdf/shift', ['user' => $user,'lender' => $lender, 'business' => $business, 'inputs_add' => $inputs_add, 'data' => $data,'shift' => $shift]);
            $pdf = PDF::loadHTML($view);
            return $pdf->stream('turno' . $code.'.pdf', array("Attachment" => true));


                
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
public function statistics(Request $request,$id,$init,$end) {
    try {
            $get_business = Business::find($id);
            if (isset($get_business) == 0)
                return redirect::to('/empresas');
            if($get_business->em_uscid!=$this->getIdBusiness())
                return redirect::to('/empresas');

            
            if ($init != '') {
                $init = explode('-', $init);
                $init = $init[2] . "-" . $init[1] . "-" . $init[0];
            }
            if ($end != '') {
                $end = explode('-', $end);
                $end = $end[2] . "-" . $end[1] . "-" . $end[0];
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
            $ausencia= Shift::
            where('emp_id', $id)
            ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
            ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
            ->where('tu_asist', '0')
            ->count();
            $parcial= Shift::
            where('emp_id', $id)
            ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
            ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
            ->where('tu_asist', '2')
            ->count();
            $no_defined= Shift::
            where('emp_id', $id)
            ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
            ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
            ->where('tu_asist', '3')
            ->count();
            $days= Shift::
            select('tu_fec', DB::raw('count(*) as total'))
            ->where('emp_id', $id)
            ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
            ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
            ->groupBy('tu_fec')
            ->orderby('tu_fec', 'asc')
            ->get();
            $hours= Shift::
            select('tu_hora', DB::raw('count(*) as total'))
            ->where('emp_id', $id)
            ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
            ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
            ->groupBy('tu_hora')
            ->orderby('tu_hora', 'asc')
            ->get();
            $lenders=Shift::
            select('pres_id', DB::raw('count(*) as total'))
            ->where('emp_id', $id)
            ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
            ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
            ->groupBy('pres_id')
            ->offset(0)
            ->limit(5)
            ->orderby('total', 'desc')
            ->get();
            $view = view('pdf/statistics', ['business'=>$get_business,
                'days'=>$days,
                'hours'=>$hours,
                "lenders"=>$lenders,
                'shifts'=>$shifts,
                "overturn"=>$overturn,
                'actives'=>$actives,
                'asistencia'=>$asistencia,
                'ausencia'=>$ausencia,
                'parcial'=>$parcial,
                'no_defined'=>$no_defined,
                'cancels'=>$cancel,
                'init'=>$init,
                'end'=>$end]);
            $pdf = PDF::loadHTML($view);
            return $pdf->stream('estadisticas-' . $id.'-'.date("Y-m-d", strtotime($init)).'-'.date("Y-m-d", strtotime($end)).'.pdf', array("Attachment" => true));


    } catch (Exception $ex) {
        return false;
    }
    return false;
}

    public function report($type, $rep_type, $id_sq, $emp_id, $date_reports) {
 set_time_limit(0);
        try {
              if ($date_reports != '') {
                $date_reports = explode('-', $date_reports);
                $date_reports = $date_reports[2] . "-" . $date_reports[1] . "-" . $date_reports[0];
            }
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
                        'lender' => mb_strtoupper($lender->tmsp_pnom),
                    );
                }
            }

            $count_branch=0;

             if ($type == '1') {
            $business = Business::find($id_sq);
            $count_branch= Branch::where('suc_empid', $id_sq)->where('suc_estado', 'ALTA')->count();
            $title = 'Reporte de turnos de '.$business->em_nomfan.' para el '.date("d/m/Y",strtotime($date_reports));
        }


 if ($type == '2') {
            $branch = Branch::find($id_sq);
            $title = 'Reporte de turnos de '.$branch->suc_nom.' para el '.date("d/m/Y",strtotime($date_reports));
        }

            
            if ($type == '3') {
            $lender = Lenders::find($id_sq);
            $title = 'Reporte de turnos de '.$lender->tmsp_pnom.' para el '.date("d/m/Y",strtotime($date_reports));
        }


            $inputs_add = BusinessFields::where('mi_empid', $emp_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();


            $view = view('pdf/report', ['title' => strtoupper($title), 'data' => $json, 'inputs_add' => $inputs_add, 'rep_type' => $rep_type,'type'=>$type,'count_branch'=>$count_branch]);
            $pdf = PDF::loadHTML($view)->setPaper('letter', 'landscape');




            if($type=='1')
                $name='Reporte-de-turnos-empresa-'.date("Y-m-d", strtotime($date_reports)).'.pdf';

            if($type=='2')
                $name='Reporte-de-turnos-sucursal-'.date("Y-m-d", strtotime($date_reports)).'.pdf';

            if($type=='3')
                $name='Reporte-de-turnos-prestador-'.date("Y-m-d", strtotime($date_reports)).'.pdf';


        

            return $pdf->download($name.'.pdf', array("Attachment" => true));


          
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

    /**
     * Set Format phone
     * @param type $phone
     * @return boolean|string
     */
    public function format_phone($phone) {
        try {
            if (!isset($phone{3})) {
                return '';
            }
            $phone = preg_replace("/[^0-9]/", "", $phone);
            $length = strlen($phone);
            switch ($length) {
                case 7:
                    return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                    break;
                case 8:
                    return preg_replace("/([0-9]{4})([0-9]{4})/", "$1-$2", $phone);
                    break;
                case 9:
                    return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1 $2-$3", $phone);
                    break;
                case 10:
                    return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1 $2-$3", $phone);
                    break;
                case 11:
                    return preg_replace("/([0-9]{1})([0-9]{2})([0-9]{4})([0-9]{4})/", "$1 $2 $3-$4", $phone);
                    break;
                default:
                    return $phone;
                    break;
            }
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


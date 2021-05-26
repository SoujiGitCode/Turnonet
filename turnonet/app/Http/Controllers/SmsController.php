<?php
namespace App\Http\Controllers;
use App\Mail\TuMailBusiness;
use Illuminate\Http\Request;
use Route;
use App\Activities;
use App\Visits;
use App\Business;
use App\Branch;
use App\UsersApp;
use App\Directory;
use App\BusinessFields;
use App\ClientsCustomization;
use App\BlockedShedules;
use App\LenderNotifications;
use App\Notifications;
use App\SMS;
use App\Lenders;
use App\Country;
use App\Services;
use App\Shift;
use Redirect;
use DB;
use Mail;
use Cache;
use Session;
use Log;
use Auth;
use URL;
use App\Http\Requests;
class SmsController extends Controller {
/**
* Up status shift
* @param Request $request
* @return boolean
*/
public function index(Request $request) {
    try {

       

        
         //Capturo los parametros

        if(isset($_GET['r'])){

             Log::info('cancelo turno '.$_GET['r'].' desde smsfacil por curl ');

            $turn_emp=$_GET['r'];
            $msg=$_GET['msg'];
            $turn_emp=explode('-',$turn_emp);
            $turn_id=$turn_emp[1];
            $turn_empid=$turn_emp[0];
            $primerosChar= mb_strtolower(substr($msg, 0,2));

            if($msg==''){
                return response()->json(["msg" => "borrado"]);
            }
            if($primerosChar=='no' ){

               
                $shift = Shift::find($turn_id);
                if(isset($shift)!=0){

                   
                  
                    $business = Business::find($shift->emp_id);
                    if ($business->em_smscontrol == 'ALTA') {
                        $bloqueo = 0;
                        $aviso = 1;
                        $shift = Shift::find($turn_id);
                        $user = Directory::where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();
                        $services = '';
                        $lender = Lenders::find($shift->pres_id);
                        $business = Business::find($shift->emp_id);
                        $address = DB::table('tu_emps_suc')->where('suc_id', $shift->suc_id)->first();
                        $create = $this->nameDay(date("w", strtotime($shift->tu_fec))) . ', ' . date("d", strtotime($shift->tu_fec)) . ' de ' . $this->NameMonth(date("m", strtotime($shift->tu_fec))) . ' del  ' . date("Y", strtotime($shift->tu_fec));
                        $time = (date("H", strtotime($shift->tu_hora)) <= 12) ? 'AM' : 'PM';
// get services
                        if ($shift->tu_servid != "") {
                            if (substr_count($shift->tu_servid, '-') <= 0) {
                                $get_service = Services::find($shift->tu_servid);
                                if (isset($get_service) != 0) {
                                    $services .= $get_service->serv_nom;
                                }
                            } else {
                                for ($i = 0; $i <= substr_count($shift->tu_servid, '-'); $i++) {
                                    $service = explode('-', $shift->tu_servid);
                                    $get_service = Services::find($service[$i]);
                                    if (isset($get_service) != 0) {
                                        $services .= trim($get_service->serv_nom);
                                    }
                                    if ($i != substr_count($shift->tu_servid, '-')) {
                                        $services .= ", ";
                                    }
                                }
                            }
                        }
                        BlockedShedules::where('tur_id',$turn_id)->delete();
                        
                        if(isset($user)!=0){
                            $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))) . '
                            <br/>
                            Te informamos a través de este correo electrónico que el turno que has solicitado para el ' . $create . ' a las ' . date("H:i", strtotime($shift->tu_hora)) . ' ' . $time . ' ha sido cancelado.<br><br>
                            <strong style="color:#FF5722">DATOS DEL TURNO:</strong><br/>
                            <strong>Código:</strong> ' . $shift->tu_code . '<br/>
                            <strong>Empresa:</strong> ' . mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8') . '<br/>
                            <strong>Prestador:</strong> ' . mb_convert_encoding($lender->tmsp_pnom, 'UTF-8', 'UTF-8') . '<br/>';
                            if (isset($address) != 0) {
                                $content .= '<strong>Dirección:</strong> ' . $address->suc_dom . ' ' . $address->suc_domnum . ' ' . $address->suc_dompiso . '<br/>';
                            }
                            if ($services != '') {
                                $content .= '<strong>Servicios solicitados:</strong> ' . $services . '<br/>';
                            }
                            $content .= '<br>Para más información comunicate con ' . mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8') . '.';
                            $title = 'Turno Cancelado';
                            $replyto = mb_convert_encoding($business->em_email, 'UTF-8', 'UTF-8');
                            if ($replyto == "" || false == filter_var($replyto, FILTER_VALIDATE_EMAIL)) {
                                $replyto=env('MAIL_FROM_ADDRESS');
                            }
                            if($user->email!="" && false !== filter_var($user->email, FILTER_VALIDATE_EMAIL)  && $aviso==1 ){
                                Mail::to($user->email, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuMailBusiness($replyto, 'email_single', $title, $content));
                            }
                        }
                        $notifications_business = LenderNotifications::
                        where('pc_empid', $business->em_id)
                        ->first();
                        if (isset($notifications_business) != 0 && $notifications_business->pc_mailc == '1') {
                            Mail::to($user->email,$user->name)
                            ->send(
                                new TuMailBusiness(
                                    $replyto,
                                    'email_single',
                                    $title,
                                    str_replace('Hola, ' . ucwords(mb_strtolower($user->name)), 'Hola, ' . ucwords(mb_strtolower($user->name)), $content)
                                ));
                        }


                        Notifications::create([
                            'description' => 'El turno ' . $shift->tu_code . ' ha sido cancelado',
                            "us_id" => $business->em_uscid,
                            'tipo' => '2',
                            'url' => url('/') . '/agenda/turno/' . $shift->tu_code,
                        ]);


                        DB::table('tu_tucan')->insert([
                            'tucan_turid' => $shift->tu_id,
                            "tucan_mot" => 'Cancelado por SMS',
                            "tucan_fec" => date("Y-m-d"),
                            "tucan_hor" => date("H:i:s"),
                            "tucan_usid" => $shift->us_id
                        ]);

                        $shift = Shift::find($turn_id);
                        $shift->fill([
                            'tu_estado' => 'BAJA'
                        ]);
                        $shift->save();

                        DB::table('tu_smscanceltur')->insert([
                            'smsc_turid' => $shift->tu_id,
                            "smsc_empid" => $shift->emp_id,
                            "smsc_msg"=>$msg,
                            "smsc_fecrec" => date("Y-m-d"),
                            "sms_timerec" => date("H:i:s")
                        ]);

                        if ($business->em_tel != "" && $business->em_tel != null) {
                            SMS::create([
                                'tusms_turid' => $shift->tu_id,
                                'tusms_empid' => $shift->emp_id,
                                'tusms_sucid' => $shift->suc_id,
                                'tusms_preid' => $shift->pres_id,
                                'tusms_usuid' => $shift->us_id,
                                'tusms_pacnom' => mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'),
                                'tusms_celenv' => trim($business->em_tel),
                                "tusms_msg" => "El turno para el " . date("d/m/Y", strtotime($shift->tu_fec)) . " a las " . date("H:i", strtotime($shift->tu_hora)) . " hs. ha sido cancelado por " . ucwords(mb_strtolower(mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))),
                                'tusms_tipo' => '4',
                                'tusms_para' => '1',
                                'tusms_pass' => '1',
                                'tusms_priori' => '1',
                                'tusms_envfec' => date("Y-m-d"),
                                'tusms_envtime' => date("H:i:s")
                            ]);
                        }
                    }
                }

            }
        }
        return response()->json(["msg" => "true"]);
    } catch (Exception $ex) {
        return false;
    }
    return false;
}

  /**
     * Set name Day
     * @param type $day
     * @param type $lang
     * @return type
     */
    public function nameDay($day) {
        switch ($day) {
            case 0:
                $day = 'Domingo';
                break;
            case 1:
                $day = 'Lunes';
                break;
            case 2:
                $day = 'Martes';
                break;
            case 3:
                $day = 'Miércoles';
                break;
            case 4:
                $day = 'Jueves';
                break;
            case 5:
                $day = 'Viernes';
                break;
            case 6:
                $day = 'Sábado';
                break;
            default:
# code...
                break;
        }
        return $day;
    }

    /**
     * Set name month
     * @param type $month
     * @param type $lang
     * @return type
     */
    public function NameMonth($month) {
        if ($month == '01' || $month == '1') {
            $name = 'Enero';
        }
        if ($month == '02' || $month == '2') {
            $name = 'Febrero';
        }
        if ($month == '03' || $month == '3') {
            $name = 'Marzo';
        }
        if ($month == '04' || $month == '4') {
            $name = 'Abril';
        }
        if ($month == '05' || $month == '5') {
            $name = 'Mayo';
        }
        if ($month == '06' || $month == '6') {
            $name = 'Junio';
        }
        if ($month == '07' || $month == '7') {
            $name = 'Julio';
        }
        if ($month == '08' || $month == '8') {
            $name = 'Agosto';
        }if ($month == '09' || $month == '9') {
            $name = 'Septiembre';
        }
        if ($month == '10') {
            $name = 'Octubre';
        }
        if ($month == '11') {
            $name = 'Noviembre';
        }
        if ($month == '12') {
            $name = 'Diciembre';
        }
        return ucwords($name);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Audits;
use App\Http\Controllers\Controller;
use App\UsersApp;
use App\Business;
use App\MercadoPago;
use App\Shift;
use App\Lenders;
use App\Frame;
use App\Mail\TuMail;
use Mail;
use Flash;
use DB;
use Cache;
use Artisan;
use Auth;
use Session;
use Redirect;
use Illuminate\Http\Request;

class CmsBusinessController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:23');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.business_lists');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request) {
        try {
            return view('admin.business_reports_lists');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payments_reports() {
        try {

            $business = Business::where('em_mp',1)->orderby('em_feccre', 'desc')->offset(0)->limit(5000)->get();
            return view('admin.payment_reports_lists',['business'=>$business]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accounts(Request $request, $id) {
        try {
            $business = Business::find($id);
            if (isset($business) == 0)
                return redirect::to('/business');
            return view('admin.business_account', ['business' => $business]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists($status) {
        try {
            set_time_limit(0);
            $business = DB::select('select * from tu_emps where em_estado= ? order by em_feccre desc', [$status]);
            $json = array();
            foreach ($business as $rs):
                $user_id = $rs->em_uscid;
                $get_frame = url('/') . '/e/esn/' . $rs->em_id . '/' . substr($rs->em_valcod, 0, 4);
                $user = Cache::rememberForever('usuario_' . $user_id, function ()use($user_id) {
                            return DB::table('tu_users')->where('us_id', $user_id)->first();
                        });
                $pay = ($rs->em_fact == '1') ? 'SI' : 'NO';
                if (isset($user) != 0) {
                    $email = ($rs->em_email == '') ? 'N/A' : strtolower($rs->em_email);
                    $color = ($status == 'ALTA') ? 'transparent' : '#ffe9e9';
                    $zoom= ($rs->zoom_act == '0') ? 'NO' : 'SI'; 
                    $mp = ($rs->em_mp != '1') ? 'N/A' : $rs->commission;
                    $created_at = ($rs->em_feccre == null) ? '' : date("Y-m-d", strtotime($rs->em_feccre));
                    $json[] = array(
                        "id" => $rs->em_id,
                        "name" => mb_substr(mb_convert_encoding($rs->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100),
                        "email" => mb_convert_encoding($email, 'UTF-8', 'UTF-8'),
                        "status_business" => $rs->em_estado,
                        "status_user" => $user->us_esta,
                        "id_user" => $user->us_id,
                        "frame" => $get_frame,
                        "zoom"=>$zoom,
                        "color" => $color,
                        "pay" => $pay,
                        "mp" => $mp,
                        "total_commission"=>number_format($rs->total_commission, 2, ".", ","),
                        "status_smscontrol" => $rs->em_smscontrol,
                        "password" => $user->us_recon,
                        "username" => $user->us_nom,
                        "useremail" => strtolower($user->us_mail),
                        "created_at" => $created_at,
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
     * Display list search resources
     * @return type
     */
    public function lists_search($pay, $status) {
        try {
            $pay = $pay;
            $status = $status;
            if ($pay == '0')
                $pay = '';
            if ($status == '0')
                $status = '';
            $business = DB::table('tu_emps')
                    ->when(!empty($pay), function ($query) use($pay) {
                        return $query->where('em_fact', $pay);
                    })
                    ->when(!empty($status), function ($query) use($status) {
                        return $query->where('em_estado', $status);
                    })
                    ->orderby('em_feccre', 'desc')
                    ->offset(0)
                    ->limit(5000)
                    ->get();
            $json = array();
            foreach ($business as $rs):
                $get_frame = url('/') . '/e/esn/' . $rs->em_id . '/' . substr($rs->em_valcod, 0, 4);
                $user_id = $rs->em_uscid;
                $user = Cache::rememberForever('usuario_' . $user_id, function ()use($user_id) {
                            return DB::table('tu_users')->where('us_id', $user_id)->first();
                        });
                if (isset($user) != 0) {
                    $email = ($rs->em_email == '') ? 'N/A' : strtolower($rs->em_email);
                    $mp = ($rs->em_mp != '1') ? 'N/A' : $rs->commission;
                    $color = ($status == 'ALTA') ? 'transparent' : '#ffe9e9';
                    $zoom= ($rs->zoom_act == '0') ? 'NO' : 'SI'; 
                    $pay = ($rs->em_fact == '1') ? 'SI' : 'NO';
                    $created_at = ($rs->em_feccre == null) ? '' : date("Y-m-d", strtotime($rs->em_feccre));
                    $json[] = array(
                        "id" => $rs->em_id,
                        "name" => mb_substr(mb_convert_encoding($rs->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100),
                        "email" => mb_convert_encoding($email, 'UTF-8', 'UTF-8'),
                        "status_business" => $rs->em_estado,
                        "status_user" => $user->us_esta,
                        "id_user" => $user->us_id,
                        "lenders" => $rs->total_pres,
                        "zoom"=>$zoom,
                        "color" => $color,
                        "frame" => $get_frame,
                        "status_smscontrol" => $rs->em_smscontrol,
                        "total_commission"=>number_format($rs->total_commission, 2, ".", ","),
                        "um" => $rs->total_turnos_lastmonth,
                        "ua" => $rs->total_turnos_mes,
                        "pay" => $pay,
                        "sms" => $rs->total_sms,
                        "mp" => $mp,
                        "created_at" => $created_at,
                    );
                }
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function lists_payments() {
        try {
            $lists = MercadoPago::orderby('id', 'desc')->get();
            $json = array();
            foreach ($lists as $rs):
                $id_business = $rs->emp_id;
                $get_business = Cache::rememberForever('business_' . $id_business, function() use($id_business) {
                            return DB::table('tu_emps')->where('em_id', $id_business)->first();
                        });
                $shift = Shift::find($rs->id_turno);
                if (isset($get_business) != 0 && isset($shift) != 0) {
                    $json[] = array(
                        "id" => $rs->tu_id,
                        "id_payment" => $rs->id_payment,
                        "code" => $shift->tu_code,
                        "amount" => number_format($rs->amount, 2, ".", ","),
                        "commission" => number_format($rs->commission, 2, ".", ","),
                        "business" => mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100),
                        "created_at" => date("d/m/Y H:i:s", strtotime($rs->created_at))
                    );
                }
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function lists_payments_search($init, $end,$business) {
        try {

            if ($init != '') {
                $init = explode('-', $init);
                $init = $init[2] . "-" . $init[1] . "-" . $init[0];
            }
            if ($end != '') {
                $end = explode('-', $end);
                $end = $end[2] . "-" . $end[1] . "-" . $end[0];
            }

            if ($business == '0')
                $business = '';

            $lists = MercadoPago::when(!empty($init), function ($query) use($init) {
                        return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->when(!empty($business), function ($query) use($business) {
                        return $query->where('emp_id',$business);
                    })
                    ->get();
            $json = array();
            foreach ($lists as $rs):
                $id_business = $rs->emp_id;
                $get_business = Cache::rememberForever('business_' . $id_business, function() use($id_business) {
                            return DB::table('tu_emps')->where('em_id', $id_business)->first();
                        });
                $shift = Shift::find($rs->id_turno);
                if (isset($get_business) != 0 && isset($shift) != 0) {
                    $json[] = array(
                        "id" => $rs->tu_id,
                        "id_payment" => $rs->id_payment,
                        "code" => $shift->tu_code,
                        "amount" => number_format($rs->amount, 2, ".", ","),
                        "commission" => number_format($rs->commission, 2, ".", ","),
                        "business" => mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100),
                        "created_at" => date("d/m/Y H:i:s", strtotime($rs->created_at))
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserAppCreateRequest $request) {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        try {
            $business = Business::find($id);
            if (isset($business) == 0)
                return redirect::to('/business');
            return view('admin.business_edit', ['business' => $business]);
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
            if (isset($request['em_mp'])) {
                $status_mp = '1';
            } else {
                $status_mp = '0';
            }
            $business = Business::find($id);
            $business->fill([
                'em_mp' => $status_mp,
                'commission' => $request['commission'],
            ]);
            $business->save();
            $this->audit('Actualización de datos de empresa ID #' . $id);
            Session()->flash('warning', 'Registro Actualizado');
            return redirect::to('/business');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }


    public function update_video(Request $request){


          try {
            $business = Business::find($request['id']);
            $business->fill([
                'zoom_act' => $request['status'],
            ]);
            $business->save();


            if($request['status']=="0"){

 Lenders::where('emp_id', $request['id'])->update([
                    'activate_zoom' => '0'
                ]);

            }

        
            return response()->json(["msg" => "borrado"]);

             } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status user
     * @param Request $request
     * @return boolean
     */
    public function up_status(Request $request) {
        try {
            if ($request['status'] == '1') {
                $status = 'ALTA';
                $this->audit('Activar empresa ID #' . $request['id']);
                $business = Business::find($request['id']);
                $user = UsersApp::find($business->em_uscid);
                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))) . '.<br/>
            <br/>
            Le informamos a través de este correo electrónico que su empresa ' . mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8') . ' se encuentra en estado "activo" <br />
            <br/><br>';
                $title = 'Su empresa ha sido activado';
                Mail::to($user->us_mail, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuMail('email_user', $title, $content));
            } else {
                $status = 'BAJA';
                $business = Business::find($request['id']);
                $user = UsersApp::find($business->em_uscid);
                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))) . '.<br/>
            <br/>
            Le informamos a través de este correo electrónico que su empresa ' . mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8') . ' ha sido dada de baja, para más información comunicate con el administrador del sistema<br />
            <br/><br>';
                $title = 'Su empresa ha sido dado de baja';
                Mail::to($user->us_mail, mb_convert_encoding($user->name, 'UTF-8', 'UTF-8'))->send(new TuMail('email_single', $title, $content));
                $this->audit('Desactivar empresa ID #' . $request['id']);
            }
            $business = Business::find($request['id']);
            $business->fill([
                'em_estado' => $status
            ]);
            $business->save();
            $user = UsersApp::find($request['user']);
            $user->fill([
                'us_esta' => $status
            ]);
            $user->save();
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status pay
     * @param Request $request
     * @return boolean
     */
    public function up_pay(Request $request) {
        try {
            if ($request['status'] == '1') {
                $membresia = 2;
                $this->audit('Activar pago a empresa ID #' . $request['id']);
            } else {
                $membresia = 1;
                $business = Business::find($request['id']);
                $this->audit('Desactivar pago a empresa ID #' . $request['id']);
            }
            $business = Business::find($request['id']);
            $business->fill([
                'em_fact' => $request['status']
            ]);
            $business->save();
            $business = Business::find($request['id']);
            $user = UsersApp::find($business->em_uscid);
            $user->fill([
                'us_membresia' => $membresia
            ]);
            $user->save();
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status sms 
     * @param Request $request
     * @return boolean
     */
    public function up_sms(Request $request) {
        try {
            if ($request['status'] == '1') {
                $sms = DB::table('tu_emp_sms')->where('tes_empid', $request['id'])->count();
                if ($sms != 0) {
                    DB::table('tu_emp_sms')->where('tes_empid', $request['id'])->update([
                        'tes_envrec' => '1',
                        'tes_recopt' => '1',
                    ]);
                } else {
                    DB::table('tu_emp_sms')->insert([
                        'tes_empid' => $request['id'],
                        'tes_envrec' => '1',
                        'tes_recopt' => '1',
                        'tes_envcan' => date("Y-m-d")
                    ]);
                }
                $this->audit('Activar sms a empresa ID #' . $request['id']);
            } else {
                $sms = DB::table('tu_emp_sms')->where('tes_empid', $request['id'])->count();
                if ($sms != 0) {
                    DB::table('tu_emp_sms')->where('tes_empid', $request['id'])->update([
                        'tes_envrec' => '10',
                        'tes_recopt' => '10',
                    ]);
                }
                $this->audit('Desactivar sms a empresa ID #' . $request['id']);
            }
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status sms res
     * @param Request $request
     * @return boolean
     */
    public function up_sms_res(Request $request) {
        try {
            if ($request['status'] == '1') {
                $status = 'ALTA';
                $this->audit('Activar respuesta sms a empresa ID #' . $request['id']);
            } else {
                $business = Business::find($request['id']);
                $status = 'BAJA';
                $this->audit('Desactivar respuesta sms a empresa ID #' . $request['id']);
            }
            $business = Business::find($request['id']);
            $business->fill([
                'em_smscontrol' => $status
            ]);
            $business->save();
            return response()->json(["msg" => "borrado"]);
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
            Business::destroy($id);
            $this->audit('Eliminar empresa ID #' . $id);
            return response()->json(["msg" => "borrado"]);
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
            Audits::create([
                'activity' => $activity,
                'ip' => $this->getIp(),
                'id_user' => Auth::guard('admin')->User()->id
            ]);
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

}

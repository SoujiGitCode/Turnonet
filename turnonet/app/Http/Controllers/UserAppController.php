<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Audits;
use App\Http\Requests\UserAppCreateRequest;
use App\Http\Requests\UserAppUpdateRequest;
use App\Http\Controllers\Controller;
use App\UsersApp;
use App\Business;
use App\Mail\TuMail;
use Mail;
use Flash;
use Cache;
use DB;
use Auth;
use Session;
use Redirect;
use Illuminate\Http\Request;

class UserAppController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {


        $this->middleware('admin');
        $this->middleware('role:22');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.user_list_app');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists() {
        try {
            $user = DB::table('tu_users')->orderby('us_id', 'desc')->where('type','1')->where('level','!=','2')->where('us_esta','!=','PENDIENTE')->get();
            $json = array();
            foreach ($user as $rs):

                $email = ($rs->us_mail == '') ? 'N/A' : strtolower($rs->us_mail);
                $color = ($rs->us_esta == 'ALTA') ? 'transparent' : '#ffe9e9';
                $created_at = ($rs->us_altfec == null) ? '' : date("Y-m-d", strtotime($rs->us_altfec));

                $json[] = array(
                    "id" => $rs->us_id,
                    "name" => mb_substr($rs->us_nom, 0, 40),
                    "status" => $rs->us_esta,
                    "password" => $rs->us_recon,
                    "color" => $color,
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        try {
            return view('admin.user_app');
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
    public function store(UserAppCreateRequest $request) {
        try {

            $password = $this->getPassword();

            UsersApp::create([
                'us_nom' => $request['us_nom'],
                'us_mail' => $request['us_mail'],
                'us_contra' => md5($password),
                'us_recon' => $password,
                'us_esta' => 'ALTA',
                'us_altfec' => date("Y-m-d"),
                'us_membresia' => $request['us_membresia'],
                'status' => '1',
                'type'=>'1',
                'password' => $password,
            ]);
            $users = UsersApp::all();
            $user = $users->last();

            $this->audit('Registro de usuario ID #' . $user->us_id);


            $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($request['us_nom'], 'UTF-8', 'UTF-8'))) . '.<br/>
              <br/>
              Su registro en la aplicación ha sido procesado con éxito. Los datos que hemos recibido son los siguientes:<br />
              <br />
              <strong>Nombre y apellido:</strong>  ' . $request['us_nom'] . '<br/>
              <strong>Correo electrónico:</strong>  ' . $request['us_mail'] . '<br/>
              <strong>Contraseña:</strong>  ' . $password . '<br/>';

            $title = 'Registro exitoso';

            Mail::to($request['us_mail'], mb_convert_encoding($request['us_nom'], 'UTF-8', 'UTF-8'))->send(new TuMail('email_user', $title, $content));

            Session()->flash('notice', 'Registro Exitoso');
            return redirect::to('/users-app');
        } catch (Exception $ex) {
            return false;
        }
        return false;
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
            $user = UsersApp::find($id);
            if (isset($user) == 0)
                return redirect::to('/users-app');
            return view('admin.user_app_edit', ['user' => $user]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Show the activities user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activities($id) {
        try {
            $user = UsersApp::find($id);
            if (isset($user) == 0)
                return redirect::to('/users-app');
            return view('admin.user_app_activities', ['user' => $user]);
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
    public function update(UserAppUpdateRequest $request, $id) {
        try {

            $user = UsersApp::find($id);
            $user->fill([
                'us_nom' => $request['us_nom'],
                'us_mail' => $request['us_mail'],
                'us_membresia' => $request['us_membresia']
            ]);
            $user->save();




            //Mail::to($request['us_mail'], $request['us_nom'])->send(new TuMail('email_user', $title, $content));

            $this->audit('Actualización de datos de usuario ID #' . $id);
            Session()->flash('warning', 'Registro Actualizado');
            return redirect::to('/users-app');
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

                $this->audit('Activar usuario ID #' . $request['id']);


                $user = UsersApp::find($request['id']);

                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))) . '.<br/>
                <br/>
                Le informamos a través de este correo electrónico que su cuenta se encuentra en estado activo<br />
                <br/><br>';

                $title = 'Su usuario ha sido activado';

                Mail::to($user->us_mail, $user->us_nom)->send(new TuMail('email_user', $title, $content));
            } else {

                $status = 'BAJA';

                $user = UsersApp::find($request['id']);

                $content = 'Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))) . '.<br/>
                <br/>
                Le informamos a través de este correo electrónico que su cuenta ha sido dada de baja, para más información comunicate con el administrador del sistema<br />
                <br/><br>';

                $title = 'Su usuario ha sido dado de baja';


                Mail::to($user->us_mail, $user->us_nom)->send(new TuMail('email_single', $title, $content));


                $this->audit('Desactivar usuario ID #' . $request['id']);
            }


            $user = UsersApp::find($request['id']);
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
     * Open account user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function open_account($id) {

        try {
            $user = UsersApp::find($id);
            if (isset($user) == 0)
                return redirect::to('/users-app');

            Auth::guard('user')->login($user);

            $business = Business::where('em_uscid', Auth::guard('user')->User()->us_id)->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();

            if (count($business) == 1) {
                foreach ($business as $row) {
                    Session()->put('emp_id', $row->em_id);
                }
            }
            return redirect::to('/escritorio');
            //return view('frontend.demo_app', ['user' => $user]);
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
            UsersApp::destroy($id);
            $this->audit('Eliminar usuario ID #' . $id);
            return response()->json(["msg" => "borrado"]);
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

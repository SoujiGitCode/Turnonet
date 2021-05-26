<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Audits;
use App\Http\Controllers\Controller;
use App\Business;
use App\UsersApp;
use App\Lenders;
use App\Mail\TuMail;
use Mail;
use Flash;
use Cache;
use Auth;
use Session;
use Redirect;
use Illuminate\Http\Request;

class CmsLendersController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {


        $this->middleware('admin');
        $this->middleware('role:25');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.lenders_lists');
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
            $business = Lenders::orderby('tmsp_id', 'desc')->offset(0)->limit(5000)->get();
            $json = array();
            foreach ($business as $rs):

                $business = Business::find($rs->emp_id);
                if (isset($business) != 0) {

                    $email = ($rs->tmsp_pmail == '') ? 'N/A' : strtolower($rs->tmsp_pmail);
                    $color = ($rs->tmsp_estado == 'ALTA') ? 'transparent' : '#ffe9e9';

                    $created_at = ($rs->tmsp_fecadd == null) ? '' : date("Y-m-d", strtotime($rs->tmsp_fecadd));

                    $json[] = array(
                        "id" => $rs->tmsp_id,
                        "business" => mb_substr(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100),
                        "name" => mb_substr(mb_convert_encoding($rs->tmsp_pnom, 'UTF-8', 'UTF-8'), 0, 100),
                        "email" => mb_convert_encoding($email, 'UTF-8', 'UTF-8'),
                        "status" => $rs->tmsp_estado,
                        "color" => $color,
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
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

                $this->audit('Activar prestador ID #' . $request['id']);

                $lenders = Lenders::find($request['id']);
                $business = Business::find($lenders->emp_id);
                $user = UsersApp::find($business->em_uscid);

                $content = 'Hola, ' . $user->us_nom . '.<br/>
                <br/>
                Le informamos a través de este correo electrónico que su prestador ' . mb_convert_encoding($lenders->tmsp_pnom, 'UTF-8', 'UTF-8') . ' se encuentra en estado "activo" puede acceder en cualquier momento ingresando sus credenciales en: ' . url('/') . '<br />
                <br/><br>';

                $title = 'Su prestador ha sido activado';


                //Mail::to($user->us_mail, $user->us_nom)->send(new TuMail($title, $content));
            } else {

                $status = 'BAJA';

                $lenders = Lenders::find($request['id']);
                $business = Business::find($lenders->emp_id);
                $user = UsersApp::find($business->em_uscid);

                $content = 'Hola, ' . $user->us_nom . '.<br/>
                <br/>
                Le informamos a través de este correo electrónico que su prestador ' . mb_convert_encoding($lenders->tmsp_pnom, 'UTF-8', 'UTF-8') . ' ha sido dada de baja, para más información comunicate con el administrador del sistema<br />
                <br/><br>';

                $title = 'Su prestador ha sido dado de baja';


                //Mail::to($user->us_mail, $user->us_nom)->send(new TuMail($title, $content));


                $this->audit('Desactivar prestador ID #' . $request['id']);
            }

            $lenders = Lenders::find($request['id']);
            $lenders->fill([
                'tmsp_estado' => $status
            ]);
            $lenders->save();
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

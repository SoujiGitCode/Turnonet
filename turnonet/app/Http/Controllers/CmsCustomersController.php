<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Audits;
use App\Http\Controllers\Controller;
use App\Business;
use App\Directory;
use App\Lenders;
use App\Mail\TuMail;
use Mail;
use Flash;
use Cache;
use Auth;
use Session;
use Redirect;
use Illuminate\Http\Request;

class CmsCustomersController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {


        $this->middleware('admin');
        $this->middleware('role:24');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request) {
        try {
            return view('admin.customer_inactive_lists');
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
            $business = Directory::orderby('id', 'desc')->where('status','0')->offset(0)->limit(5000)->get();
            $json = array();
            foreach ($business as $rs):

                $business = Business::find($rs->emp_id);
                if (isset($business) != 0) {


                    $created_at = ($rs->created_at == null) ? '' : date("Y-m-d", strtotime($rs->created_at));

                    $json[] = array(
                        "id" => $rs->id,
                        "business" => mb_substr(mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100),
                        "name" => mb_substr(mb_convert_encoding($rs->name, 'UTF-8', 'UTF-8'), 0, 100),
                        "email" => mb_convert_encoding($rs->email, 'UTF-8', 'UTF-8'),
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
        

            $this->audit('Activar cliente ID #' . $request['id']);
            
            $lenders = Directory::find($request['id']);
            $lenders->fill([
                'status' => '1'
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

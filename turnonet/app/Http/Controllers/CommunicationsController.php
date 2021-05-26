<?php

namespace App\Http\Controllers;

use App\Mail\TuMAil;
use App\UsersApp;
use App\Notifications;
use App\Audits;
use App\Business;
use Illuminate\Http\Request;
use App\Http\Requests\CommunicationsCreateRequest;
use App\Http\Requests\CommunicationsUpdateRequest;
use Illuminate\Support\Facades\Notification;
use App\Communications;
use Flash;
use Session;
use Auth;
use Mail;
use Redirect;

class CommunicationsController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:27');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.communication_list');
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
            $communication = Communications::orderBy('id', 'desc')->get();
            $json = array();
            foreach ($communication as $rs):

                $json[] = array(
                    'id' => $rs->id,
                    "url"=>$rs->url,
                    "title_2" => $rs->title,
                    "title" => mb_substr($rs->title, 0, 200) . '',
                    "created_at" => date("Y-m-d H:i:s", strtotime($rs->created_at)),
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
            return view('admin.communication');
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
    public function store(CommunicationsCreateRequest $request) {
        try {


            Communications::create([
                'title' => $request['title'],
                'content' => $request['content'],
                'url' => $request['title']
            ]);
            Session()->flash('notice', 'Registro Exitoso');
            $communication = Communications::all();
            $communication = $communication->last();

            $this->audit('Registro de comunicación ID #' . $communication->id);
            $this->send_push($communication->id);
            return redirect::to('/communication');
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
            $communication = Communications::find($id);
            if (isset($communication) == 0)
                return redirect::to('/communication');


            return view('admin.communication_edit', ['communication' => $communication]);
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
    public function update(CommunicationsUpdateRequest $request, $id) {
        try {


            $communication = Communications::find($id);
            $communication->fill([
                'title' => $request['title'],
                'url' => $request['title'],
                'content' => $request['content']
            ]);
            $communication->save();
            $this->audit('Actualización comunicación ID #' . $id);
            $this->send_push($id);
            Session()->flash('warning', 'Registro Actualizado');
            return Redirect::to('/communication');
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
            Communications::destroy($id);
            $this->audit('Eliminar comunicación ID #' . $id);
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    function reenviar(Request $request) {

        try {
            $this->audit('Reenviar comunicación ID #' . $request['id']);
            $this->send_push($request['id']);

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

    /**
     * Send push notifications
     * @param type $id
     */
    public function send_push($id) {

        
        $communication = Communications::find($id);
        if (isset($communication) != 0) {

            $title = $communication->title;
            $url = $communication->url;
            $icon = '';

            $user = Business::where('em_fact','1')->offset(0)->limit(5000)->get();

            foreach ($user as $rs):

               

                Notifications::create([
                    'description' => $title,
                    "us_id" => $rs->em_uscid,
                    'tipo'=>'3',
                    'url' => $url,
                ]);

            endforeach;
        }
    }

}

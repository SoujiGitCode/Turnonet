<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\TuMail;
use App\Bugs;
use App\Audits;
use App\UsersApp;
use Flash;
use Session;
use Redirect;
use Mail;
use Auth;
use App\Http\Requests;

class CommentsController extends Controller {

    public function __construct() {
        $this->middleware('admin', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
    }

    /** Show the pages list ** */
    public function listing() {
        $comment = Bugs::orderby('created_at', 'desc')->get();
        $json = array();
        foreach ($comment as $rs) {
            $user = UsersApp::find($rs->user);
            if (isset($user) != 0) {
                $json[] = array(
                    'id' => $rs->id,
                    'name' => $user->us_nom,
                    'email' => $user->us_mail,
                    "message" => $rs->message,
                    "created_at" => date("Y-m-d H:i:s", strtotime($rs->created_at))
                );
            }
        }
        return response()->json($json);
    }

//Listar Fotos de la pagina
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
    public function store(Request $request) {
        
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $content = $request['response'];
        $title = 'Soporte Turnonet';
        Mail::to($request['email'], $request['name'])->send(new TuMail('email_single', $title, $content));
        $this->audit('Responder consulta ID #' . $id);
        Session()->flash('submit', 'Registro Exitoso');
        return redirect::to('/dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Bugs::destroy($id);
        return response()->json(["mensaje" => "borrado"]);
    }

    public function list_comment($post) {
        $comments = Bugs::where('post', $post)->get();
        return response()->json($comments);
    }

    public function delete_alls(Request $request) {
        if ($request['id'] != "") {
            for ($i = 1; $i <= substr_count($request['id'], ','); $i++) {
                $item = explode(',', $request['id']);
                Bugs::destroy($item[$i]);
            }
        }
         return response()->json(["mensaje" => "borrado"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        try {
            $buzon = Bugs::find($id);
            if (isset($buzon) == 0)
                return redirect::to('/dashboard');
            return view('admin.buzon_edit', ['buzon' => $buzon]);
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

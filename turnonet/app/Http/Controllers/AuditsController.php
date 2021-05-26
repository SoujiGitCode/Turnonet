<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Audits;
use Cache;
use Session;
use Redirect;
use Auth;
use App\Http\Requests;
use DB;

class AuditsController extends Controller {

    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:4');
    }

    public function index(Request $request) {
        try {
            $tech = Users::pluck('name', 'id');
            return view('admin.audits_list', compact('tech'));
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function listing() {
        try {
            $audits = Audits::
                    whereDate('created_at', '=', date("Y-m-d"))
                    ->orderby('created_at', 'desc')
                    ->cursor();
            $json = array();
            foreach ($audits as $rs):
                Session()->flash('id_user', $rs->id_user);
                $user = Cache::remember('users_' . Session::get('id_user'), 340, function() {
                            return Users::where('id', Session::get('id_user'))->first();
                        });
                if (isset($user)) {
                    $json[] = array(
                        "id" => $rs->id,
                        "name" => $user->name,
                        "email" => $user->email,
                        "ip" => $rs->ip,
                        "activity" => $rs->activity,
                        "created_at" => date("Y-m-d H:i:s", strtotime($rs->created_at))
                    );
                }
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function search_listing($id_user = 0, $init = 0, $end = 0) {
        try {
            if ($init == 0)
                $init = '';
            if ($end == 0)
                $end = '';

            if ($id_user == 0)
                $id_user = '';

            if ($init != '') {
                $init = explode('-', $init);
                $init = $init[2] . "-" . $init[0] . "-" . $init[1];
            }
            if ($end != '') {
                $end = explode('-', $end);
                $end = $end[2] . "-" . $end[0] . "-" . $end[1];
            }

            $audits = Audits::
                    when(!empty($id_user), function ($query) use($id_user) {
                        return $query->where('id_user', $id_user);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderby('created_at', 'asc')
                    ->cursor();

            $json = array();
            foreach ($audits as $rs):
                Session()->flash('id_user', $rs->id_user);
                $user = Cache::remember('users_' . Session::get('id_user'), 340, function() {
                            return Users::where('id', Session::get('id_user'))->first();
                        });
                if (isset($user)) {
                    $json[] = array(
                        "id" => $rs->id,
                        "name" => $user->name,
                        "email" => $user->email,
                        "ip" => $rs->ip,
                        "activity" => $rs->activity,
                        "created_at" => date("Y-m-d H:i:s", strtotime($rs->created_at))
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
     * Audit user
     * @return type
     */
    public function audit($activity) {
        try {
            Audits::create([
                'activity' => $activity,
                'ip' => $this->getIp(),
                'id_center' => Auth::guard('admin')->User()->id_center,
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

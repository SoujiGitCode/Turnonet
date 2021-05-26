<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Audits;
use App\Visits;
use DB;
use Auth;
use Artisan;
use Redirect;
use Mail;
use App\Http\Requests;
use App\Mail\BAMail;
use App\Http\Requests\AccountRequest;

class DashboardController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {

        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        try {
            return view('admin.index');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Acount data
     * @return boolean
     */
    public function account() {
        try {
            $user = Users::find(Auth::guard('admin')->User()->id);
            return view('admin.account', ['user' => $user]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Update Account data
     * @param AccountRequest $request
     * @param type $id
     * @return boolean
     */
    public function update(AccountRequest $request, $id) {
        try {
            $user = Users::find($id);
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = $request['password'];
            $user->save();
            $this->audit('Actualización de datos de la cuenta');
            Session()->flash('warning', 'Registro Actualizado');
            return Redirect::to('/my-account');
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Analytics
     * @return type
     */
    public function analytics() {

        try {

            if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
                return redirect::to('/dashboard');

            $desktop = Visits::where('device', '1')->count();
            $mobiles = Visits::where('device', '2')->count();
            $tablets = Visits::where('device', '3')->count();

            $code = Visits::select('name', 'code', DB::raw('count(*) as total'))->where('month', date("m"))->where('year', date("Y"))->groupBy('name', 'code')->where('code', '!=', '')->orderby('total', 'desc')->get();

            $days = Visits::select('name_day', 'day', DB::raw('count(*) as total'))->where('month', date("m"))->where('year', date("Y"))->groupBy('day', 'name_day')->where('day', '!=', '')->orderby('day', 'asc')->get();

            $hours = Visits::select('hour', DB::raw('count(*) as total'))->where('month', date("m"))->where('year', date("Y"))->groupBy('hour')->orderby('hour', 'asc')->get();

            $ip = Visits::select('ip', 'page', 'code', 'created_at', DB::raw('count(*) as total'))->where('month', date("m"))->where('year', date("Y"))->groupBy('ip', 'page', 'code', 'created_at')->orderby('ip', 'asc')->offset(0)->limit(200)->get();


            $datenc = date("Y-m-");
            $sms = DB::table('tu_sms')->where('tusms_envfec', 'like', '%' . $datenc . '%')->count();
            $commissions = DB::table('tu_mercadopago')->where('created_at', 'like', '%' . $datenc . '%')->sum('commission');


            $users = Visits::select('date', 'code', 'month', DB::raw('count(*) as total'))
                    ->where('month', date("m"))
                    ->where('year', date("Y"))
                    ->groupBy('month', 'date', 'code')
                    ->orderby('date', 'asc')
                    ->get();


            $users_month = Visits::select('date', DB::raw('count(*) as total'))
                    ->where('month', date("m"))
                    ->where('year', date("Y"))
                    ->groupBy('date')
                    ->orderby('date', 'asc')
                    ->get();

            return view('admin.analytics', ['desktop' => $desktop, 'mobiles' => $mobiles, 'tablets' => $tablets, 'code' => $code, 'hours' => $hours, 'users' => $users, 'days' => $days, 'ip' => $ip, 'users_month' => $users_month,'sms'=>$sms,'commissions'=>$commissions]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function analytics_date($init, $end) {
        try {

            if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
                return redirect::to('/dashboard');

            $init = explode('-', $init);
            $init = $init['2'] . '-' . $init['1'] . '-' . $init['0'];

            $end = explode('-', $end);
            $end = $end['2'] . '-' . $end['1'] . '-' . $end['0'];


            $desktop = Visits::where('device', '1')->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->count();
            $mobiles = Visits::where('device', '2')->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->count();
            $tablets = Visits::where('device', '3')->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->count();


            $code = Visits::select('name', 'code', DB::raw('count(*) as total'))->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->groupBy('name', 'code')->where('code', '!=', '')->orderby('total', 'desc')->get();

            $days = Visits::select('name_day', 'day', DB::raw('count(*) as total'))->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->groupBy('day', 'name_day')->where('day', '!=', '')->orderby('day', 'asc')->get();

            $hours = Visits::select('hour', DB::raw('count(*) as total'))->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->groupBy('hour')->orderby('hour', 'asc')->get();

            $ip = Visits::select('ip', 'page', 'code', 'created_at', DB::raw('count(*) as total'))->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->groupBy('date', 'ip', 'page', 'code', 'created_at')->orderby('total', 'desc')->groupBy('ip')->orderby('ip', 'asc')->get();

            $users = Visits::select('date', 'code', DB::raw('count(*) as total'))->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->groupBy('date', 'code')->orderby('date', 'asc')->get();


            $users_month = Visits::select('date', DB::raw('count(*) as total'))->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])->groupBy('date')->orderby('date', 'asc')->get();


        
            $sms = DB::table('tu_sms')->where('tusms_envfec', '>=',date("Y-m-d", strtotime($init)))->where('tusms_envfec', '<=',date("Y-m-d", strtotime($end)))->count();


            $commissions = DB::table('tu_mercadopago')
                ->when(!empty($init), function ($query) use($init) {
                    return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                })
                ->when(!empty($end), function ($query) use($end) {
                    return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                })->sum('commission');

            return view('admin.analytics_date', ['desktop' => $desktop, 'mobiles' => $mobiles, 'tablets' => $tablets, 'code' => $code, 'hours' => $hours, 'users' => $users, 'init' => $init, 'end' => $end, 'days' => $days, 'ip' => $ip, 'users_month' => $users_month,'sms'=>$sms,'commissions'=>$commissions]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function pages_listing() {
        try {
            $pages = Visits::select('page', DB::raw('count(*) as total'))
                            ->groupBy('page')->orderby('total', 'desc')->get();
            $json = array();
            foreach ($pages as $rs):
                $json[] = array(
                    'page' => $rs->page,
                    "views" => $rs->total
                );
            endforeach;
            return response()->json($json);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function pages_listing_date($init, $end) {
        try {
            $pages = Visits::select('page', DB::raw('count(*) as total'))
                            ->whereBetween('date', [date("Y-m-d", strtotime($init)), date("Y-m-d", strtotime($end))])
                            ->groupBy('page')->orderby('total', 'desc')->get();
            $json = array();
            foreach ($pages as $rs):
                $json[] = array(
                    'page' => $rs->page,
                    "views" => $rs->total
                );
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

    public function mantenaice(){

         Artisan::call('optimize:clear');
         return response()->json(["msg" => "creado"]);

    }

}

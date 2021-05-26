<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Visits;
use App\Activities;
use App\Business;
use App\Lenders;
use Auth;
use Redirect;

class ZoomController extends Controller {

    public function index(Request $request, $id) {
        $request->session()->put('id_prestador', $id);
        try {
            $url = env('ZOOM_AUTH');
            $url .= '?client_id=' . env('ZOOM_CLIENT_ID');
            $url .= '&redirect_uri=' . env('APP_URL') . '/redirect-zoom';
            $url .= '&response_type=code';
            return Redirect::to($url);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function redirect(Request $request) {
        $code = $request->get('code');
        $jwt = base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET'));
        $body = array(
            'grant_type' => 'authorization_code',
            'redirect_uri' => env('APP_URL') . '/redirect-zoom',
            'code' => $code
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('ZOOM_TOKEN'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic " . $jwt,
                "cache-control: no-cache",
                "content-type: multipart/form-data;",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $id = $request->session()->get('id_prestador');
        $response = json_decode($response);
        $lender = Lenders::find($id);
        $lender->fill([
            'activate_zoom' => '1',
            'token_zoom' => $response->refresh_token,
            'id_zoom' => $this->getEmail($response->access_token),
        ]);
        $lender->save();


        $lender = Lenders::find($id);

        $this->setBusiness($lender->emp_id);

        $this->audit('Asociar tu cuenta de zoom prestador' . $id);

        Session()->flash('zoom-create', 'Registro Exitoso');

        
        return redirect('prestador/configuracion/' . $id);
    }

    public function remove(Request $request) {
        $lender = Lenders::find($request['id']);
        $lender->fill([
            'activate_zoom' => '0',
            'token_zoom' => '',
            'id_zoom' => '',
        ]);
        $lender->save();


        $lender = Lenders::find($request['id']);

        $this->setBusiness($lender->emp_id);


        $this->audit('Desvincular tu cuenta de zoom prestador' . $request['id']);
        return response()->json(["msg" => "eliminado"]);
    }

    public function getEmail($token) {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer " . $token,
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $response = json_decode($response);
            return $response->users[0]->email;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function endpoint(Request $request) {
        try {
            
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

    public function getIdBusiness() {
        if (Auth::guard('user')->User()->level == 1) {
            return Auth::guard('user')->User()->us_id;
        } else {
            $get_business = Business::find(Auth::guard('user')->User()->emp_id);
            return $get_business->em_uscid;
        }
    }

    public function setBusiness($id) {
        try {
            $zoom_act = 0;
            if (Lenders::where('emp_id', $id)->where('activate_zoom', '1')->count() != 0) {
                $zoom_act = 1;
            }
            $business = Business::find($id);
            $business->fill([
                'zoom_act' => $zoom_act,
            ]);
            $business->save();
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

}

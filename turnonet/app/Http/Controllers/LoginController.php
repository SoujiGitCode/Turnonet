<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mail\TuMail;
use App\Users;
use App\Audits;
use Session;
use Redirect;
use Auth;
use DB;
use Mail;
use Illuminate\Http\Request;

class LoginController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        try {

            /**
             * Validate Session and redirect
             */
            if (Auth::guard('admin')->guest()) {
                /**
                 * Show view Login
                 */
                return view('admin.login');
            } else {
                /**
                 * Redirect 
                 */
                return Redirect::to('dashboard');
            }
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
    public function store(Request $request) {
        try {
            /**
             * Validate request with ajax
             */
            if ($request->ajax()) {

                $email = $this->descript_code($request['email']);
                $password = $this->descript_code($request['password']);

               
                if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {

                    $this->audit('Inicio de sesión');

                    return response()->json([
                                "response" => "true"
                    ]);
                } else {
                    return response()->json([
                                "response" => "false"
                    ]);
                }
               
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * logout 
     * @return type
     */
    public function logout() {
        try {
            if (!Auth::guard('admin')->guest()) {
                $this->audit('Cierre de sesión');
                Auth::guard('admin')->logout();
            }
            return Redirect::to('cms');
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function recovery() {
        try {
            if (Auth::guard('admin')->guest()) {
                return view('admin.recovery');
            } else {
                return Redirect::to('dashboard');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function store_recovery(Request $request) {

        try {

            $email = $this->descript_code($request['email']);
         
           
                if (Users::where('email', $email)->count() != 0) {

                    $password = $this->getPassword();
                    $user = Users::where('email', $email)->first();
                    $user->fill([
                        'password' => $password
                    ]);
                    $user->save();
                    $user = Users::where('email', $email)->first();

                    $content = 'Hola, ' . $user->name . '.<br/>
                    <br/>
                    Le informamos a través de este correo electrónico que su contraseña se ha recuperado con éxito, los datos para acceder a su cuenta son:<br />
                    <br />
                    <strong>Correo electrónico:</strong>  ' . $user->email . '<br/>
                    <strong>Contraseña:</strong>  ' . $password . '<br/><br/>
                    Le recomendamos que actualice su contraseña una vez que comience la sesión.<br><br>';

                    $title = 'Recuperar Contraseña';
                    Mail::to($user->email, $user->name)->send(new TuMail('email',$title, $content));

                    return response()->json(["response" => "success"]);
                } else {
                    return response()->json(["response" => "error"]);
                }
          
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
     * Decode inputs forms.
     *
     * @param  $text
     * @return $strReturn
     */
    public function descript_code($text) {
        try {
            $key = "0123456789abcdef";
            $iv = "abcdef9876543210";
            $method = 'AES-128-CBC';
            $strData = base64_decode($text);
            $strReturn = openssl_decrypt($strData, $method, $key, OPENSSL_RAW_DATA, $iv);
            return $strReturn;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

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

    public function validate_captcha_google($token) {


        try {


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => env('CAPTCHA_KEY_SECRET'), 'response' => $token)));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $arrResponse = json_decode($response, true);


            if ($arrResponse["success"] == '1' && $arrResponse["action"] == 'homepage' && $arrResponse["score"] >= 0.5) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

}

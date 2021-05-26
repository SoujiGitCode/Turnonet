<?php

namespace App\Http\Controllers;

use App\Mail\TuMail;
use verifyEmail;
use Illuminate\Http\Request;
use Route;
use App\Activities;
use App\Visits;
use App\Business;
use App\Lenders;
use App\UsersApp;
use App\Directory;
use App\Services;
use App\BusinessFields;
use App\ClientsCustomization;
use App\Shift;
use Redirect;
use DB;
use Mail;
use Session;
use Auth;
use URL;
use App\Http\Requests;

class DirectoryController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('user');
    }

    /**
     * Display directory
     * @return type
     */
    public function index(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/empresas');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/agenda');
                $subtitle = " - Directorio";
                $this->visits('Directorio');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.directory', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_diary' => '1', 'menu_directorio' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display create
     * @return type
     */
    public function create(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $get_business = Business::find($id);
                if (isset($get_business) == 0)
                    return redirect::to('/agenda');
                if ($get_business->em_uscid != $this->getIdBusiness())
                    return redirect::to('/agenda');
                $subtitle = " - Directorio - Nuevo Cliente";
                $this->visits('Directorio - Nuevo Cliente');
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.create_directory', ['subtitle' => $subtitle, 'get_business' => $get_business, 'business' => $business, 'act_diary' => '1', 'menu_directorio' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
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

            if(!$this->validateMailExistance($request['email'])){

                return response()->json(["msg" => "no-email"]);

            }


            $dni = '';
            if (isset($request['dni']) && $request['dni']!="") {
                $dni = $request['dni'];
            }

            $phone = '';
            if (isset($request['phone']) && $request['phone']!="") {
                $phone = $request['phone'];
            }



            if ($request['email'] == "") {


                UsersApp::create([
                    'us_nom' => $request['name'],
                ]);
                $users_app = UsersApp::where('us_nom',$request['name'])->orderby('us_id', 'desc')->first();
                $id_usuario = $users_app->us_id;

                Directory::create([
                    'emp_id' => $request['emp_id'],
                    'us_id' => $id_usuario,
                    'name' => $request['name'],
                    'phone'=>$phone,
                    'dni' => $dni
                ]);

                $this->audit('Registro de cliente ID #');
                return response()->json(["msg" => "registrado"]);


            } else {


                $data = Directory::where('email', $request['email'])->where('emp_id', $request['emp_id'])->where('status', '1')->count();
                if ($data != 0) {
                    return response()->json(["msg" => "error"]);
                } else {
                    $get_user = UsersApp::where('us_mail', $request['email'])->orderby('us_id', 'desc')->first();
                    if (isset($get_user) != 0) {
                        $id_usuario = $get_user->us_id;
                    } else {
                        UsersApp::create([
                            'us_nom' => $request['name'],
                            'us_mail' => $request['email']
                        ]);
                        $users_app = UsersApp::where('us_mail', $request['email'])->orderby('us_id', 'desc')->first();
                        $id_usuario = $users_app->us_id;
                    }
                    Directory::create([
                        'emp_id' => $request['emp_id'],
                        'us_id' => $id_usuario,
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'phone'=>$phone,
                        'dni' => $dni
                    ]);

                    $this->audit('Registro de cliente ID #');
                    return response()->json(["msg" => "registrado"]);
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display edit
     * @return type
     */
    public function edit(Request $request, $id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $user = Directory::find($id);
                if (isset($user) == 0)
                    return redirect::to('/directorio');
                $subtitle = " - Directorio - Actualizar Cliente";
                $this->visits('Directorio - Actualizar Cliente');
                $get_business = Business::find($user->emp_id);
                $business = Business::where('em_uscid', $this->getIdBusiness())->where('em_estado', 'ALTA')->orderby('em_uscid', 'desc')->get();
                return view('frontend.edit_directory', ['subtitle' => $subtitle, 'user' => $user, 'get_business' => $get_business, 'business' => $business, 'act_diary' => '1', 'menu_directorio' => '1']);
            } else {
                return Redirect::to('iniciar-sesion');
            }
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
    public function update(Request $request) {
        try {

            $dni = '';
            if (isset($request['dni'])) {
                $dni = $request['dni'];
            }

            $phone = '';
            if (isset($request['phone']) && $request['phone']!="") {
                $phone = $request['phone'];
            }

            $user = Directory::find($request['id']);
            $user->fill([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone'=>$phone,
                'dni' => $dni
            ]);
            $user->save();




            $user_act = UsersApp::find($user->us_id);
            $user_act->fill([
                'us_nom' => $request['name'],
                'us_mail' => $request['email'],
            ]);
            $user_act->save();


            Directory::where('us_id',$user->us_id)->update([
               'name' => $request['name'],
               'email' => $request['email'],
           ]);


            $this->audit('Actualización de datos de cliente ID #' . $request['id']);
            return response()->json(["msg" => "actualizado"]);
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
    public function update_turno(Request $request) {
        try {
            $user = Directory::find($request['id']);
            $user->fill([
                'name' => $request['name'],
                'email' => $request['email'],
            ]);
            $user->save();
            if (isset($request['date_1_dd']) && isset($request['date_1_mm']) && isset($request['date_1'])) {
                $date = $request['date_1'] . "-" . $request['date_1_mm'] . "-" . $request['date_1_dd'];
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_fecnac' => $date,
                ]);
            }
            if (isset($request['f_2'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_tipdoc' => $request['f_2'],
                ]);
            }
            if (isset($request['f_3'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_obsoc' => $request['f_3'],
                ]);
            }
            if (isset($request['f_4'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_obsocpla' => $request['f_4'],
                ]);
            }
            if (isset($request['f_5'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_numdoc' => $request['f_5'],
                ]);
            }
            if (isset($request['f_6'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_afilnum' => $request['f_6'],
                ]);
            }
            if (isset($request['f_7'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_tel' => $request['f_7'],
                ]);
            }
            if (isset($request['f_8'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_cel' => $request['f_8'],
                ]);
            }
            if (isset($request['f_9'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_gen1' => $request['f_9'],
                ]);
            }
            if (isset($request['f_10'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_gen2' => $request['f_10'],
                ]);
            }
            if (isset($request['f_11'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_gen3' => $request['f_11'],
                ]);
            }
            if (isset($request['f_12'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_gen4' => $request['f_12'],
                ]);
            }
            if (isset($request['f_13'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_gen5' => $request['f_13'],
                ]);
            }

            if (isset($request['f_14'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_gen6' => $request['f_14'],
                ]);
            }

            if (isset($request['f_15'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_gen7' => $request['f_15'],
                ]);
            }

            if (isset($request['f_16'])) {
                ClientsCustomization::where('usm_turid', $request['shift'])->update([
                    'usm_gen8' => $request['f_16'],
                ]);
            }
            $this->audit('Actualización de datos de cliente ID #' . $request['id']);
            return response()->json(["msg" => "actualizado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display data user
     * @return type
     */
    public function user_data($id, $code) {
        try {
            if (!Auth::guard('user')->guest()) {
                $user = Directory::find($id);
                $email = strtolower($user->email);
                $shift = Shift::where('tu_id', $code)->first();

                $data = ClientsCustomization::where('usm_turid', $code)->first();
                if (isset($data) != 0) {
                    if ($data->usm_fecnac == '0000-00-00') {
                        $date = '';
                    } else {
                        $date = $this->NameMonth(date("m", strtotime($data->usm_fecnac))) . ' ' . date("d", strtotime($data->usm_fecnac)) . ' del ' . date("Y", strtotime($data->usm_fecnac));
                    }
                }
                $inputs_add = BusinessFields::where('mi_empid', $shift->emp_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
                if (count($inputs_add) != 0) {
                    if (isset($data) != 0) {
                        $text_9 = '';
                        $text_10 = '';
                        $text_11 = '';
                        $text_12 = '';
                        $text_13 = '';
                        $text_14 = '';
                        $text_15 = '';
                        $text_16 = '';
                        $input_add_9 = BusinessFields::
                        where('mi_empid', $shift->emp_id)
                        ->where('mi_field', 9)
                        ->first();
                        if (isset($input_add_9) != 0) {
                            $text_9 = ucfirst(mb_strtolower($input_add_9->mi_gentxt));
                        }
                        $input_add_10 = BusinessFields::
                        where('mi_empid', $shift->emp_id)
                        ->where('mi_field', 10)
                        ->first();
                        if (isset($input_add_10) != 0) {
                            $text_10 = ucfirst(mb_strtolower($input_add_10->mi_gentxt));
                        }
                        $input_add_11 = BusinessFields::
                        where('mi_empid', $shift->emp_id)
                        ->where('mi_field', 11)
                        ->first();
                        if (isset($input_add_11) != 0) {
                            $text_11 = ucfirst(mb_strtolower($input_add_11->mi_gentxt));
                        }
                        $input_add_12 = BusinessFields::
                        where('mi_empid', $shift->emp_id)
                        ->where('mi_field', 12)
                        ->first();
                        if (isset($input_add_12) != 0) {
                            $text_12 = ucfirst(mb_strtolower($input_add_12->mi_gentxt));
                        }

                        $input_add_13 = BusinessFields::
                        where('mi_empid', $shift->emp_id)
                        ->where('mi_field', 13)
                        ->first();
                        if (isset($input_add_13) != 0) {
                            $text_13 = ucfirst(mb_strtolower($input_add_13->mi_gentxt));
                        }
                        $input_add_14 = BusinessFields::
                        where('mi_empid', $shift->emp_id)
                        ->where('mi_field', 14)
                        ->first();
                        if (isset($input_add_14) != 0) {
                            $text_14 = ucfirst(mb_strtolower($input_add_14->mi_gentxt));
                        }
                        $input_add_15 = BusinessFields::
                        where('mi_empid', $shift->emp_id)
                        ->where('mi_field', 15)
                        ->first();
                        if (isset($input_add_15) != 0) {
                            $text_15 = ucfirst(mb_strtolower($input_add_15->mi_gentxt));
                        }
                        $input_add_16 = BusinessFields::
                        where('mi_empid', $shift->emp_id)
                        ->where('mi_field', 16)
                        ->first();
                        if (isset($input_add_16) != 0) {
                            $text_16 = ucfirst(mb_strtolower($input_add_16->mi_gentxt));
                        }




                        return response()->json([
                            "id" => $id,
                            "id_data"=>$data->usm_id,
                            "code" => $shift->tu_code,
                            "phone"=>$this->format_phone($user->phone),
                            "name" => mb_strtoupper($user->name),
                            "email" => $email,
                            "usm_fecnac" => $date,
                            "usm_tipdoc" => $data->usm_tipdoc,
                            "usm_obsocpla" => $data->usm_obsocpla,
                            "usm_numdoc" => $data->usm_numdoc,
                            "usm_obsoc" => $data->usm_obsoc,
                            "usm_afilnum" => $data->usm_afilnum,
                            "usm_gen1" => ($data->usm_gen1=="") ? '' : $this->acentos($data->usm_gen1),
                            "usm_gen2" => ($data->usm_gen2=="") ? '' : $this->acentos($data->usm_gen2),
                            "usm_gen3" => ($data->usm_gen3=="") ? '' : $this->acentos($data->usm_gen3),
                            "usm_gen4" => ($data->usm_gen4=="") ? '' : $this->acentos($data->usm_gen4),
                            "usm_gen5" => ($data->usm_gen5=="") ? '' : $this->acentos($data->usm_gen5),
                            "usm_gen6" => ($data->usm_gen6=="") ? '' : $this->acentos($data->usm_gen6),
                            "usm_gen7" => ($data->usm_gen7=="") ? '' : $this->acentos($data->usm_gen7),
                            "usm_gen8" => ($data->usm_gen8=="") ? '' : $this->acentos($data->usm_gen8),
                            "text_9" => $text_9,
                            "text_10" => $text_10,
                            "text_11" => $text_11,
                            "text_12" => $text_12,
                            "text_13" => $text_13,
                            "text_14" => $text_14,
                            "text_15" => $text_15,
                            "text_16" => $text_16,
                            "usm_tel" => $this->format_phone($data->usm_tel),
                            "usm_cel" => $this->format_phone($data->usm_cel),
                        ]);
                    } else {
                        return response()->json([
                            "id" => $id,
                            "code" => $shift->tu_code,
                            "phone"=>$this->format_phone($user->phone),
                            "name" => mb_strtoupper($user->name),
                            "email" => $email,
                            "usm_fecnac" => '',
                            "usm_tipdoc" => '',
                            "usm_obsocpla" => '',
                            "usm_numdoc" => '',
                            "usm_obsoc" => '',
                            "usm_afilnum" => '',
                            "usm_gen1" => '',
                            "usm_gen2" => '',
                            "usm_gen3" => '',
                            "usm_gen4" => '',
                            "usm_gen5" => '',
                            "usm_gen6" => '',
                            "usm_gen7" => '',
                            "usm_gen8" => '',
                            "text_9" => '',
                            "text_10" => '',
                            "text_11" => '',
                            "text_12" => '',
                            "text_13" => '',
                            "text_14" => '',
                            "text_15" => '',
                            "text_16" => '',
                            "usm_tel" => '',
                            "usm_cel" => '',
                        ]);
                    }
                } else {
                    return response()->json([
                        "id" => $id,
                        "code" => $shift->tu_code,
                        "phone"=>$this->format_phone($user->phone),
                        "name" => mb_strtoupper($user->name),
                        "email" => $email,
                        "usm_fecnac" => '',
                        "usm_tipdoc" => '',
                        "usm_obsocpla" => '',
                        "usm_numdoc" => '',
                        "usm_obsoc" => '',
                        "usm_afilnum" => '',
                        "usm_gen1" => '',
                        "usm_gen2" => '',
                        "usm_gen3" => '',
                        "usm_gen4" => '',
                        "usm_gen5" => '',
                        "usm_gen6" => '',
                        "usm_gen7" => '',
                        "usm_gen8" => '',
                        "text_9" => '',
                        "text_10" => '',
                        "text_11" => '',
                        "text_12" => '',
                        "text_13" => '',
                        "text_14" => '',
                        "text_15" => '',
                        "text_16" => '',
                        "usm_tel" => '',
                        "usm_cel" => '',
                    ]);
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }
    /**
     * Display data user
     * @return type
     */
    public function user_last_data($id) {
        try {
            if (!Auth::guard('user')->guest()) {
                $user = Directory::find($id);
                $email = strtolower($user->email);


                $data = ClientsCustomization::where('usm_usid', $user->us_id)->where('usm_empid', $user->emp_id)->orderBy('usm_turid', 'desc')->first();
                if (isset($data) != 0) {
                    if ($data->usm_fecnac == '0000-00-00') {
                        $date = '';
                    } else {
                        $date = $this->NameMonth(date("m", strtotime($data->usm_fecnac))) . ' ' . date("d", strtotime($data->usm_fecnac)) . ' del ' . date("Y", strtotime($data->usm_fecnac));
                    }
                }
                $inputs_add = BusinessFields::where('mi_empid', $user->emp_id)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
                if (count($inputs_add) != 0) {
                    if (isset($data) != 0) {
                       $text_9 = '';
                       $text_10 = '';
                       $text_11 = '';
                       $text_12 = '';
                       $text_13 = '';
                       $text_14 = '';
                       $text_15 = '';
                       $text_16 = '';
                       $input_add_9 = BusinessFields::
                       where('mi_empid', $user->emp_id)
                       ->where('mi_field', 9)
                       ->first();
                       if (isset($input_add_9) != 0) {
                        $text_9 = ucfirst(mb_strtolower($input_add_9->mi_gentxt));
                    }
                    $input_add_10 = BusinessFields::
                    where('mi_empid', $user->emp_id)
                    ->where('mi_field', 10)
                    ->first();
                    if (isset($input_add_10) != 0) {
                        $text_10 = ucfirst(mb_strtolower($input_add_10->mi_gentxt));
                    }
                    $input_add_11 = BusinessFields::
                    where('mi_empid', $user->emp_id)
                    ->where('mi_field', 11)
                    ->first();
                    if (isset($input_add_11) != 0) {
                        $text_11 = ucfirst(mb_strtolower($input_add_11->mi_gentxt));
                    }
                    $input_add_12 = BusinessFields::
                    where('mi_empid', $user->emp_id)
                    ->where('mi_field', 12)
                    ->first();
                    if (isset($input_add_12) != 0) {
                        $text_12 = ucfirst(mb_strtolower($input_add_12->mi_gentxt));
                    }

                     $input_add_13 = BusinessFields::
                        where('mi_empid', $user->emp_id)
                        ->where('mi_field', 13)
                        ->first();
                        if (isset($input_add_13) != 0) {
                            $text_13 = ucfirst(mb_strtolower($input_add_13->mi_gentxt));
                        }
                        $input_add_14 = BusinessFields::
                        where('mi_empid', $user->emp_id)
                        ->where('mi_field', 14)
                        ->first();
                        if (isset($input_add_14) != 0) {
                            $text_14 = ucfirst(mb_strtolower($input_add_14->mi_gentxt));
                        }
                        $input_add_15 = BusinessFields::
                        where('mi_empid', $user->emp_id)
                        ->where('mi_field', 15)
                        ->first();
                        if (isset($input_add_15) != 0) {
                            $text_15 = ucfirst(mb_strtolower($input_add_15->mi_gentxt));
                        }
                        $input_add_16 = BusinessFields::
                        where('mi_empid', $user->emp_id)
                        ->where('mi_field', 16)
                        ->first();
                        if (isset($input_add_16) != 0) {
                            $text_16 = ucfirst(mb_strtolower($input_add_16->mi_gentxt));
                        }
                    return response()->json([
                        "id" => $id,
                        "phone"=>$this->format_phone($user->phone),
                        "name" => mb_strtoupper($user->name),
                        "email" => $email,
                        "usm_fecnac" => $date,
                        "usm_tipdoc" => $data->usm_tipdoc,
                        "usm_obsocpla" => $data->usm_obsocpla,
                        "usm_numdoc" => $data->usm_numdoc,
                        "usm_obsoc" => $data->usm_obsoc,
                        "usm_afilnum" => $data->usm_afilnum,
                        "usm_gen1" => ($data->usm_gen1=="") ? '' : $this->acentos($data->usm_gen1),
                        "usm_gen2" => ($data->usm_gen2=="") ? '' : $this->acentos($data->usm_gen2),
                        "usm_gen3" => ($data->usm_gen3=="") ? '' : $this->acentos($data->usm_gen3),
                        "usm_gen4" => ($data->usm_gen4=="") ? '' : $this->acentos($data->usm_gen4),
                        "usm_gen5" => ($data->usm_gen5=="") ? '' : $this->acentos($data->usm_gen5),
                        "usm_gen6" => ($data->usm_gen6=="") ? '' : $this->acentos($data->usm_gen6),
                        "usm_gen7" => ($data->usm_gen7=="") ? '' : $this->acentos($data->usm_gen7),
                        "usm_gen8" => ($data->usm_gen8=="") ? '' : $this->acentos($data->usm_gen8),
                        "text_9" => $text_9,
                        "text_10" => $text_10,
                        "text_11" => $text_11,
                        "text_12" => $text_12,
                        "text_13" => $text_13,
                        "text_14" => $text_14,
                        "text_15" => $text_15,
                        "text_16" => $text_16,
                        "usm_tel" => $this->format_phone($data->usm_tel),
                        "usm_cel" => $this->format_phone($data->usm_cel),
                    ]);
                } else {
                    return response()->json([
                        "id" => $id,
                        "phone"=>$this->format_phone($user->phone),
                        "name" => mb_strtoupper($user->name),
                        "email" => $email,
                        "usm_fecnac" => '',
                        "usm_tipdoc" => '',
                        "usm_obsocpla" => '',
                        "usm_numdoc" => '',
                        "usm_obsoc" => '',
                        "usm_afilnum" => '',
                        "usm_gen1" => '',
                        "usm_gen2" => '',
                        "usm_gen3" => '',
                        "usm_gen4" => '',
                        "usm_gen5" => '',
                        "usm_gen6" => '',
                        "usm_gen7" => '',
                        "usm_gen8" => '',
                        "text_9" => '',
                        "text_10" => '',
                        "text_11" => '',
                        "text_12" => '',
                        "text_13" => '',
                        "text_14" => '',
                        "text_15" => '',
                        "usm_tel" => '',
                        "usm_cel" => '',
                    ]);
                }
            } else {
                return response()->json([
                    "id" => $id,
                    "phone"=>$this->format_phone($user->phone),
                    "name" => mb_strtoupper($user->name),
                    "email" => $email,
                    "usm_fecnac" => '',
                    "usm_tipdoc" => '',
                    "usm_obsocpla" => '',
                    "usm_numdoc" => '',
                    "usm_obsoc" => '',
                    "usm_afilnum" => '',
                    "usm_gen1" => '',
                    "usm_gen2" => '',
                    "usm_gen3" => '',
                    "usm_gen4" => '',
                    "usm_gen5" => '',
                    "usm_gen6" => '',
                    "usm_gen7" => '',
                    "usm_gen8" => '',
                    "text_9" => '',
                    "text_10" => '',
                    "text_11" => '',
                    "text_12" => '',
                    "text_13" => '',
                    "text_14" => '',
                    "text_15" => '',
                    "usm_tel" => '',
                    "usm_cel" => '',
                ]);
            }
        }
    } catch (Exception $ex) {
        return false;
    }
    return false;
}

    /**
     * Display list resources
     * @return type
     */
    public function lists($business,$status) {
        try {
            $json = array();
            $users = DB::table('directory')->where('emp_id', $business)->where('status', $status)->offset(0)->limit(700)->orderby('name', 'asc')->get();
            foreach ($users as $rs) {
                if ($rs->name != "") {
                    $phone = '';
                    $shift = DB::table('tu_turnos')->where('us_id', $rs->us_id)->where('emp_id', $business)->count();
                    $data = DB::table('tu_ususmd')->where('usm_usid', $rs->us_id)->where('usm_empid', $rs->emp_id)->first();
                    if (isset($data) != 0) {
                        $phone = $this->format_phone($data->usm_tel);
                    }
                    $json[] = array(
                        "id" => $rs->id,
                        "business" => $rs->emp_id,
                        "us_id" => $rs->us_id,
                        "shift" => $shift,
                        "phone" => $phone,
                        "status"=>$rs->status,
                        "name" => mb_strtolower($rs->name),
                        "email" => strtolower($rs->email),
                    );
                }
            }
            return response()->json($this->super_unique($json, 'us_id'));
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Display list resources
     * @return type
     */
    public function lists_search($search, $business,$status) {
        try {
            $users = DB::table('directory')
            ->where('emp_id', $business)
            ->where('status', $status)
            ->where(function($query) use($search) {
                return $query->where('email', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');
            })
            ->offset(0)
            ->limit(700)
            ->orderby('name', 'asc')
            ->get();
            $json = array();
            foreach ($users as $rs) {
                if ($rs->name != "") {
                    $phone = '';
                    $shift = DB::table('tu_turnos')->where('us_id', $rs->us_id)->where('emp_id', $business)->count();
                    $data = DB::table('tu_ususmd')->where('usm_usid', $rs->us_id)->where('usm_empid', $rs->emp_id)->orderBy('usm_turid', 'desc')->first();
                    if (isset($data) != 0) {
                        $phone = $this->format_phone($data->usm_tel);
                        if ($data->usm_fecnac == '0000-00-00') {

                            $day = '';
                            $month = '';
                            $year = '';
                        } else {

                            $date = explode('-', $data->usm_fecnac);
                            $day = $date[2];
                            $month = $date[1];
                            $year = $date[0];
                        }

                        $json[] = array(
                            "id" => $rs->id,
                            "us_id" => $rs->us_id,
                            "business" => $rs->emp_id,
                            "shift" => $shift,
                            "day" => $day,
                            "month" => $month,
                            "year" => $year,
                            "usm_tipdoc" => $data->usm_tipdoc,
                            "usm_obsocpla" => $data->usm_obsocpla,
                            "usm_numdoc" => $data->usm_numdoc,
                            "usm_obsoc" => $data->usm_obsoc,
                            "usm_afilnum" => $data->usm_afilnum,
                            "usm_gen1" => $data->usm_gen1,
                            "usm_gen2" => $data->usm_gen2,
                            "usm_gen3" => $data->usm_gen3,
                            "usm_gen4" => $data->usm_gen4,
                             "usm_gen5" => $data->usm_gen5,
                            "usm_gen6" => $data->usm_gen6,
                            "usm_gen7" => $data->usm_gen7,
                            "usm_gen8" => $data->usm_gen8,
                            "usm_tel" => $this->format_phone($data->usm_tel),
                            "usm_cel" => $this->format_phone($data->usm_cel),
                            "status"=>$rs->status,
                            "name" => mb_strtolower($rs->name),
                            "email" => strtolower($rs->email),
                        );
                    } else {
                        $json[] = array(
                            "id" => $rs->id,
                            "us_id" => $rs->us_id,
                            "business" => $rs->emp_id,
                            "shift" => $shift,
                            "day" => '',
                            "month" => '',
                            "year" => '',
                            "usm_fecnac" => '',
                            "usm_tipdoc" => '',
                            "usm_obsocpla" => '',
                            "usm_numdoc" => '',
                            "usm_obsoc" => '',
                            "usm_afilnum" => '',
                            "usm_gen1" => '',
                            "usm_gen2" => '',
                            "usm_gen3" => '',
                            "usm_gen4" => '',
                            "usm_gen5" => '',
                            "usm_gen6" => '',
                            "usm_gen7" => '',
                            "usm_gen8" => '',
                            "usm_tel" => '',
                            "usm_cel" => '',
                            "status"=>$rs->status,
                            "name" => mb_strtolower($rs->name),
                            "email" => strtolower($rs->email),
                        );
                    }
                }
            }
            return response()->json($this->super_unique($json, 'us_id'));
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status shift
     * @param Request $request
     * @return boolean
     */
    public function delete_user(Request $request) {
        try {
            $user = Directory::find($request['id']);
            $user->fill([
                'status' => 0,
            ]);
            $user->save();
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status shift
     * @param Request $request
     * @return boolean
     */
    public function alta_user(Request $request) {
        try {
            $user = Directory::find($request['id']);
            $user->fill([
                'status' => 1,
            ]);
            $user->save();
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Up status shift
     * @param Request $request
     * @return boolean
     */
    public function up_asistencia(Request $request) {
        try {
            if ($request['status'] == '1') {
                $this->audit('Confirmar la asistencia del  turno ID #' . $request['id']);
            } else {
                $this->audit('Cancelar la asistencia del turno ID #' . $request['id']);
            }
            $shift = Shift::find($request['id']);
            $shift->fill([
                'tu_asist' => $request['status']
            ]);
            $shift->save();
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function up_status_directory(Request $request){


        try {
            if ($request['id'] != "") {
                for ($i = 1; $i <= substr_count($request['id'], ','); $i++) {
                    $item = explode(',', $request['id']);


                    $user = Directory::find($item[$i]);
                    $user->fill([
                        'status' => $request['status']
                    ]);
                    $user->save();
                }
            }
            $this->audit('Dar de alta especialidad ID #' . $request['id']);
            return response()->json(["msg" => "updated"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;


    }

    /**
     * Set name Day
     * @param type $day
     * @param type $lang
     * @return type
     */
    public function nameDay($day) {
        switch ($day) {
            case 0:
            $day = 'Domingo';
            break;
            case 1:
            $day = 'Lunes';
            break;
            case 2:
            $day = 'Martes';
            break;
            case 3:
            $day = 'Miércoles';
            break;
            case 4:
            $day = 'Jueves';
            break;
            case 5:
            $day = 'Viernes';
            break;
            case 6:
            $day = 'Sábado';
            break;
            default:
# code...
            break;
        }
        return $day;
    }

    /**
     * Set name month
     * @param type $month
     * @param type $lang
     * @return type
     */
    public function NameMonth($month) {
        if ($month == '01' || $month == '1') {
            $name = 'Enero';
        }
        if ($month == '02' || $month == '2') {
            $name = 'Febrero';
        }
        if ($month == '03' || $month == '3') {
            $name = 'Marzo';
        }
        if ($month == '04' || $month == '4') {
            $name = 'Abril';
        }
        if ($month == '05' || $month == '5') {
            $name = 'Mayo';
        }
        if ($month == '06' || $month == '6') {
            $name = 'Junio';
        }
        if ($month == '07' || $month == '7') {
            $name = 'Julio';
        }
        if ($month == '08' || $month == '8') {
            $name = 'Agosto';
        }if ($month == '09' || $month == '9') {
            $name = 'Septiembre';
        }
        if ($month == '10') {
            $name = 'Octubre';
        }
        if ($month == '11') {
            $name = 'Noviembre';
        }
        if ($month == '12') {
            $name = 'Diciembre';
        }
        return ucwords($name);
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
     * Get Type Device
     * @return string
     */
    public function getTypedevice() {
        try {
            $tablet_browser = 0;
            $mobile_browser = 0;
            $body_class = 'desktop';
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $tablet_browser++;
                $body_class = "tablet";
            }
            if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $mobile_browser++;
                $body_class = "mobile";
            }
            if (isset($_SERVER['HTTP_ACCEPT'])) {
                if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ( (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
                    $mobile_browser++;
                    $body_class = "mobile";
                }
            }
            $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
            $mobile_agents = array(
                'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
                'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
                'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
                'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
                'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
                'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
                'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
                'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
                'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-');
            if (in_array($mobile_ua, $mobile_agents)) {
                $mobile_browser++;
            }
            if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
                $mobile_browser++;
                $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
                if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                    $tablet_browser++;
                }
            }
            if ($tablet_browser > 0) {
                return '3';
            } else if ($mobile_browser > 0) {
                return '2';
            } else {
                return '1';
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function getDay($day) {
        try {
            if ($day == '0') {
                return 'Domingo';
            }
            if ($day == '1') {
                return 'Lunes';
            }
            if ($day == '2') {
                return 'Martes';
            }
            if ($day == '3') {
                return 'Miércoles';
            }
            if ($day == '4') {
                return 'Jueves';
            }
            if ($day == '5') {
                return 'Viernes';
            }
            if ($day == '6') {
                return 'Sábado';
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function getCodeLocation($ip) {
        try {
            $code = 'Buenos Aires F.D.';
            return $code;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function getNameLocation($ip) {
        try {
            $name = 'Buenos Aires';
            return $name;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function visits($page) {
        try {
            $visit = Visits::where('page', $page)->where('ip', $this->getIp())->where('date', date("Y-m-d"))->get();
            if (count($visit) == 0) {
                Visits::create([
                    'page' => $page,
                    'ip' => $this->getIp(),
                    'device' => $this->getTypedevice(),
                    'code' => $this->getCodeLocation($this->getIp()),
                    'name' => $this->getNameLocation($this->getIp()),
                    'date' => date("Y-m-d"),
                    'year' => date("Y"),
                    'month' => date("m"),
                    'hour' => date("H") . ':00:00',
                    'day' => date("w"),
                    'name_day' => $this->getDay(date("w"))
                ]);
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

    /**
     * Set array
     * @param type $phone
     * @return boolean|string
     */
    public function super_unique($array, $key) {
        $temp_array = array();
        foreach ($array as &$v) {
            if (!isset($temp_array[$v[$key]]))
                $temp_array[$v[$key]] = & $v;
        }
        $array = array_values($temp_array);
        return $array;
    }

    /**
     * Set Format phone
     * @param type $phone
     * @return boolean|string
     */
    public function format_phone($phone) {
        try {
            if (!isset($phone{3})) {
                return '';
            }
            $phone = preg_replace("/[^0-9]/", "", $phone);
            $length = strlen($phone);
            switch ($length) {
                case 7:
                return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                break;
                case 8:
                return preg_replace("/([0-9]{4})([0-9]{4})/", "$1-$2", $phone);
                break;
                case 9:
                return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1 $2-$3", $phone);
                break;
                case 10:
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1 $2-$3", $phone);
                break;
                case 11:
                return preg_replace("/([0-9]{1})([0-9]{2})([0-9]{4})([0-9]{4})/", "$1 $2 $3-$4", $phone);
                break;
                default:
                return $phone;
                break;
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }


    public function getIdBusiness() {

        if (Auth::guard('user')->User()->level == 1) {
            return Auth::guard('user')->User()->us_id;
        } else {

            $get_business = DB::table('tu_emps')->where('em_id',Auth::guard('user')->User()->emp_id)->first();
            return $get_business->em_uscid;
        }
    }


    public function acentos($cadena)
    {
       $search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±,Ã“,Ã ,Ã‰,Ã ,Ãš,â€œ,â€ ,Â¿,ü");
       $replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ó,Á,É,Í,Ú,\",\",¿,&uuml;");
       $cadena= str_replace($search, $replace, $cadena);

       return $cadena;
   }


    //reinaldo email existance validation


   public function  validateMailExistance($email){
      include('ValidateMailExistance.php');

      if($email=='' or $email != ''){
          return true;
      }else{
          $vmail = new verifyEmail();
          $vmail->setStreamTimeoutWait(20);
          $vmail->Debug= TRUE;
          $vmail->Debugoutput= 'html';

          $vmail->setEmailFrom('turnos@turnonet.com');

          if ($vmail->check($email)) {
            return true;
        } elseif (verifyEmail::validate($email)) {
            return false;
        } else {
            return false;
        }

    }


}

}

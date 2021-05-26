<?php

namespace App\Http\Controllers;

use App\Plans;
use App\Http\Requests\PlansCreateRequest;
use App\Http\Requests\PlansUpdateRequest;
use Illuminate\Http\Request;
use App\Audits;
use Flash;
use Session;
use Redirect;
use Auth;
use App\Http\Requests;

class PlansController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:18');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.plans_list');
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
            $plans = Plans::orderBy('position', 'asc')->get();
            $json = array();
            foreach ($plans as $rs):

               

                $json[] = array(
                    'id' => $rs->id,
                    "parse_text" => $this->parseText($rs->title),
                    "title_2" => $rs->title,
                    "title" => substr($rs->title, 0, 37) . '...',
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
     * Display list photo page
     * @return type
     */
    public function lists_photo(Request $request) {
        $plans = Plans::find($request['id']);
        $total = substr_count($plans->image, ',');
        $json=array();
        if ($plans->image != "") {
            for ($i = 1; $i <= substr_count($plans->image, ','); $i++) {
                $imagen = explode(',', $plans->image);
                $json[] = array('nombre' => $imagen[$i]);
            }
        }
        return response()->json($json);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        try {
            return view('admin.plans');
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
    public function store(PlansCreateRequest $request) {
        try {
            $planss = Plans::orderBy('position', 'asc')->offset(0)->limit(100)->get();

                    foreach ($planss as $rs):

                        $position=$rs->position+1;
                        $plans = Plans::find($rs->id);
                        $plans->fill([
                            'position' => $position,
                        ]);
                        $plans->save();

                    endforeach;

            Plans::create([
                'position'=>'0',
                'title' => $request['title'],
                'price' => $request['price'],
                'price_usd' => $request['price_usd'],
                'item_1' => $request['item_1'],
                'item_2' => $request['item_2'],
                'item_3' => $request['item_3'],
                'item_4' => $request['item_4'],
                'item_5' => $request['item_5'],
                'item_6' => $request['item_6'],
                'item_7' => $request['item_7'],
                'item_8' => $request['item_8'],
                'item_9' => $request['item_9'],
                'item_10' => $request['item_10'],
                'item_11' => $request['item_11'],
                'item_12' => $request['item_12'],
                'item_13' => $request['item_13'],
                'item_14' => $request['item_14'],
                'item_15' => $request['item_15'],
                'item_16' => $request['item_16'],
                'item_17' => $request['item_17'],
                'item_18' => $request['item_18'],
            ]);

            $plans = Plans::all();
            $plans = $plans->last();

            $this->audit('Registro de plans ID #' . $plans->id);

            Session()->flash('notice', 'Registro Exitoso');
            return redirect::to('/plans');
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
            $plans = Plans::find($id);
            if (isset($plans) == 0)
                return redirect::to('/plans');
            return view('admin.plans_edit', ['plan' => $plans]);
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
    public function update(PlansUpdateRequest $request, $id) {
        try {
            $plans = Plans::find($id);
            $plans->fill([
                'title' => $request['title'],
                'price' => $request['price'],
                'price_usd' => $request['price_usd'],
                'item_1' => $request['item_1'],
                'item_2' => $request['item_2'],
                'item_3' => $request['item_3'],
                'item_4' => $request['item_4'],
                'item_5' => $request['item_5'],
                'item_6' => $request['item_6'],
                'item_7' => $request['item_7'],
                'item_8' => $request['item_8'],
                'item_9' => $request['item_9'],
                'item_10' => $request['item_10'],
                'item_11' => $request['item_11'],
                'item_12' => $request['item_12'],
                'item_13' => $request['item_13'],
                'item_14' => $request['item_14'],
                'item_15' => $request['item_15'],
                'item_16' => $request['item_16'],
                'item_17' => $request['item_17'],
                'item_18' => $request['item_18'],
            ]);
            $plans->save();
            $this->audit('Actualización plans ID #' . $id);
            Session()->flash('warning', 'Registro Actualizado');
            return Redirect::to('/plans');
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
            Plans::destroy($id);
            $this->audit('Eliminar plans ID #' . $id);
            return response()->json(["msg" => "borrado"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Delete photo page
     * @param Request $request
     * @return type
     */
    public function delete_photo(Request $request) {
        $plans = Plans::find($request['id']);
        $plans->fill([
            'image' => $request['image'],
        ]);
        $plans->save();
        $this->audit('Eliminar imagen de plans ID #' . $request['id']);
        return response()->json(["msg" => "borrado"]);
    }

    /**
     * Move items faq
     * @param Request $request
     * @return boolean
     */
    public function move_plans(Request $request) {
        try {
            foreach ($request['item'] as $key => $value) {
                $plans = Plans::find($value);
                $plans->fill([
                    'position' => $key
                ]);
                $plans->save();
            }
            $this->audit('Ordenar banner');
            return response()->json(["msg" => "movido"]);
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
     * Parse text
     * @return type
     */
    public function parseText($value) {
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $value = str_replace($find, $repl, $value);
        return $value;
    }

}

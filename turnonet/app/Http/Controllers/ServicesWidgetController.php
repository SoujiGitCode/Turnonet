<?php

namespace App\Http\Controllers;

use App\ServicesWidgets;
use App\Http\Requests\ServicesWidgetCreateRequest;
use App\Http\Requests\ServicesWidgetUpdateRequest;
use Illuminate\Http\Request;
use App\Audits;
use Flash;
use Session;
use Redirect;
use Auth;
use App\Http\Requests;

class ServicesWidgetController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:13');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.service_list');
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
            $services = ServicesWidgets::orderBy('position', 'asc')->get();
            $json = array();
            foreach ($services as $rs):


                $json[] = array(
                    'id' => $rs->id,
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
        $services = ServicesWidgets::find($request['id']);
        $total = substr_count($services->image, ',');
        $json=array();
        if ($services->image != "") {
            for ($i = 1; $i <= substr_count($services->image, ','); $i++) {
                $imagen = explode(',', $services->image);
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
            return view('admin.service');
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
    public function store(ServicesWidgetCreateRequest $request) {
        try {
            $servicess = ServicesWidgets::orderBy('position', 'asc')->offset(0)->limit(100)->get();

                    foreach ($servicess as $rs):

                        $position=$rs->position+1;
                        $services = ServicesWidgets::find($rs->id);
                        $services->fill([
                            'position' => $position,
                        ]);
                        $services->save();

                    endforeach;

            ServicesWidgets::create([
                'position'=>'0',
                'title' => $request['title'],
                'image' => $request['image']
            ]);

            $services = ServicesWidgets::all();
            $services = $services->last();

            $this->audit('Registro de widget ID #' . $services->id);

            Session()->flash('notice', 'Registro Exitoso');
            return redirect::to('/services');
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
            $services = ServicesWidgets::find($id);
            if (isset($services) == 0)
                return redirect::to('/services');
            return view('admin.service_edit', ['service' => $services]);
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
    public function update(ServicesWidgetUpdateRequest $request, $id) {
        try {
            $services = ServicesWidgets::find($id);
            $services->fill([
                'title' => $request['title'],
                'image' => $request['image']
            ]);
            $services->save();
            $this->audit('Actualización widget ID #' . $id);
            Session()->flash('warning', 'Registro Actualizado');
            return Redirect::to('/services');
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
            ServicesWidgets::destroy($id);
            $this->audit('Eliminar widget ID #' . $id);
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
        $services = ServicesWidgets::find($request['id']);
        $services->fill([
            'image' => $request['image'],
        ]);
        $services->save();
        $this->audit('Eliminar imagen de widget ID #' . $request['id']);
        return response()->json(["msg" => "borrado"]);
    }

    /**
     * Move items faq
     * @param Request $request
     * @return boolean
     */
    public function move_services(Request $request) {
        try {
            foreach ($request['item'] as $key => $value) {
                $services = ServicesWidgets::find($value);
                $services->fill([
                    'position' => $key
                ]);
                $services->save();
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

}

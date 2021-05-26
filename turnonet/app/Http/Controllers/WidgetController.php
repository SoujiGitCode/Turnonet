<?php

namespace App\Http\Controllers;

use App\Widgets;
use App\Http\Requests\WidgetCreateRequest;
use App\Http\Requests\WidgetUpdateRequest;
use Illuminate\Http\Request;
use App\Audits;
use Flash;
use Session;
use Redirect;
use Auth;
use App\Http\Requests;

class WidgetController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:16');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.widget_list');
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
            $widget = Widgets::orderBy('position', 'asc')->get();
            $json = array();
            foreach ($widget as $rs):

               

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
        $widget = Widgets::find($request['id']);
        $total = substr_count($widget->image, ',');
        $json=array();
        if ($widget->image != "") {
            for ($i = 1; $i <= substr_count($widget->image, ','); $i++) {
                $imagen = explode(',', $widget->image);
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
            return view('admin.widget');
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
    public function store(WidgetCreateRequest $request) {
        try {
            $widgets = Widgets::orderBy('position', 'asc')->offset(0)->limit(100)->get();

                    foreach ($widgets as $rs):

                        $position=$rs->position+1;
                        $widget = Widgets::find($rs->id);
                        $widget->fill([
                            'position' => $position,
                        ]);
                        $widget->save();

                    endforeach;

            Widgets::create([
                'position'=>'0',
                'title' => $request['title'],
                'content' => $request['content'],
                'image' => $request['image']
            ]);

            $widget = Widgets::all();
            $widget = $widget->last();

            $this->audit('Registro de widget ID #' . $widget->id);

            Session()->flash('notice', 'Registro Exitoso');
            return redirect::to('/widget');
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
            $widget = Widgets::find($id);
            if (isset($widget) == 0)
                return redirect::to('/widget');
            return view('admin.widget_edit', ['widget' => $widget]);
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
    public function update(WidgetUpdateRequest $request, $id) {
        try {
            $widget = Widgets::find($id);
            $widget->fill([
                'title' => $request['title'],
                'content' => $request['content'],
                'image' => $request['image']
            ]);
            $widget->save();
            $this->audit('Actualización widget ID #' . $id);
            Session()->flash('warning', 'Registro Actualizado');
            return Redirect::to('/widget');
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
            Widgets::destroy($id);
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
        $widget = Widgets::find($request['id']);
        $widget->fill([
            'image' => $request['image'],
        ]);
        $widget->save();
        $this->audit('Eliminar imagen de widget ID #' . $request['id']);
        return response()->json(["msg" => "borrado"]);
    }

    /**
     * Move items faq
     * @param Request $request
     * @return boolean
     */
    public function move_widget(Request $request) {
        try {
            foreach ($request['item'] as $key => $value) {
                $widget = Widgets::find($value);
                $widget->fill([
                    'position' => $key
                ]);
                $widget->save();
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

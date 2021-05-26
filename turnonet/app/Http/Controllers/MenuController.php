<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Menu;
use App\Audits;
use Flash;
use Session;
use Redirect;
use Auth;
use Illuminate\Http\Request;

class MenuController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:7');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        try {
            return view('admin.menu');
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
            $menu = Menu::orderBy('position', 'asc')->get();
            return response()->json($menu);
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
        $menu = Menu::find($request['id']);
        $total = substr_count($menu->image, ',');
        $json=array();
        if ($menu->image != "") {
            for ($i = 1; $i <= substr_count($menu->image, ','); $i++) {
                $imagen = explode(',', $menu->image);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {

            $menus = Menu::orderBy('position', 'asc')->offset(0)->limit(100)->get();

            foreach ($menus as $rs):

                $position=$rs->position+1;
                $menu = Menu::find($rs->id);
                $menu->fill([
                    'position' => $position,
                ]);
                $menu->save();

            endforeach;

            Menu::create([
                'position'=>'0',
                'type' => $request['type'],
                'url' => $request['url'],
                'title' => $request['title'],
                'status' => '1'
            ]);

            $menus = Menu::all();
            $menu = $menus->last();
            $this->audit('Registro de item de menú ID #' . $menu->id);

            Session()->flash('notice', 'Registro Exitoso');
            return response()->json([
                        "msg" => "creado"
            ]);
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
            $menu = Menu::find($id);

            if (isset($menu) == 0)
                return Redirect::to('menu');
            return view('admin.menu_edit', ['menu' => $menu]);
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
    public function update(Request $request, $id) {
        try {

            $menu = Menu::find($id);
            $menu->fill([
                'type' => $request['type'],
                'url' => $request['url'],
                'title' => $request['title']
            ]);
            $menu->save();
            $this->audit('Actualización de item de menú ID #' . $id);

            Session()->flash('warning', 'Registro Actualizado');
            return response()->json([
                        "msg" => "creado"
            ]);
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
    public function up_status(Request $request) {
        try {

            if ($request['status'] == '1') {
                $this->audit('Activar  item de menú ID #' . $request['id']);
            } else {
                $this->audit('Desactivar  item de menú ID #' . $request['id']);
            }
            $menu = Menu::find($request['id']);
            $menu->fill([
                'status' => $request['status']
            ]);
            $menu->save();
            return response()->json(["msg" => "borrado"]);
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

        Menu::destroy($id);
            $this->audit('Eliminar menu ID #' . $id);
            return response()->json(["msg" => "borrado"]);
        
    }

    /**
     * Move items menu
     * @param Request $request
     * @return boolean
     */
    public function move_menu(Request $request) {
        try {
            foreach ($request['item'] as $key => $value) {
                $menu = Menu::find($value);
                $menu->fill([
                    'position' => $key
                ]);
                $menu->save();
            }
            $this->audit('Ordenar items del menú');
            return response()->json(["msg" => "movido"]);
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
        $menu = Menu::find($request['id']);
        $menu->fill([
            'image' => $request['image'],
        ]);
        $menu->save();
        $this->audit('Eliminar imagen de menu ID #' . $request['id']);
        return response()->json(["msg" => "borrado"]);
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

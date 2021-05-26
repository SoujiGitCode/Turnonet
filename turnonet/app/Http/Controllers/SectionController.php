<?php

namespace App\Http\Controllers;

use App\Sections;
use App\Http\Requests\SectionCreateRequest;
use App\Http\Requests\SectionUpdateRequest;
use Illuminate\Http\Request;
use App\Audits;
use App\Menu;
use Flash;
use Session;
use Redirect;
use Auth;
use App\Http\Requests;

class SectionController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:22');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.section_list');
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
            $section = Sections::orderby('id','asc')->get();
            $json = array();
            foreach ($section as $rs):

                $json[] = array(
                    'id' => $rs->id,
                    "parse_text" => $this->parseText($rs->title),
                    "title" => $rs->title,
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
        $section = Sections::find($request['id']);
        $total = substr_count($section->image, ',');
        $json=array();
        if ($section->image != "") {
            for ($i = 1; $i <= substr_count($section->image, ','); $i++) {
                $imagen = explode(',', $section->image);
                $json[] = array('nombre' => $imagen[$i]);
            }
        }
        return response()->json($json);
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
            $section = Sections::find($id);
            if (isset($section) == 0)
                return redirect::to('/section');
            return view('admin.section_edit', ['section' => $section]);
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
    public function update(SectionUpdateRequest $request, $id) {
        try {

           if (isset($request['status_content'])) {
            $status_content = '0';
        } else {
            $status_content = '1';
        }
        if (isset($request['status'])) {
            $status = '0';
        } else {
            $status = '1';
        }

            $section = Sections::find($id);
            $section->fill([
                'title' => $request['title'],
                'content' => $request['content'],
                'name_boton'=>$request['name_boton'],
                'name_boton_en'=>$request['name_boton_en'],
                'url_boton'=>$request['url_boton'],
                'url_boton_en'=>$request['url_boton_en'],
                'image' => $request['image'],
                'status'=>$status,
                'status_content'=>$status_content
            ]);
            $section->save();
            $this->audit('Actualización seccion ID #' . $id);
            Session()->flash('warning', 'Registro Actualizado');
            return Redirect::to('/section');
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
            Sections::destroy($id);
            $this->audit('Eliminar seccion ID #' . $id);
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
        $section = Sections::find($request['id']);
        $section->fill([
            'image' => $request['image'],
        ]);
        $section->save();
        $this->audit('Eliminar imagen de la seccion ID #' . $request['id']);
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

<?php

namespace App\Http\Controllers;

use App\Banners;
use App\Http\Requests\BannerUpdateRequest;
use Illuminate\Http\Request;
use App\Audits;
use App\Menu;
use Flash;
use Session;
use Redirect;
use Auth;
use App\Http\Requests;

class BannerController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.banner_list');
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
            $banner = Banners::orderby('id','asc')->get();
            $json = array();
            foreach ($banner as $rs):

            
                $json[] = array(
                    'id' => $rs->id,
                    "parse_name"=>$this->parseText($rs->title),
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
        $banner = Banners::find($request['id']);
        $total = substr_count($banner->image, ',');
        $json=array();
        if ($banner->image != "") {
            for ($i = 1; $i <= substr_count($banner->image, ','); $i++) {
                $imagen = explode(',', $banner->image);
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
            $banner = Banners::find($id);
            if (isset($banner) == 0)
                return redirect::to('/banner');
            return view('admin.banner_edit', ['banner' => $banner]);
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
    public function update(BannerUpdateRequest $request, $id) {
        try {
            if($request['type']=="1"){
                $image=$request['image'];
                $video='';
            }else{
                $image='';
                $video=$request['video'];
            }
            $banner = Banners::find($id);
            $banner->fill([
                'title' => $request['title'],
                'subtitle' => $request['subtitle'],
                'name_boton'=>$request['name_boton'],
                'url_boton'=>$request['url_boton'],
                'image' => $image,
                'video'=>$video
            ]);
            $banner->save();
            $this->audit('Actualización de banner ID #' . $id);
            Session()->flash('warning', 'Registro Actualizado');
            return Redirect::to('/banners');
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
            Banners::destroy($id);
            $this->audit('Eliminar banner ID #' . $id);
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
        $banner = Banners::find($request['id']);
        $banner->fill([
            'image' => $request['image'],
        ]);
        $banner->save();
        $this->audit('Eliminar imagen de banner ID #' . $request['id']);
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

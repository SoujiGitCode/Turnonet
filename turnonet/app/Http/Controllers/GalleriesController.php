<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Galleries;

class GalleriesController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {

        $this->middleware('admin',['only'=>['index','create','store','show','edit','update','destroy']]);
         $this->middleware('role:10');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $galleries = Galleries::orderBy('id', 'asc')->get();
        return view('admin.galleries',['galleries'=>$galleries]);
    }
    
    /**
     * Display list resources
     * @return type
     */
    public function lists() {
        $galleries = Galleries::orderBy('id', 'asc')->get();
        return response()->json($galleries);
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
        if ($request->ajax()) {
            Galleries::create(['image' => $request['image'],'name'=>$request['name']]);
            return response()->json([
                        "msg" => "creado"
            ]);
        }
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
            $gallery = Galleries::find($id);
            if (isset($gallery) == 0)
                return Redirect::to('galleries');

            return view('admin.galleries_edit', ['gallery' => $gallery]);
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
            if ($request->ajax()) {

if( $request['image']!=""){

  $gallery = Galleries::find($id);
               $gallery->fill([
                'image' => $request['image'],
                'name'=>$request['name']
                ]);
               $gallery->save();


}else{

      $gallery = Galleries::find($id);
               $gallery->fill([
                'name'=>$request['name']
                ]);
               $gallery->save();


}
             
                      return response()->json(["msg" => "Actualizado"]);
            }
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
        Galleries::destroy($id);
        return response()->json(["msg" => "borrado"]);
    }
    
    /**
     * Move items Galleries
     * @param Request $request
     * @return boolean
     */
    public function move_gallery(Request $request) {
        try {
            foreach ($request['item'] as $key => $value) {
                $galleries = Galleries::find($value);
                $galleries->fill([
                    'status' => $key
                ]);
                $galleries->save();
            }
            return response()->json(["msg" => "movido"]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Customers;

class CustomersController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {

        $this->middleware('admin',['only'=>['index','create','store','show','edit','update','destroy']]);
         $this->middleware('role:9');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.customers');
    }
    
    /**
     * Display list resources
     * @return type
     */
    public function lists() {
        $galleries = Customers::orderBy('status', 'asc')->get();
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

            if ($request['image'] != "") {
            for ($i = 0; $i <= substr_count($request['image'], ','); $i++) {
                $imagen = explode(',', $request['image']);
                  if($imagen[$i]!="") {
                 Customers::create(['image' => $imagen[$i],'name'=>$request['name']]);
             }
            }
        }


          
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
            $gallery = Customers::find($id);
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

    $imagen = explode(',', $request['image']);

  $gallery = Customers::find($id);
               $gallery->fill([
                'image' => $imagen[1],
                'name'=>$request['name']
                ]);
               $gallery->save();


}else{

      $gallery = Customers::find($id);
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
        Customers::destroy($id);
        return response()->json(["msg" => "borrado"]);
    }
    
    /**
     * Move items Galleries
     * @param Request $request
     * @return boolean
     */
    public function move_customer(Request $request) {
        try {
            foreach ($request['item'] as $key => $value) {
                $galleries = Customers::find($value);
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

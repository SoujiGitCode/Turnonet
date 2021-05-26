<?php

namespace App\Http\Controllers;

use App\Faqs;
use App\Http\Requests\FaqCreateRequest;
use App\Http\Requests\FaqUpdateRequest;
use Illuminate\Http\Request;
use App\Audits;
use Flash;
use Session;
use Redirect;
use Auth;
use App\Http\Requests;

class FaqController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('role:20');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            return view('admin.faq_list');
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
            $faqs = Faqs::orderBy('position', 'asc')->get();
            $json = array();
            foreach ($faqs as $rs):

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        try {
            return view('admin.faq');
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
    public function store(FaqCreateRequest $request) {
        try {
            $faqs = Faqs::orderBy('position', 'asc')->offset(0)->limit(100)->get();

            foreach ($faqs as $rs):

                $position=$rs->position+1;
                $faq = Faqs::find($rs->id);
                $faq->fill([
                    'position' => $position,
                ]);
                $faq->save();

            endforeach;
            
            Faqs::create([
                'position'=>'0',
                'title' => $request['title'],
                'video' => $request['video'],
                'url' => $request['title'],
                'image'=>$request['image'],
                'content' => $request['content']
            ]);

            $faqs = Faqs::all();
            $faq = $faqs->last();

            $this->audit('Registro de pregunta frecuente ID #' . $faq->id);

            Session()->flash('notice', 'Registro Exitoso');
            return redirect::to('/faq');
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
            $faqs = Faqs::find($id);
            if (isset($faqs) == 0)
                return redirect::to('/faq');
            return view('admin.faq_edit', ['faq' => $faqs]);
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
        $faq = Faqs::find($request['id']);
        $total = substr_count($faq->image, ',');
        $json=array();
        if ($faq->image != "") {
            for ($i = 1; $i <= substr_count($faq->image, ','); $i++) {
                $imagen = explode(',', $faq->image);
                $json[] = array('nombre' => $imagen[$i]);
            }
        }
        return response()->json($json);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FaqUpdateRequest $request, $id) {
        try {
            $faqs = Faqs::find($id);
            $faqs->fill([
                'title' => $request['title'],
                'url' => $request['title'],
                'video' => $request['video'],
                'image'=>$request['image'],
                'content' => $request['content']
            ]);
            $faqs->save();
            $this->audit('Actualización pregunta frecuente ID #' . $id);
            Session()->flash('warning', 'Registro Actualizado');
            return Redirect::to('/faq');
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
            Faqs::destroy($id);
            $this->audit('Eliminar pregunta frecuente ID #' . $id);
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
        $faq = Faqs::find($request['id']);
        $faq->fill([
            'image' => $request['image'],
        ]);
        $faq->save();
        $this->audit('Eliminar imagen de widget ID #' . $request['id']);
        return response()->json(["msg" => "borrado"]);
    }

    /**
     * Move items faq
     * @param Request $request
     * @return boolean
     */
    public function move_faq(Request $request) {
        try {
            foreach ($request['item'] as $key => $value) {
                $faq = Faqs::find($value);
                $faq->fill([
                    'position' => $key
                ]);
                $faq->save();
            }
            $this->audit('Ordenar preguntas frecuentes');
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

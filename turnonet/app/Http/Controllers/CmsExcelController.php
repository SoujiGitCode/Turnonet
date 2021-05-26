<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Audits;
use App\Activities;
use App\Bugs;
use App\Users;
use App\Communications;
use App\Business;
use App\MercadoPago;
use App\Directory;
use App\UsersApp;
use App\Lenders;
use App\Shift;
use App\Rol;
use Auth;
use Cache;
use Session;
use DB;
use Redirect;
use PDF;
use App\Exports\TuExcelSingle;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class CmsExcelController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('admin');
    }

    /**
     * generate excel administrators
     * @return type
     */
    public function administrators($init, $end) {
        set_time_limit(0);
        if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
            return redirect::to('/dashboard');
        $this->audit('Descarga reporte de administradores');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        $fileName = 'administradores_' . $init . '_' . $end . '.xlsx';
        $users = Users::
                when(!empty($init), function ($query) use($init) {
                    return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                })
                ->when(!empty($end), function ($query) use($end) {
                    return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                })
                ->where('level', '2')
                ->orderby('created_at', 'asc')
                ->offset(0)->limit(5000)
                ->get();
        if (count($users) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/user');
        }
        $json = array();
        foreach ($users as $rs) {
            $rol = Rol::find($rs->rol);
            $json[] = array(
                "id" => $rs->id,
                "name" => $rs->name,
                "email" => $rs->email,
                "rol" => $rol->name,
                "created_at" => date("Y-m-d H:i", strtotime($rs->created_at))
            );
        }
        return Excel::download(new TuExcelSingle('admins', $json), $fileName);
    }

    /**
     * generate excel activities search
     * @param string $id_user
     * @param type $init
     * @param type $end
     * @return type
     */
    public function activities($id_user, $init = 0, $end = 0) {
        set_time_limit(0);
        if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
            return redirect::to('/dashboard');
        if ($init == "0") {
            $init = '';
        }
        if ($end == "0") {
            $end = '';
        }
        $this->audit('Descarga reporte de auditoria');
        if ($id_user == 0)
            $id_user = '';
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        $fileName = 'auditoria_' . $init . '_' . $end . '.xlsx';
        $activities = Audits::
                        when(!empty($id_user), function ($query) use($id_user) {
                            return $query->where('id_user', $id_user);
                        })
                        ->when(!empty($init), function ($query) use($init) {
                            return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                        })
                        ->when(!empty($end), function ($query) use($end) {
                            return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                        })
                        ->offset(0)->limit(5000)
                        ->orderby('created_at', 'asc')->get();
        if (count($activities) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/audits');
        }
        $json = array();
        foreach ($activities as $rs) {
            $user = Users::where('id', $rs->id_user)->first();
            if (isset($user)) {
                $json[] = array(
                    "id" => $rs->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "ip" => $rs->ip,
                    "activity" => $rs->activity,
                    "created_at" => date("Y-m-d H:i", strtotime($rs->created_at))
                );
            }
        }
        return Excel::download(new TuExcelSingle('audits', $json), $fileName);
    }

    /**
     * generate excel activities search
     * @param string $id_user
     * @param type $init
     * @param type $end
     * @return type
     */
    public function activities_user($id_user, $init = 0, $end = 0) {
        set_time_limit(0);
        if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
            return redirect::to('/dashboard');
        if ($init == "0") {
            $init = '';
        }
        if ($end == "0") {
            $end = '';
        }
        $this->audit('Descarga reporte de auditoria');
        if ($id_user == 0)
            $id_user = '';
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        $fileName = 'auditoria_.' . $id_user . '_' . $init . '_' . $end . '.xlsx';
        $activities = Activities::
                        when(!empty($id_user), function ($query) use($id_user) {
                            return $query->where('id_user', $id_user);
                        })
                        ->when(!empty($init), function ($query) use($init) {
                            return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                        })
                        ->when(!empty($end), function ($query) use($end) {
                            return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                        })
                        ->offset(0)->limit(5000)
                        ->orderby('created_at', 'asc')->get();
        if (count($activities) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/audits');
        }
        $json = array();
        foreach ($activities as $rs) {
            $user = DB::table('tu_users')->where('us_id', $rs->id_user)->first();
            if (isset($user)) {
                $json[] = array(
                    "id" => $rs->id,
                    "name" => $user->us_nom,
                    "email" => $user->us_mail,
                    "ip" => $rs->ip,
                    "activity" => $rs->activity,
                    "created_at" => date("Y-m-d H:i", strtotime($rs->created_at))
                );
            }
        }
        return Excel::download(new TuExcelSingle('audits', $json), $fileName);
    }

    /**
     * generate excel users apps
     * @return type
     */
    public function users($init, $end) {
        set_time_limit(0);
        ini_set('memory_limit', '64M');
        if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
            return redirect::to('/dashboard');
        $this->audit('Descarga reporte de usuarios');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        $fileName = 'usuarios_' . $init . '_' . $end . '.xlsx';
        $titles = array('Nombre y apellido', 'Correo electrónico', 'Estatus', 'Fecha de Registro');
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($fileName);
        $writer->addRow($titles);
        $users = DB::table('tu_users')
                ->when(!empty($init), function ($query) use($init) {
                    return $query->whereDate('us_altfec', '>=', date("Y-m-d", strtotime($init)));
                })
                ->when(!empty($end), function ($query) use($end) {
                    return $query->whereDate('us_altfec', '<=', date("Y-m-d", strtotime($end)));
                })
                ->where('type', '1')
                ->where('level', '!=', '2')
                ->where('us_esta', 'ALTA')
                ->orderby('us_altfec', 'asc')
                ->offset(0)->limit(5000)
                ->get();
        if (count($users) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/users-app');
        }
        foreach ($users as $rs) {
            $email = ($rs->us_mail == '') ? 'N/A' : strtolower($rs->us_mail);
            $created_at = ($rs->us_altfec == null) ? '' : date("Y-m-d", strtotime($rs->us_altfec));
            $row = array(
                "name" => $rs->us_nom,
                "email" => $email,
                "status" => $rs->us_esta,
                "created_at" => $created_at
            );
            $writer->addRow($row);
        }
        $writer->close();
    }

    /**
     * generate excel business
     * @return type
     */
    public function business($pay, $status) {
        set_time_limit(0);
        if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
            return redirect::to('/dashboard');
        $pay = $pay;
        $status = $status;
        if ($pay == '0') {
            $pay = '';
        }
        if ($status == '0') {
            $status = '';
        }
        $this->audit('Descarga reporte de empresas');
        $fileName = 'reporte_empresas.xlsx';
        $business = DB::table('tu_emps')
                ->orderby('em_nomfan', 'asc')
                ->when(!empty($pay), function ($query) use($pay) {
                    return $query->where('em_fact', $pay);
                })
                ->when(!empty($status), function ($query) use($status) {
                    return $query->where('em_estado', $status);
                })
                ->offset(0)->limit(5000)
                ->get();
        if (count($business) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/business');
        }
        $titles = array('Usuario', 'Correo usuario', 'Empresa', 'Correo electrónico Empresa', 'Cant Pres', 'Paga', 'Turnos', 'Mes anterior', 'Mes actual', 'SMS', 'MP','Total Comision', 'Video', 'Estatus', 'Fecha de Registro');
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($fileName);
        $writer->addRow($titles);
        foreach ($business as $rs) {
            $user_id = $rs->em_uscid;
            $user = Cache::rememberForever('usuario_' . $user_id, function ()use($user_id) {
                        return DB::table('tu_users')->where('us_id', $user_id)->first();
                    });
            if (isset($user) != 0) {
                $email = ($rs->em_email == '') ? 'N/A' : strtolower($rs->em_email);
                $email_user = ($user->us_mail == '') ? 'N/A' : strtolower($user->us_mail);
                $mp = ($rs->em_mp != '1') ? 'N/A' : '% ' . $rs->commission;
                $pay = ($rs->em_fact == '1') ? 'SI' : 'NO';
                $zoom = ($rs->zoom_act == '0') ? 'NO' : 'SI';
                $created_at = ($rs->em_feccre == null) ? '' : date("Y-m-d", strtotime($rs->em_feccre));
                $row = array(
                    strtoupper($user->us_nom),
                    $email_user,
                    strtoupper(mb_convert_encoding($rs->em_nomfan, 'UTF-8', 'UTF-8')),
                    mb_convert_encoding($email, 'UTF-8', 'UTF-8'),
                    $rs->total_pres,
                    $pay,
                    $rs->total_turnos,
                    $rs->total_turnos_lastmonth,
                    $rs->total_turnos_mes,
                    $rs->total_sms,
                    $mp,
                    number_format($rs->total_commission, 2, ".", ","),
                    $zoom,
                    $rs->em_estado,
                    $created_at,
                );
                $writer->addRow($row);
            }
        }
        $writer->close();
    }

    /**
     * Format text
     * @param type $text
     * @return boolean
     */
    public function clean_text($text) {
        try {
            $content = strip_tags($text, '');
            $content = str_replace('</br>', ' ', $content);
            $content = str_replace('&aacute;', 'á', $content);
            $content = str_replace('&eacute;', 'é', $content);
            $content = str_replace('&iacute;', 'í', $content);
            $content = str_replace('&oacute;', 'ó', $content);
            $content = str_replace('&uacute;', 'ú', $content);
            $content = str_replace('&ntilde;', 'ñ', $content);
            $content = str_replace('&nbsp;', ' ', $content);
            $content = str_replace('&iquest;', '¿', $content);
            $content = str_replace('&ldquo;', '“', $content);
            $content = str_replace('&rdquo;', '”', $content);
            $content = trim($content);
            return $content;
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * generate excel messages
     * @return type
     */
    public function messages($init, $end) {
        set_time_limit(0);
        if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
            return redirect::to('/dashboard');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        $fileName = 'mensajes_' . $init . '_' . $end . '.xlsx';
        $messages = Bugs::
                when(!empty($init), function ($query) use($init) {
                    return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                })
                ->when(!empty($end), function ($query) use($end) {
                    return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                })
                ->orderby('created_at', 'asc')
                ->offset(0)->limit(5000)
                ->get();
        if (count($messages) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/dashboard');
        }
        $json = array();
        foreach ($messages as $rs) {
            $user_id = $rs->rs->user;
            $user = Cache::rememberForever('usuario_' . $user_id, function ()use($user_id) {
                        return DB::table('tu_users')->where('us_id', $user_id)->first();
                    });
            if (isset($user) != 0) {
                $json[] = array(
                    'id' => $rs->id,
                    'name' => $user->us_nom,
                    'email' => $user->us_mail,
                    'subject' => $rs->subject,
                    'message' => $rs->message,
                    'created_at' => date("Y-m-d H:i", strtotime($rs->created_at))
                );
            }
        }
        return Excel::download(new TuExcelSingle('messages', $json), $fileName);
    }

    public function pdf_reports($id, $init, $end) {
        $get_business = Business::find($id);
        if (isset($get_business) == 0)
            return redirect::to('/business');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        $shifts = Shift::
                where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->count();
        $actives = Shift::
                where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->where('tu_estado', 'ALTA')
                ->count();
        $cancel = Shift::
                where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->where('tu_estado', 'BAJA')
                ->count();
        $overturn = Shift::
                where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->where('tu_st', '1')
                ->count();
        $asistencia = Shift::
                where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->where('tu_asist', '1')
                ->count();
        $ausencia = Shift::
                where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->where('tu_asist', '0')
                ->count();
        $parcial = Shift::
                where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->where('tu_asist', '2')
                ->count();
        $no_defined = Shift::
                where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->where('tu_asist', '3')
                ->count();
        $days = Shift::
                select('tu_fec', DB::raw('count(*) as total'))
                ->where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->groupBy('tu_fec')
                ->orderby('tu_fec', 'asc')
                ->get();
        $hours = Shift::
                select('tu_hora', DB::raw('count(*) as total'))
                ->where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->groupBy('tu_hora')
                ->orderby('tu_hora', 'asc')
                ->get();
        $lenders = Shift::
                select('pres_id', DB::raw('count(*) as total'))
                ->where('emp_id', $id)
                ->where('tu_fec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tu_fec', '<=', date("Y-m-d", strtotime($end)))
                ->groupBy('pres_id')
                ->orderby('total', 'desc')
                ->get();
        $sms = DB::table('tu_sms')
                ->where('tusms_empid', $id)
                ->where('tusms_envfec', '>=', date("Y-m-d", strtotime($init)))
                ->where('tusms_envfec', '<=', date("Y-m-d", strtotime($end)))
                ->count();
        $view = view('pdf/business_report', [
            'business' => $get_business,
            'days' => $days,
            'hours' => $hours,
            "lenders" => $lenders,
            'shifts' => $shifts,
            "overturn" => $overturn,
            'actives' => $actives,
            'asistencia' => $asistencia,
            'ausencia' => $ausencia,
            'parcial' => $parcial,
            'no_defined' => $no_defined,
            'cancels' => $cancel,
            'init' => $init,
            "sms" => $sms,
            'end' => $end
        ]);
        $pdf = PDF::loadHTML($view);
        return $pdf->stream('reporte-' . $id . '-' . $init . '-' . $end . '.pdf', array("Attachment" => true));
    }

    public function commissions() {
        $this->audit('Descarga reporte de comisiones');
        $fileName = 'reporte_comisiones.xlsx';
        $lists = MercadoPago::orderby('id', 'desc')->offset(0)->limit(5000)->get();
        if (count($lists) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/payments-reports');
        }
        $titles = array('Empresa', 'ID pago', 'ID de turno', 'Monto', 'Comision', 'Fecha de Registro');
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($fileName);
        $writer->addRow($titles);
        foreach ($lists as $rs):
            $id_business = $rs->emp_id;
            $get_business = Cache::rememberForever('business_' . $id_business, function() use($id_business) {
                        return DB::table('tu_emps')->where('em_id', $id_business)->first();
                    });
            $shift = Shift::find($rs->id_turno);
            if (isset($get_business) != 0 && isset($shift) != 0) {
                $row = array(
                    mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100),
                    $rs->id_payment,
                    $shift->tu_code,
                    number_format($rs->amount, 2, ".", ","),
                    number_format($rs->commission, 2, ".", ","),
                    date("d/m/Y H:i:s", strtotime($rs->created_at))
                );
                $writer->addRow($row);
            }
        endforeach;
        $writer->close();
    }
    public function commissions_date($init, $end,$business) {
        $this->audit('Descarga reporte de comisiones');

        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
         if ($business == '0')
                $business = '';
        $fileName = 'reporte_comisiones_' . $init . '_' . $end . '.xlsx';
        $lists = MercadoPago::when(!empty($init), function ($query) use($init) {
                    return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                })
                ->when(!empty($end), function ($query) use($end) {
                    return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                })
                ->when(!empty($business), function ($query) use($business) {
                        return $query->where('emp_id',$business);
                    })
                ->orderby('created_at', 'asc')
                ->offset(0)
                ->limit(5000)
                ->get();
        if (count($lists) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/payments-reports');
        }
        $titles = array('Empresa', 'ID pago', 'ID de turno', 'Monto', 'Comision', 'Fecha de Registro');
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($fileName);
        $writer->addRow($titles);
        foreach ($lists as $rs):
            $id_business = $rs->emp_id;
            $get_business = Cache::rememberForever('business_' . $id_business, function() use($id_business) {
                        return DB::table('tu_emps')->where('em_id', $id_business)->first();
                    });
            $shift = Shift::find($rs->id_turno);
            if (isset($get_business) != 0 && isset($shift) != 0) {
                $row = array(
                    mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100),
                    $rs->id_payment,
                    $shift->tu_code,
                    number_format($rs->amount, 2, ".", ","),
                    number_format($rs->commission, 2, ".", ","),
                    date("d/m/Y H:i:s", strtotime($rs->created_at))
                );
                $writer->addRow($row);
            }
        endforeach;
        $writer->close();
    }

    /**
     * generate excel notifications
     * @return type
     */
    public function communications($init, $end) {
        if (Auth::guard('admin')->User()->level != '1' && Auth::guard('admin')->User()->rol != '1')
            return redirect::to('/dashboard');
        $this->audit('Descarga reporte de comunicaciones');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        $fileName = 'comunicaciones_' . $init . '_' . $end . '.xlsx';
        $communications = Communications::
                when(!empty($init), function ($query) use($init) {
                    return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                })
                ->when(!empty($end), function ($query) use($end) {
                    return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                })
                ->orderby('created_at', 'asc')
                ->offset(0)->limit(5000)
                ->get();
        if (count($communications) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/communication');
        }
        $json = array();
        foreach ($communications as $rs) {
            $json[] = array(
                "id" => $rs->id,
                "title" => $rs->title,
                "content" => $this->clean_text($rs->content),
                "created_at" => date("Y-m-d H:i", strtotime($rs->created_at))
            );
        }
        return Excel::download(new TuExcelSingle('communications', $json), $fileName);
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

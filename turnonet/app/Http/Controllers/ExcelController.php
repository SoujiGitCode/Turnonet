<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activities;
use App\Business;
use App\Services;
use App\Branch;
use App\UsersApp;
use App\MercadoPago;
use App\Directory;
use App\Lenders;
use App\Shift;
use App\BusinessFields;
use App\ClientsCustomization;
use Auth;
use Cache;
use Session;
use DB;
use Redirect;
use App\Exports\TuExcel;
use App\Exports\TuExcelSingle;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class ExcelController extends Controller {

    /**
     * Validate session
     */
    public function __construct() {
        $this->middleware('user');
    }

    /**
     * generate excel shift
     * @return type
     */
    public function shift($init, $end, $status, $type_shift, $lender, $branch, $business) {

        set_time_limit(0);
        ini_set('memory_limit', '64M');

        $get_business = Cache::rememberForever('business_' . $business, function() use($business) {
                    return DB::table('tu_emps')->where('em_id', $business)->first();
                });


        $name_business = $this->updateUrl(mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100));

        $this->audit('Descarga reporte de turnos');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        if ($status == "ALTA") {
            $fileName = 'turnos-solicitados-' . $name_business . '-' . $init . '-' . $end . '.xlsx';
        } else {
            $fileName = 'turnos-cancelados-' . $name_business . '-' . $init . '-' . $end . '.xlsx';
        }

        $type_shift = $type_shift;
        $tu_st = '';
        $tu_carga_user = '';
        $tu_carga_admin = '';
        if ($type_shift == 'SOBRETURNO') {
            $tu_st = '1';
        }
        if ($type_shift == 'ADMIN') {
            $tu_carga_admin = '1';
        }
        if ($type_shift == 'USER') {
            $tu_carga_user = '1';
        }
        if ($type_shift == 'ALL' || $type_shift == 'SOBRETURNO' || $type_shift == 'ADMIN' || $type_shift == 'USER') {
            $type_shift = '';
        }
        if ($lender == 'ALL') {
            $lender = '';
        }
        if ($branch == 'ALL') {
            $branch = '';
        }
        if ($type_shift == '0') {
            $shift = DB::table('tu_turnos')
                    ->where('emp_id', $business)
                    ->where('tu_fec', date("Y-m-d", strtotime($date)))
                    ->when(!empty($lender), function ($query) use($lender) {
                        return $query->where('pres_id', $lender);
                    })
                    ->when(!empty($branch), function ($query) use($branch) {
                        return $query->where('suc_id', $branch);
                    })
                    ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                        return $query->where('tu_carga', '!=', 0);
                    })
                    ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                        return $query->where('tu_carga', 0);
                    })
                    ->when(!empty($tu_st), function ($query) use($tu_st) {
                        return $query->where('tu_st', $tu_st);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->where('tu_fec', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->where('tu_fec', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderBy('tu_fec', 'ASC')
                    ->where('tu_estado', $status)
                    ->where('tu_asist', 0)
                    ->orderBy('tu_hora', 'ASC')
                    ->offset(0)->limit(2000)
                    ->get();
        } else {
            $shift = DB::table('tu_turnos')
                    ->where('emp_id', $business)
                    ->where('tu_estado', $status)
                    ->when(!empty($lender), function ($query) use($lender) {
                        return $query->where('pres_id', $lender);
                    })
                    ->when(!empty($branch), function ($query) use($branch) {
                        return $query->where('suc_id', $branch);
                    })
                    ->when(!empty($tu_st), function ($query) use($tu_st) {
                        return $query->where('tu_st', $tu_st);
                    })
                    ->when(!empty($type_shift), function ($query) use($type_shift) {
                        return $query->where('tu_asist', $type_shift);
                    })
                    ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                        return $query->where('tu_carga', '!=', 0);
                    })
                    ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                        return $query->where('tu_carga', 0);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->where('tu_fec', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->where('tu_fec', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderBy('tu_fec', 'ASC')
                    ->orderBy('suc_id', 'ASC')
                    ->orderBy('pres_id', 'ASC')
                    ->orderBy('tu_hora', 'ASC')
                    ->offset(0)->limit(2000)
                    ->get();
        }
        if (count($shift) == 0) {
            Session()->flash('no-results', 'No hay resultados');

            if (Auth::guard('user')->User()->us_id == "474536") {

                return redirect::to('agenda');
            }
            return redirect::to('/agenda/empresa/' . $business);
        }


        $inputs_add = DB::table('tu_emps_md')->where('mi_empid', $business)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();


        if ($status == "ALTA") {
            $titles = array('TURNO', 'FECHA', 'HORA', 'ESTADO', 'ASISTENCIA', 'AGENDADO', 'SOBRETURNO', 'SUCURSAL', 'PRESTADOR', 'SERVICIO', 'CLIENTE', 'CORREO ELECTRÓNICO');
        } else {
            $titles = array('TURNO', 'FECHA', 'HORA', 'ESTADO', 'ASISTENCIA', 'CANCELADO', 'MOTIVO DE CANCELACIÓN', 'SOBRETURNO', 'SUCURSAL', 'PRESTADOR', 'SERVICIO', 'CLIENTE', 'CORREO ELECTRÓNICO');
        }
        foreach ($inputs_add as $item):
            if ($item->mi_field == 1 && $item->field_report == "1"):
                array_push($titles, "FECHA DE NACIMIENTO");
            endif;
            if ($item->mi_field == 2 && $item->field_report == "1"):
                array_push($titles, "TIPO Y NRO. DE DOCUMENTO");
            endif;
            if ($item->mi_field == 3 && $item->field_report == "1"):
                array_push($titles, "OBRA SOCIAL");
            endif;
            if ($item->mi_field == 4 && $item->field_report == "1"):
                array_push($titles, "PLAN OBRA SOCIAL");
            endif;
            if ($item->mi_field == 5 && $item->field_report == "1"):
                array_push($titles, "NÚMERO DE DOCUMENTO");
            endif;
            if ($item->mi_field == 6 && $item->field_report == "1"):
                array_push($titles, "NRO. DE AFILIADO OBRA SOCIAL");
            endif;
            if ($item->mi_field == 7 && $item->field_report == "1"):
                array_push($titles, "TELÉFONO");
            endif;
            if ($item->mi_field == 8 && $item->field_report == "1"):
                array_push($titles, "CELULAR");
            endif;
            if ($item->mi_field == 9 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 10 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 11 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 12 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 13 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 14 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 15 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 16 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
        endforeach;
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($fileName);
        $writer->addRow($titles);


        foreach ($shift as $rs) {
            $services = '';
            $canceled = '';
            $motivo_canceled = '';
            if ($status == "BAJA") {
                $motivo = DB::table('tu_tucan')->where('tucan_turid', $rs->tu_id)->first();
                if (isset($motivo) != 0):
                    $motivo_canceled = $motivo->tucan_mot;
                    $person_id = $motivo->tucan_usid;
                    $person = Cache::rememberForever('person_' . $person_id, function() use($person_id) {
                                return DB::table('tu_users')->where('us_id', $person_id)->first();
                            });
                    if (isset($person) != 0):
                        $canceled = $person->us_nom;
                    endif;
                endif;
            }
            if ($rs->tu_servid != null) {
                if (substr_count($rs->tu_servid, '-') <= 0) {
                    $service_id = $rs->tu_servid;
                    $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                            });
                    if (isset($get_service) != 0) {
                        $services .= $get_service->serv_nom;
                    }
                } else {
                    for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                        $service = explode('-', $rs->tu_servid);
                        $service_id = $service[$i];
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= trim($get_service->serv_nom);
                        }
                        if ($i != substr_count($rs->tu_servid, '-')) {
                            $services .= ", ";
                        }
                    }
                }
            }
            $lender_id = $rs->pres_id;
            $branch_id = $rs->suc_id;
            $user_id = $rs->us_id;
            $business_id = $rs->emp_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                        return DB::table('tu_emps_suc')->where('suc_id', $branch_id)->first();
                    });
            $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                        return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                    });
            $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                        return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                    });
            if (isset($lender) != 0 && isset($user) != 0) {
                $detail = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                $time = (date("H", strtotime($rs->tu_hora)) <= 11) ? 'AM' : 'PM';
                $asist = $rs->tu_asist;
                if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist == "3") {
                    $asist = 4;
                }
                if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist == "3" && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                    $asist = 4;
                }
                if ($services == "") {
                    $services = "N/A";
                }
                if ($type_shift != '3') {


                    if ($asist == '1') {
                        $assistance = "ATENDIDO";
                    }
                    if ($asist == '0') {
                        $assistance = "AUSENCIA";
                    }
                    if ($asist == '2') {
                        $assistance = "ASISTENCIA PARCIAL";
                    }
                    if ($asist == '3') {
                        $assistance = "POR ANTENDER";
                    }
                    if ($asist == '4') {
                        $assistance = "EXPIRADO";
                    }
                    if ($rs->tu_carga == '0') {
                        $loaded = "PRESTADOR";
                    } else {
                        $loaded = "CLIENTE";
                    }
                    if ($rs->tu_st == '0') {
                        $overturn = "N/A";
                    } else {
                        $overturn = "SI";
                    }
                    if ($services == "") {
                        $services = "N/A";
                    } else {
                        $services = trim($services);
                    }


                    if ($status == "ALTA") {
                        $row = array(
                            strval($rs->tu_code),
                            date("d/m/Y", strtotime($rs->tu_fec)),
                            date("H:i", strtotime($rs->tu_hora)),
                            $rs->tu_estado,
                            $assistance,
                            $loaded,
                            $overturn,
                            mb_strtoupper($branch->suc_nom),
                            mb_strtoupper($lender->tmsp_pnom),
                            $services,
                            mb_strtoupper($user->name),
                            strtolower($user->email)
                        );
                    } else {
                        $row = array(
                            strval($rs->tu_code),
                            date("d/m/Y", strtotime($rs->tu_fec)),
                            date("H:i", strtotime($rs->tu_hora)),
                            $rs->tu_estado,
                            $assistance,
                            $canceled,
                            $motivo_canceled,
                            $overturn,
                            mb_strtoupper($branch->suc_nom),
                            mb_strtoupper($lender->tmsp_pnom),
                            $services,
                            mb_strtoupper($user->name),
                            strtolower($user->email)
                        );
                    }
                } else {

                    if ($asist == '1') {
                        $assistance = "ATENDIDO";
                    }
                    if ($asist == '0') {
                        $assistance = "AUSENCIA";
                    }
                    if ($asist == '2') {
                        $assistance = "ASISTENCIA PARCIAL";
                    }
                    if ($asist == '3') {
                        $assistance = "POR ANTENDER";
                    }
                    if ($asist == '4') {
                        $assistance = "EXPIRADO";
                    }
                    if ($rs->tu_carga == '0') {
                        $loaded = "PRESTADOR";
                    } else {
                        $loaded = "CLIENTE";
                    }
                    if ($rs->tu_st == '0') {
                        $overturn = "N/A";
                    } else {
                        $overturn = "SI";
                    }
                    if ($services == "") {
                        $services = "N/A";
                    } else {
                        $services = trim($services);
                    }

                    if ($asist != 4) {
                        if ($status == "ALTA") {

                            $row = array(
                                strval($rs->tu_code),
                                date("d/m/Y", strtotime($rs->tu_fec)),
                                date("H:i", strtotime($rs->tu_hora)),
                                $rs->tu_estado,
                                $assistance,
                                $loaded,
                                $overturn,
                                mb_strtoupper($branch->suc_nom),
                                mb_strtoupper($lender->tmsp_pnom),
                                $services,
                                mb_strtoupper($user->name),
                                strtolower($user->email)
                            );
                        } else {

                            $row = array(
                                strval($rs->tu_code),
                                date("d/m/Y", strtotime($rs->tu_fec)),
                                date("H:i", strtotime($rs->tu_hora)),
                                $rs->tu_estado,
                                $assistance,
                                $canceled,
                                $motivo_canceled,
                                $overturn,
                                mb_strtoupper($branch->suc_nom),
                                mb_strtoupper($lender->tmsp_pnom),
                                $services,
                                mb_strtoupper($user->name),
                                strtolower($user->email)
                            );
                        }
                    }
                }


                foreach ($inputs_add as $item):
                    if ($item->mi_field == 1 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {

                            if ($detail->usm_fecnac != "0000-00-00") {


                                array_push($row, date("d/m/Y", strtotime($detail->usm_fecnac)));
                            } else {

                                array_push($row, "");
                            }
                        }
                    endif;
                    if ($item->mi_field == 2 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_tipdoc);
                        }
                    endif;
                    if ($item->mi_field == 3 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_obsoc);
                        }
                    endif;
                    if ($item->mi_field == 4 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_obsocpla);
                        }
                    endif;
                    if ($item->mi_field == 5 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_numdoc);
                        }
                    endif;
                    if ($item->mi_field == 6 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_afilnum);
                        }
                    endif;
                    if ($item->mi_field == 7 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_tel);
                        }
                    endif;
                    if ($item->mi_field == 8 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_cel);
                        }
                    endif;
                    if ($item->mi_field == 9 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen1));
                        }
                    endif;
                    if ($item->mi_field == 10 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen2));
                        }
                    endif;
                    if ($item->mi_field == 11 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen3));
                        }
                    endif;

                    if ($item->mi_field == 12 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen4));
                        }
                    endif;

                    if ($item->mi_field == 13 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen5));
                        }
                    endif;

                    if ($item->mi_field == 14 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen6));
                        }
                    endif;

                    if ($item->mi_field == 15 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen7));
                        }
                    endif;

                    if ($item->mi_field == 16 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen8));
                        }
                    endif;

                endforeach;

                if (isset($row)) {
                    $writer->addRow($row);
                }
            }
        }
        $writer->close();
    }

    /**
     * generate excel shift register
     * @return type
     */
    public function shift_register($init, $end, $status, $type_shift, $lender, $branch, $business) {

        set_time_limit(0);
        ini_set('memory_limit', '64M');

        $get_business = Cache::rememberForever('business_' . $business, function() use($business) {
                    return DB::table('tu_emps')->where('em_id', $business)->first();
                });


        $name_business = $this->updateUrl(mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'), 0, 100));

        $this->audit('Descarga reporte de turnos');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        if ($status == "ALTA") {
            $fileName = 'registros-diarios-' . $name_business . '-' . $init . '-' . $end . '.xlsx';
        } else {
            $fileName = 'registros-diarios-' . $name_business . '-' . $init . '-' . $end . '.xlsx';
        }

        $type_shift = $type_shift;
        $tu_st = '';
        $tu_carga_user = '';
        $tu_carga_admin = '';
        if ($type_shift == 'SOBRETURNO') {
            $tu_st = '1';
        }
        if ($type_shift == 'ADMIN') {
            $tu_carga_admin = '1';
        }
        if ($type_shift == 'USER') {
            $tu_carga_user = '1';
        }
        if ($type_shift == 'ALL' || $type_shift == 'SOBRETURNO' || $type_shift == 'ADMIN' || $type_shift == 'USER') {
            $type_shift = '';
        }
        if ($lender == 'ALL') {
            $lender = '';
        }
        if ($branch == 'ALL') {
            $branch = '';
        }
        if ($type_shift == '0') {
            $shift = DB::table('tu_turnos')
                    ->where('emp_id', $business)
                    ->where('tu_estado', '!=', 'BLOQUEO')
                    ->where('tu_fec', date("Y-m-d", strtotime($date)))
                    ->when(!empty($lender), function ($query) use($lender) {
                        return $query->where('pres_id', $lender);
                    })
                    ->when(!empty($branch), function ($query) use($branch) {
                        return $query->where('suc_id', $branch);
                    })
                    ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                        return $query->where('tu_carga', '!=', 0);
                    })
                    ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                        return $query->where('tu_carga', 0);
                    })
                    ->when(!empty($tu_st), function ($query) use($tu_st) {
                        return $query->where('tu_st', $tu_st);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->where('tu_bloqfec', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->where('tu_bloqfec', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderBy('tu_fec', 'ASC')
                    ->where('tu_asist', 0)
                    ->orderBy('tu_hora', 'ASC')
                    ->offset(0)->limit(2000)
                    ->get();
        } else {
            $shift = DB::table('tu_turnos')
                    ->where('emp_id', $business)
                    ->where('tu_estado', '!=', 'BLOQUEO')
                    ->when(!empty($lender), function ($query) use($lender) {
                        return $query->where('pres_id', $lender);
                    })
                    ->when(!empty($branch), function ($query) use($branch) {
                        return $query->where('suc_id', $branch);
                    })
                    ->when(!empty($tu_st), function ($query) use($tu_st) {
                        return $query->where('tu_st', $tu_st);
                    })
                    ->when(!empty($type_shift), function ($query) use($type_shift) {
                        return $query->where('tu_asist', $type_shift);
                    })
                    ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                        return $query->where('tu_carga', '!=', 0);
                    })
                    ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                        return $query->where('tu_carga', 0);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->where('tu_bloqfec', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->where('tu_bloqfec', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderBy('tu_fec', 'ASC')
                    ->orderBy('suc_id', 'ASC')
                    ->orderBy('pres_id', 'ASC')
                    ->orderBy('tu_hora', 'ASC')
                    ->offset(0)->limit(2000)
                    ->get();
        }
        if (count($shift) == 0) {
            Session()->flash('no-results', 'No hay resultados');

            if (Auth::guard('user')->User()->us_id == "474536") {

                return redirect::to('agenda');
            }
            return redirect::to('/agenda/empresa/' . $business);
        }


        $inputs_add = DB::table('tu_emps_md')->where('mi_empid', $business)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();


        if ($status == "ALTA") {
            $titles = array('TURNO', 'FECHA', 'HORA', 'ESTADO', 'ASISTENCIA', 'AGENDADO', 'SOBRETURNO', 'SUCURSAL', 'PRESTADOR', 'SERVICIO', 'CLIENTE', 'CORREO ELECTRÓNICO');
        } else {
            $titles = array('TURNO', 'FECHA', 'HORA', 'ESTADO', 'ASISTENCIA', 'CANCELADO', 'MOTIVO DE CANCELACIÓN', 'SOBRETURNO', 'SUCURSAL', 'PRESTADOR', 'SERVICIO', 'CLIENTE', 'CORREO ELECTRÓNICO');
        }
        foreach ($inputs_add as $item):
            if ($item->mi_field == 1 && $item->field_report == "1"):
                array_push($titles, "FECHA DE NACIMIENTO");
            endif;
            if ($item->mi_field == 2 && $item->field_report == "1"):
                array_push($titles, "TIPO Y NRO. DE DOCUMENTO");
            endif;
            if ($item->mi_field == 3 && $item->field_report == "1"):
                array_push($titles, "OBRA SOCIAL");
            endif;
            if ($item->mi_field == 4 && $item->field_report == "1"):
                array_push($titles, "PLAN OBRA SOCIAL");
            endif;
            if ($item->mi_field == 5 && $item->field_report == "1"):
                array_push($titles, "NÚMERO DE DOCUMENTO");
            endif;
            if ($item->mi_field == 6 && $item->field_report == "1"):
                array_push($titles, "NRO. DE AFILIADO OBRA SOCIAL");
            endif;
            if ($item->mi_field == 7 && $item->field_report == "1"):
                array_push($titles, "TELÉFONO");
            endif;
            if ($item->mi_field == 8 && $item->field_report == "1"):
                array_push($titles, "CELULAR");
            endif;
            if ($item->mi_field == 9 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 10 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 11 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 12 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 13 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 14 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 15 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 16 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
        endforeach;
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($fileName);
        $writer->addRow($titles);


        foreach ($shift as $rs) {
            $services = '';
            $canceled = '';
            $motivo_canceled = '';
            if ($status == "BAJA") {
                $motivo = DB::table('tu_tucan')->where('tucan_turid', $rs->tu_id)->first();
                if (isset($motivo) != 0):
                    $motivo_canceled = $motivo->tucan_mot;
                    $person_id = $motivo->tucan_usid;
                    $person = Cache::rememberForever('person_' . $person_id, function() use($person_id) {
                                return DB::table('tu_users')->where('us_id', $person_id)->first();
                            });
                    if (isset($person) != 0):
                        $canceled = $person->us_nom;
                    endif;
                endif;
            }
            if ($rs->tu_servid != null) {
                if (substr_count($rs->tu_servid, '-') <= 0) {
                    $service_id = $rs->tu_servid;
                    $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                            });
                    if (isset($get_service) != 0) {
                        $services .= $get_service->serv_nom;
                    }
                } else {
                    for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                        $service = explode('-', $rs->tu_servid);
                        $service_id = $service[$i];
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= trim($get_service->serv_nom);
                        }
                        if ($i != substr_count($rs->tu_servid, '-')) {
                            $services .= ", ";
                        }
                    }
                }
            }
            $lender_id = $rs->pres_id;
            $branch_id = $rs->suc_id;
            $user_id = $rs->us_id;
            $business_id = $rs->emp_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                        return DB::table('tu_emps_suc')->where('suc_id', $branch_id)->first();
                    });
            $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                        return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                    });
            $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                        return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                    });
            if (isset($lender) != 0 && isset($user) != 0) {
                $detail = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                $time = (date("H", strtotime($rs->tu_hora)) <= 11) ? 'AM' : 'PM';
                $asist = $rs->tu_asist;
                if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist == "3") {
                    $asist = 4;
                }
                if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist == "3" && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                    $asist = 4;
                }
                if ($services == "") {
                    $services = "N/A";
                }
                if ($type_shift != '3') {


                    if ($asist == '1') {
                        $assistance = "ATENDIDO";
                    }
                    if ($asist == '0') {
                        $assistance = "AUSENCIA";
                    }
                    if ($asist == '2') {
                        $assistance = "ASISTENCIA PARCIAL";
                    }
                    if ($asist == '3') {
                        $assistance = "POR ANTENDER";
                    }
                    if ($asist == '4') {
                        $assistance = "EXPIRADO";
                    }
                    if ($rs->tu_carga == '0') {
                        $loaded = "PRESTADOR";
                    } else {
                        $loaded = "CLIENTE";
                    }
                    if ($rs->tu_st == '0') {
                        $overturn = "N/A";
                    } else {
                        $overturn = "SI";
                    }
                    if ($services == "") {
                        $services = "N/A";
                    } else {
                        $services = trim($services);
                    }


                    if ($status == "ALTA") {
                        $row = array(
                            strval($rs->tu_code),
                            date("d/m/Y", strtotime($rs->tu_fec)),
                            date("H:i", strtotime($rs->tu_hora)),
                            $rs->tu_estado,
                            $assistance,
                            $loaded,
                            $overturn,
                            mb_strtoupper($branch->suc_nom),
                            mb_strtoupper($lender->tmsp_pnom),
                            $services,
                            mb_strtoupper($user->name),
                            strtolower($user->email)
                        );
                    } else {
                        $row = array(
                            strval($rs->tu_code),
                            date("d/m/Y", strtotime($rs->tu_fec)),
                            date("H:i", strtotime($rs->tu_hora)),
                            $rs->tu_estado,
                            $assistance,
                            $canceled,
                            $motivo_canceled,
                            $overturn,
                            mb_strtoupper($branch->suc_nom),
                            mb_strtoupper($lender->tmsp_pnom),
                            $services,
                            mb_strtoupper($user->name),
                            strtolower($user->email)
                        );
                    }
                } else {

                    if ($asist == '1') {
                        $assistance = "ATENDIDO";
                    }
                    if ($asist == '0') {
                        $assistance = "AUSENCIA";
                    }
                    if ($asist == '2') {
                        $assistance = "ASISTENCIA PARCIAL";
                    }
                    if ($asist == '3') {
                        $assistance = "POR ANTENDER";
                    }
                    if ($asist == '4') {
                        $assistance = "EXPIRADO";
                    }

                    if ($rs->tu_carga == '0') {
                        $loaded = "PRESTADOR";
                    } else {
                        $loaded = "CLIENTE";
                    }
                    if ($rs->tu_st == '0') {
                        $overturn = "N/A";
                    } else {
                        $overturn = "SI";
                    }
                    if ($services == "") {
                        $services = "N/A";
                    } else {
                        $services = trim($services);
                    }

                    if ($asist != 4) {
                        if ($status == "ALTA") {

                            $row = array(
                                strval($rs->tu_code),
                                date("d/m/Y", strtotime($rs->tu_fec)),
                                date("H:i", strtotime($rs->tu_hora)),
                                $rs->tu_estado,
                                $assistance,
                                $loaded,
                                $overturn,
                                mb_strtoupper($branch->suc_nom),
                                mb_strtoupper($lender->tmsp_pnom),
                                $services,
                                mb_strtoupper($user->name),
                                strtolower($user->email)
                            );
                        } else {

                            $row = array(
                                strval($rs->tu_code),
                                date("d/m/Y", strtotime($rs->tu_fec)),
                                date("H:i", strtotime($rs->tu_hora)),
                                $rs->tu_estado,
                                $assistance,
                                $canceled,
                                $motivo_canceled,
                                $overturn,
                                mb_strtoupper($branch->suc_nom),
                                mb_strtoupper($lender->tmsp_pnom),
                                $services,
                                mb_strtoupper($user->name),
                                strtolower($user->email)
                            );
                        }
                    }
                }


                foreach ($inputs_add as $item):
                    if ($item->mi_field == 1 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {

                            if ($detail->usm_fecnac != "0000-00-00") {


                                array_push($row, date("d/m/Y", strtotime($detail->usm_fecnac)));
                            } else {

                                array_push($row, "");
                            }
                        }
                    endif;
                    if ($item->mi_field == 2 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_tipdoc);
                        }
                    endif;
                    if ($item->mi_field == 3 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_obsoc);
                        }
                    endif;
                    if ($item->mi_field == 4 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_obsocpla);
                        }
                    endif;
                    if ($item->mi_field == 5 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_numdoc);
                        }
                    endif;
                    if ($item->mi_field == 6 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_afilnum);
                        }
                    endif;
                    if ($item->mi_field == 7 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_tel);
                        }
                    endif;
                    if ($item->mi_field == 8 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_cel);
                        }
                    endif;
                    if ($item->mi_field == 9 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen1));
                        }
                    endif;
                    if ($item->mi_field == 10 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen2));
                        }
                    endif;
                    if ($item->mi_field == 11 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen3));
                        }
                    endif;
                    if ($item->mi_field == 12 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen4));
                        }
                    endif;

                    if ($item->mi_field == 13 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen5));
                        }
                    endif;

                    if ($item->mi_field == 14 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen6));
                        }
                    endif;

                    if ($item->mi_field == 15 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen7));
                        }
                    endif;

                    if ($item->mi_field == 16 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen8));
                        }
                    endif;
                endforeach;


                $writer->addRow($row);
            }
        }
        $writer->close();
    }

    /**
     * generate excel shift
     * @return type
     */
    public function shift_sodimac($init, $end, $status, $type_shift) {

        set_time_limit(0);
        ini_set('memory_limit', '120M');


        $name_business = "sodimac";

        $this->audit('Descarga reporte de turnos');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        if ($status == "ALTA") {
            $fileName = 'turnos-solicitados-' . $name_business . '-' . $init . '-' . $end . '.xlsx';
        } else {
            $fileName = 'turnos-cancelados-' . $name_business . '-' . $init . '-' . $end . '.xlsx';
        }

        $type_shift = $type_shift;
        $tu_st = '';
        $tu_carga_user = '';
        $tu_carga_admin = '';
        if ($type_shift == 'SOBRETURNO') {
            $tu_st = '1';
        }
        if ($type_shift == 'ADMIN') {
            $tu_carga_admin = '1';
        }
        if ($type_shift == 'USER') {
            $tu_carga_user = '1';
        }
        if ($type_shift == 'ALL' || $type_shift == 'SOBRETURNO' || $type_shift == 'ADMIN' || $type_shift == 'USER') {
            $type_shift = '';
        }

        if ($type_shift == '0') {
            $shift = DB::table('tu_turnos')
                    ->where(function($q) {
                        $q->Where('emp_id', '2514')
                        ->orWhere('emp_id', '2128')
                        ->orWhere('emp_id', '2112')
                        ->orWhere('emp_id', '1728')
                        ->orWhere('emp_id', '1706')
                        ->orWhere('emp_id', '1687')
                        ->orWhere('emp_id', '1688')
                        ->orWhere('emp_id', '1689')
                        ->orWhere('emp_id', '1617')
                        ->orWhere('emp_id', '1544')
                        ->orWhere('emp_id', '1545')
                        ->orWhere('emp_id', '1546')
                        ->orWhere('emp_id', '1548')
                        ->orWhere('emp_id', '1549')
                        ->orWhere('emp_id', '1550')
                        ->orWhere('emp_id', '1551')
                        ->orWhere('emp_id', '1345');
                    })
                    ->where('tu_fec', date("Y-m-d", strtotime($date)))
                    ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                        return $query->where('tu_carga', '!=', 0);
                    })
                    ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                        return $query->where('tu_carga', 0);
                    })
                    ->when(!empty($tu_st), function ($query) use($tu_st) {
                        return $query->where('tu_st', $tu_st);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->where('tu_fec', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->where('tu_fec', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderBy('tu_fec', 'ASC')
                    ->where('tu_estado', $status)
                    ->where('tu_asist', 0)
                    ->orderBy('tu_hora', 'ASC')
                    ->offset(0)->limit(5000)
                    ->get();
        } else {
            $shift = DB::table('tu_turnos')
                    ->where(function($q) {
                        $q->Where('emp_id', '2514')
                        ->orWhere('emp_id', '2128')
                        ->orWhere('emp_id', '2112')
                        ->orWhere('emp_id', '1728')
                        ->orWhere('emp_id', '1706')
                        ->orWhere('emp_id', '1687')
                        ->orWhere('emp_id', '1688')
                        ->orWhere('emp_id', '1689')
                        ->orWhere('emp_id', '1617')
                        ->orWhere('emp_id', '1544')
                        ->orWhere('emp_id', '1545')
                        ->orWhere('emp_id', '1546')
                        ->orWhere('emp_id', '1548')
                        ->orWhere('emp_id', '1549')
                        ->orWhere('emp_id', '1550')
                        ->orWhere('emp_id', '1551')
                        ->orWhere('emp_id', '1345');
                    })
                    ->where('tu_estado', $status)
                    ->when(!empty($tu_st), function ($query) use($tu_st) {
                        return $query->where('tu_st', $tu_st);
                    })
                    ->when(!empty($type_shift), function ($query) use($type_shift) {
                        return $query->where('tu_asist', $type_shift);
                    })
                    ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                        return $query->where('tu_carga', '!=', 0);
                    })
                    ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                        return $query->where('tu_carga', 0);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->where('tu_fec', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->where('tu_fec', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderBy('tu_fec', 'ASC')
                    ->orderBy('suc_id', 'ASC')
                    ->orderBy('pres_id', 'ASC')
                    ->orderBy('tu_hora', 'ASC')
                    ->offset(0)->limit(5000)
                    ->get();
        }
        if (count($shift) == 0) {
            Session()->flash('no-results', 'No hay resultados');

            if (Auth::guard('user')->User()->us_id == "474536") {

                return redirect::to('agenda');
            }
            return redirect::to('/agenda/empresa/2128');
        }



        if ($status == "ALTA") {
            $titles = array('TURNO', 'FECHA', 'HORA', 'ESTADO', 'ASISTENCIA', 'AGENDADO', 'SOBRETURNO', 'EMPRESA', 'SUCURSAL', 'PRESTADOR', 'SERVICIO', 'CLIENTE', 'CORREO ELECTRÓNICO', 'CELULAR', 'PROVEEDOR', 'TIPO DE VEHICULO', 'CANT. PALLETS', 'N° DE ORDEN DE COMPRA');
        } else {
            $titles = array('TURNO', 'FECHA', 'HORA', 'ESTADO', 'ASISTENCIA', 'CANCELADO', 'MOTIVO DE CANCELACIÓN', 'SOBRETURNO', 'EMPRESA', 'SUCURSAL', 'PRESTADOR', 'SERVICIO', 'CLIENTE', 'CORREO ELECTRÓNICO', 'CELULAR', 'PROVEEDOR', 'TIPO DE VEHICULO', 'CANT. PALLETS', 'N° DE ORDEN DE COMPRA');
        }

        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($fileName);
        $writer->addRow($titles);


        foreach ($shift as $rs) {
            $services = '';
            $canceled = '';
            $motivo_canceled = '';
            if ($status == "BAJA") {
                $motivo = DB::table('tu_tucan')->where('tucan_turid', $rs->tu_id)->first();
                if (isset($motivo) != 0):
                    $motivo_canceled = $motivo->tucan_mot;
                    $person_id = $motivo->tucan_usid;
                    $person = Cache::rememberForever('person_' . $person_id, function() use($person_id) {
                                return DB::table('tu_users')->where('us_id', $person_id)->first();
                            });
                    if (isset($person) != 0):
                        $canceled = $person->us_nom;
                    endif;
                endif;
            }
            if ($rs->tu_servid != null) {
                if (substr_count($rs->tu_servid, '-') <= 0) {
                    $service_id = $rs->tu_servid;
                    $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                            });
                    if (isset($get_service) != 0) {
                        $services .= $get_service->serv_nom;
                    }
                } else {
                    for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                        $service = explode('-', $rs->tu_servid);
                        $service_id = $service[$i];
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= trim($get_service->serv_nom);
                        }
                        if ($i != substr_count($rs->tu_servid, '-')) {
                            $services .= ", ";
                        }
                    }
                }
            }
            $lender_id = $rs->pres_id;
            $branch_id = $rs->suc_id;
            $user_id = $rs->us_id;
            $business_id = $rs->emp_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                        return DB::table('tu_emps_suc')->where('suc_id', $branch_id)->first();
                    });
            $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                        return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                    });
            $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                        return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                    });
            if (isset($lender) != 0 && isset($user) != 0) {
                $detail = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                $time = (date("H", strtotime($rs->tu_hora)) <= 11) ? 'AM' : 'PM';
                $asist = $rs->tu_asist;
                if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist == "3") {
                    $asist = 4;
                }
                if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist == "3" && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                    $asist = 4;
                }
                if ($services == "") {
                    $services = "N/A";
                }
                if ($type_shift != '3') {


                    if ($asist == '1') {
                        $assistance = "ATENDIDO";
                    }
                    if ($asist == '0') {
                        $assistance = "AUSENCIA";
                    }
                    if ($asist == '2') {
                        $assistance = "ASISTENCIA PARCIAL";
                    }
                    if ($asist == '3') {
                        $assistance = "POR ANTENDER";
                    }
                    if ($asist == '4') {
                        $assistance = "EXPIRADO";
                    }
                    if ($rs->tu_carga == '0') {
                        $loaded = "PRESTADOR";
                    } else {
                        $loaded = "CLIENTE";
                    }
                    if ($rs->tu_st == '0') {
                        $overturn = "N/A";
                    } else {
                        $overturn = "SI";
                    }
                    if ($services == "") {
                        $services = "N/A";
                    } else {
                        $services = trim($services);
                    }


                    if ($status == "ALTA") {
                        $row = array(
                            strval($rs->tu_code),
                            date("d/m/Y", strtotime($rs->tu_fec)),
                            date("H:i", strtotime($rs->tu_hora)),
                            $rs->tu_estado,
                            $assistance,
                            $loaded,
                            $overturn,
                            mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8')),
                            mb_strtoupper($branch->suc_nom),
                            mb_strtoupper($lender->tmsp_pnom),
                            $services,
                            mb_strtoupper($user->name),
                            strtolower($user->email)
                        );
                    } else {
                        $row = array(
                            strval($rs->tu_code),
                            date("d/m/Y", strtotime($rs->tu_fec)),
                            date("H:i", strtotime($rs->tu_hora)),
                            $rs->tu_estado,
                            $assistance,
                            $canceled,
                            $motivo_canceled,
                            $overturn,
                            mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8')),
                            mb_strtoupper($branch->suc_nom),
                            mb_strtoupper($lender->tmsp_pnom),
                            $services,
                            mb_strtoupper($user->name),
                            strtolower($user->email)
                        );
                    }
                } else {

                    if ($asist == '1') {
                        $assistance = "ATENDIDO";
                    }
                    if ($asist == '0') {
                        $assistance = "AUSENCIA";
                    }
                    if ($asist == '2') {
                        $assistance = "ASISTENCIA PARCIAL";
                    }
                    if ($asist == '3') {
                        $assistance = "POR ANTENDER";
                    }
                    if ($asist == '4') {
                        $assistance = "EXPIRADO";
                    }
                    if ($rs->tu_carga == '0') {
                        $loaded = "PRESTADOR";
                    } else {
                        $loaded = "CLIENTE";
                    }
                    if ($rs->tu_st == '0') {
                        $overturn = "N/A";
                    } else {
                        $overturn = "SI";
                    }
                    if ($services == "") {
                        $services = "N/A";
                    } else {
                        $services = trim($services);
                    }

                    if ($asist != 4) {
                        if ($status == "ALTA") {

                            $row = array(
                                strval($rs->tu_code),
                                date("d/m/Y", strtotime($rs->tu_fec)),
                                date("H:i", strtotime($rs->tu_hora)),
                                $rs->tu_estado,
                                $assistance,
                                $loaded,
                                $overturn,
                                mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8')),
                                mb_strtoupper($branch->suc_nom),
                                mb_strtoupper($lender->tmsp_pnom),
                                $services,
                                mb_strtoupper($user->name),
                                strtolower($user->email)
                            );
                        } else {

                            $row = array(
                                strval($rs->tu_code),
                                date("d/m/Y", strtotime($rs->tu_fec)),
                                date("H:i", strtotime($rs->tu_hora)),
                                $rs->tu_estado,
                                $assistance,
                                $canceled,
                                $motivo_canceled,
                                $overturn,
                                mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8')),
                                mb_strtoupper($branch->suc_nom),
                                mb_strtoupper($lender->tmsp_pnom),
                                $services,
                                mb_strtoupper($user->name),
                                strtolower($user->email)
                            );
                        }
                    }
                }



                if (isset($detail) == 0) {
                    array_push($row, "");
                } else {
                    array_push($row, $detail->usm_cel);
                }

                if (isset($detail) == 0) {
                    array_push($row, "");
                } else {
                    array_push($row, $this->acentos($detail->usm_gen1));
                }

                if (isset($detail) == 0) {
                    array_push($row, "");
                } else {
                    array_push($row, $this->acentos($detail->usm_gen2));
                }

                if (isset($detail) == 0) {
                    array_push($row, "");
                } else {
                    array_push($row, $this->acentos($detail->usm_gen3));
                }

                if (isset($detail) == 0) {
                    array_push($row, "");
                } else {
                    array_push($row, $this->acentos($detail->usm_gen4));
                }



                $writer->addRow($row);
            }
        }
        $writer->close();
    }

    /**
     * generate excel shift
     * @return type
     */
    public function shift_user($user, $init, $end, $status, $type_shift, $lender, $branch, $business) {


        set_time_limit(0);
        ini_set('memory_limit', '128M');

        $this->audit('Descarga reporte de turnos');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }

        $fileName = 'turnos-' . $init . '-' . $end . '-' . $user . '.xlsx';
        $type_shift = $type_shift;
        $tu_st = '';
        $tu_carga_user = '';
        $tu_carga_admin = '';
        if ($type_shift == 'SOBRETURNO') {
            $tu_st = '1';
        }
        if ($type_shift == 'ADMIN') {
            $tu_carga_admin = '1';
        }
        if ($type_shift == 'USER') {
            $tu_carga_user = '1';
        }
        if ($type_shift == 'ALL' || $type_shift == 'SOBRETURNO' || $type_shift == 'ADMIN' || $type_shift == 'USER') {
            $type_shift = '';
        }
        if ($lender == 'ALL') {
            $lender = '';
        }
        if ($branch == 'ALL') {
            $branch = '';
        }
        if ($type_shift == '0') {
            $shift = DB::table('tu_turnos')
                    ->where('emp_id', $business)
                    ->where('us_id', $user)
                    ->where('tu_estado', '!=', 'BLOQUEO')
                    ->where('tu_fec', date("Y-m-d", strtotime($date)))
                    ->when(!empty($lender), function ($query) use($lender) {
                        return $query->where('pres_id', $lender);
                    })
                    ->when(!empty($branch), function ($query) use($branch) {
                        return $query->where('suc_id', $branch);
                    })
                    ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                        return $query->where('tu_carga', '!=', 0);
                    })
                    ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                        return $query->where('tu_carga', 0);
                    })
                    ->when(!empty($tu_st), function ($query) use($tu_st) {
                        return $query->where('tu_st', $tu_st);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->where('tu_fec', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->where('tu_fec', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderBy('tu_fec', 'ASC')
                    ->orderBy('suc_id', 'ASC')
                    ->orderBy('pres_id', 'ASC')
                    ->where('tu_asist', 0)
                    ->orderBy('tu_hora', 'ASC')
                    ->offset(0)->limit(2000)
                    ->get();
        } else {
            $shift = DB::table('tu_turnos')
                    ->where('emp_id', $business)
                    ->where('us_id', $user)
                    ->where('tu_estado', '!=', 'BLOQUEO')
                    ->when(!empty($lender), function ($query) use($lender) {
                        return $query->where('pres_id', $lender);
                    })
                    ->when(!empty($branch), function ($query) use($branch) {
                        return $query->where('suc_id', $branch);
                    })
                    ->when(!empty($tu_st), function ($query) use($tu_st) {
                        return $query->where('tu_st', $tu_st);
                    })
                    ->when(!empty($type_shift), function ($query) use($type_shift) {
                        return $query->where('tu_asist', $type_shift);
                    })
                    ->when(!empty($tu_carga_user), function ($query) use($tu_carga_user) {
                        return $query->where('tu_carga', '!=', 0);
                    })
                    ->when(!empty($tu_carga_admin), function ($query) use($tu_carga_admin) {
                        return $query->where('tu_carga', 0);
                    })
                    ->when(!empty($init), function ($query) use($init) {
                        return $query->where('tu_fec', '>=', date("Y-m-d", strtotime($init)));
                    })
                    ->when(!empty($end), function ($query) use($end) {
                        return $query->where('tu_fec', '<=', date("Y-m-d", strtotime($end)));
                    })
                    ->orderBy('tu_fec', 'ASC')
                    ->orderBy('suc_id', 'ASC')
                    ->orderBy('pres_id', 'ASC')
                    ->orderBy('tu_hora', 'ASC')
                    ->offset(0)->limit(2000)
                    ->get();
        }
        if (count($shift) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/agenda/empresa/' . $business);
        }
        $inputs_add = DB::table('tu_emps_md')->where('mi_empid', $business)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();

        $titles = array('TURNO', 'FECHA', 'HORA', 'ESTADO', 'ASISTENCIA', 'AGENDADO', 'SOBRETURNO', 'SUCURSAL', 'PRESTADOR', 'SERVICIO', 'CLIENTE', 'CORREO ELECTRÓNICO');
        foreach ($inputs_add as $item):
            if ($item->mi_field == 1 && $item->field_report == "1"):
                array_push($titles, "FECHA DE NACIMIENTO");
            endif;
            if ($item->mi_field == 2 && $item->field_report == "1"):
                array_push($titles, "TIPO Y NRO. DE DOCUMENTO");
            endif;
            if ($item->mi_field == 3 && $item->field_report == "1"):
                array_push($titles, "OBRA SOCIAL");
            endif;
            if ($item->mi_field == 4 && $item->field_report == "1"):
                array_push($titles, "PLAN OBRA SOCIAL");
            endif;
            if ($item->mi_field == 5 && $item->field_report == "1"):
                array_push($titles, "NÚMERO DE DOCUMENTO");
            endif;
            if ($item->mi_field == 6 && $item->field_report == "1"):
                array_push($titles, "NRO. DE AFILIADO OBRA SOCIAL");
            endif;
            if ($item->mi_field == 7 && $item->field_report == "1"):
                array_push($titles, "TELÉFONO");
            endif;
            if ($item->mi_field == 8 && $item->field_report == "1"):
                array_push($titles, "CELULAR");
            endif;
            if ($item->mi_field == 9 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 10 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 11 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 12 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 13 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 14 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 15 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
            if ($item->mi_field == 16 && $item->field_report == "1"):
                array_push($titles, mb_strtoupper($item->mi_gentxt));
            endif;
        endforeach;
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToBrowser($fileName);
        $writer->addRow($titles);


        foreach ($shift as $rs) {
            $services = '';
            if ($rs->tu_servid != null) {
                if (substr_count($rs->tu_servid, '-') <= 0) {
                    $service_id = $rs->tu_servid;
                    $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                            });
                    if (isset($get_service) != 0) {
                        $services .= $get_service->serv_nom;
                    }
                } else {
                    for ($i = 0; $i <= substr_count($rs->tu_servid, '-'); $i++) {
                        $service = explode('-', $rs->tu_servid);
                        $service_id = $service[$i];
                        $get_service = Cache::rememberForever('service_' . $service_id, function() use($service_id) {
                                    return DB::table('tu_emps_serv')->where('serv_id', $service_id)->first();
                                });
                        if (isset($get_service) != 0) {
                            $services .= trim($get_service->serv_nom);
                        }
                        if ($i != substr_count($rs->tu_servid, '-')) {
                            $services .= ", ";
                        }
                    }
                }
            }
            $lender_id = $rs->pres_id;
            $branch_id = $rs->suc_id;
            $user_id = $rs->us_id;
            $business_id = $rs->emp_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                        return DB::table('tu_emps_suc')->where('suc_id', $branch_id)->first();
                    });
            $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                        return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                    });
            $user = Cache::rememberForever('user_' . $user_id, function() use($user_id, $business_id) {
                        return DB::table('directory')->where('us_id', $user_id)->where('emp_id', $business_id)->first();
                    });
            if (isset($lender) != 0 && isset($user) != 0) {
                $detail = DB::table('tu_ususmd')->where('usm_turid', $rs->tu_id)->first();
                $time = (date("H", strtotime($rs->tu_hora)) <= 11) ? 'AM' : 'PM';
                $asist = $rs->tu_asist;
                if (date("Y-m-d", strtotime($rs->tu_fec)) < date("Y-m-d") && $rs->tu_asist == "3") {
                    $asist = 4;
                }
                if (date("Y-m-d", strtotime($rs->tu_fec)) == date("Y-m-d") && $rs->tu_asist == "3" && date("H:i:s", strtotime($rs->tu_hora)) < date("H:i:s")) {
                    $asist = 4;
                }
                if ($services == "") {
                    $services = "N/A";
                }
                if ($type_shift != '3') {


                    if ($asist == '1') {
                        $assistance = "ATENDIDO";
                    }
                    if ($asist == '0') {
                        $assistance = "AUSENCIA";
                    }
                    if ($asist == '2') {
                        $assistance = "ASISTENCIA PARCIAL";
                    }
                    if ($asist == '3') {
                        $assistance = "POR ANTENDER";
                    }
                    if ($asist == '4') {
                        $assistance = "EXPIRADO";
                    }
                    if ($rs->tu_carga == '0') {
                        $loaded = "PRESTADOR";
                    } else {
                        $loaded = "CLIENTE";
                    }
                    if ($rs->tu_st == '0') {
                        $overturn = "N/A";
                    } else {
                        $overturn = "SI";
                    }
                    if ($services == "") {
                        $services = "N/A";
                    } else {
                        $services = trim($services);
                    }


                    $row = array(
                        strval($rs->tu_code),
                        date("d/m/Y", strtotime($rs->tu_fec)),
                        date("H:i", strtotime($rs->tu_hora)),
                        $rs->tu_estado,
                        $assistance,
                        $loaded,
                        $overturn,
                        mb_strtoupper($branch->suc_nom),
                        mb_strtoupper($lender->tmsp_pnom),
                        $services,
                        mb_strtoupper($user->name),
                        strtolower($user->email)
                    );
                } else {

                    if ($asist == '1') {
                        $assistance = "ATENDIDO";
                    }
                    if ($asist == '0') {
                        $assistance = "AUSENCIA";
                    }
                    if ($asist == '2') {
                        $assistance = "ASISTENCIA PARCIAL";
                    }
                    if ($asist == '3') {
                        $assistance = "POR ANTENDER";
                    }
                    if ($asist == '4') {
                        $assistance = "EXPIRADO";
                    }
                    if ($rs->tu_carga == '0') {
                        $loaded = "PRESTADOR";
                    } else {
                        $loaded = "CLIENTE";
                    }
                    if ($rs->tu_st == '0') {
                        $overturn = "N/A";
                    } else {
                        $overturn = "SI";
                    }
                    if ($services == "") {
                        $services = "N/A";
                    } else {
                        $services = trim($services);
                    }

                    if ($asist != 4) {
                        $row = array(
                            strval($rs->tu_code),
                            date("d/m/Y", strtotime($rs->tu_fec)),
                            date("H:i", strtotime($rs->tu_hora)),
                            $rs->tu_estado,
                            $assistance,
                            $loaded,
                            $overturn,
                            mb_strtoupper($branch->suc_nom),
                            mb_strtoupper($lender->tmsp_pnom),
                            $services,
                            mb_strtoupper($user->name),
                            strtolower($user->email)
                        );
                    }
                }


                foreach ($inputs_add as $item):
                    if ($item->mi_field == 1 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {

                            if ($detail->usm_fecnac != "0000-00-00") {


                                array_push($row, date("d/m/Y", strtotime($detail->usm_fecnac)));
                            } else {

                                array_push($row, "");
                            }
                        }
                    endif;
                    if ($item->mi_field == 2 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_tipdoc);
                        }
                    endif;
                    if ($item->mi_field == 3 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_obsoc);
                        }
                    endif;
                    if ($item->mi_field == 4 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_obsocpla);
                        }
                    endif;
                    if ($item->mi_field == 5 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_numdoc);
                        }
                    endif;
                    if ($item->mi_field == 6 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_afilnum);
                        }
                    endif;
                    if ($item->mi_field == 7 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_tel);
                        }
                    endif;
                    if ($item->mi_field == 8 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $detail->usm_cel);
                        }
                    endif;
                    if ($item->mi_field == 9 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen1));
                        }
                    endif;
                    if ($item->mi_field == 10 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen2));
                        }
                    endif;
                    if ($item->mi_field == 11 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen3));
                        }
                    endif;
                    if ($item->mi_field == 12 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen4));
                        }
                    endif;
                    if ($item->mi_field == 13 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen5));
                        }
                    endif;

                    if ($item->mi_field == 14 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen6));
                        }
                    endif;

                    if ($item->mi_field == 15 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen7));
                        }
                    endif;

                    if ($item->mi_field == 16 && $item->field_report == "1"):
                        if (isset($detail) == 0) {
                            array_push($row, "");
                        } else {
                            array_push($row, $this->acentos($detail->usm_gen8));
                        }
                    endif;
                endforeach;


                $writer->addRow($row);
            }
        }
        $writer->close();
    }

    /**
     * generate excel customers 
     * @param type $business
     * @return type
     */
    public function customers($business) {
        $this->audit('Descarga reporte de clientes');
        $fileName = 'clientes_' . $business . '.xlsx';
        $users = DB::table('directory')->where('emp_id', $business)->where('status', '1')->orderby('name', 'asc')->get();
        if (count($users) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/directorio/empresa/' . $business);
        }
        $json = array();
        foreach ($users as $rs) {
            $business_id = $rs->emp_id;
            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            $detail = DB::table('tu_ususmd')->where('usm_usid', $rs->us_id)->where('usm_empid', $rs->emp_id)->first();
            if (isset($detail) != 0 && isset($get_business) != 0) {
                $json[] = array(
                    "name" => mb_strtoupper($rs->name),
                    "email" => strtolower($rs->email),
                    "business" => mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8')),
                    "detail" => $detail,
                );
            }
        }
        $inputs_add = DB::table('tu_emps_md')->where('mi_empid', $business)->orderby('mi_tipo', 'asc')->orderby('mi_ord', 'asc')->get();
        return Excel::download(new TuExcel('customers', $json, $inputs_add), $fileName);
    }

    /**
     * generate excel payments
     * @param type $business
     * @return type
     */
    public function payments($init, $end, $business) {
        $this->audit('Descarga reporte de pagos');
        if ($init != '') {
            $init = explode('-', $init);
            $init = $init[2] . "-" . $init[1] . "-" . $init[0];
        }
        if ($end != '') {
            $end = explode('-', $end);
            $end = $end[2] . "-" . $end[1] . "-" . $end[0];
        }
        $fileName = 'pagos-' . $init . '-' . $end . '-' . $business . '.xlsx';
        $lists = MercadoPago::where('emp_id', $business)
                ->when(!empty($init), function ($query) use($init) {
                    return $query->whereDate('created_at', '>=', date("Y-m-d", strtotime($init)));
                })
                ->when(!empty($end), function ($query) use($end) {
                    return $query->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
                })
                ->get();
        if (count($lists) == 0) {
            Session()->flash('no-results', 'No hay resultados');
            return redirect::to('/empresa/mercado-pago/' . $business);
        }
        $json = array();
        foreach ($lists as $rs) {
            $shift = DB::table('tu_turnos')->where('tu_id', $rs->id_turno)->first();
            $user = DB::table('directory')->where('us_id', $shift->us_id)->where('emp_id', $shift->emp_id)->first();

            $lender_id = $shift->pres_id;
            $branch_id = $shift->suc_id;
            $business_id = $shift->emp_id;

            $get_business = Cache::rememberForever('business_' . $business_id, function() use($business_id) {
                        return DB::table('tu_emps')->where('em_id', $business_id)->first();
                    });
            $branch = Cache::rememberForever('branch_' . $branch_id, function() use($branch_id) {
                        return DB::table('tu_emps_suc')->where('suc_id', $branch_id)->first();
                    });
            $lender = Cache::rememberForever('lender_' . $lender_id, function() use($lender_id) {
                        return DB::table('tu_tmsp')->where('tmsp_id', $lender_id)->first();
                    });

            if (isset($lender) != 0 && isset($user) != 0 && isset($shift) != 0) {

                $json[] = array(
                    "id" => $rs->tu_id,
                    "id_payment" => $rs->id_payment,
                    "code" => $shift->tu_code,
                    "amount" => number_format($rs->amount, 2, ".", ","),
                    "name" => mb_strtoupper($user->name),
                    "email" => strtolower($user->email),
                    'lender' => mb_strtoupper($lender->tmsp_pnom),
                    "branch" => mb_strtoupper($branch->suc_nom),
                    "business" => mb_strtoupper(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8')),
                    "created_at" => date("d/m/Y H:i:s", strtotime($rs->created_at))
                );
            }
        }
        return Excel::download(new TuExcelSingle('payments', $json), $fileName);
    }

    /**
     * Audit user
     * @return type
     */
    public function audit($activity) {
        try {
            Activities::create([
                'activity' => $activity,
                'ip' => $this->getIp(),
                'id_user' => $this->getIdBusiness()
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

    public function getIdBusiness() {

        if (Auth::guard('user')->User()->level == 1) {
            return Auth::guard('user')->User()->us_id;
        } else {

            $get_business = DB::table('tu_emps')->where('em_id', Auth::guard('user')->User()->emp_id)->first();
            return $get_business->em_uscid;
        }
    }

    public function updateUrl($value) {

        $value = strtolower($value);
        $value = mb_strtolower($value, 'UTF-8');
        $value = trim($value);
        $value = preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[\s-]/s', '', $value);
//Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $value = str_replace($find, $repl, $value);
// Añadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+');
        $value = str_replace($find, '-', $value);
// Eliminamos y Reemplazamos otros carácteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $value = preg_replace($find, $repl, $value);
//Asignamos Valor al atributo  URL
        return $value;
    }

    public function acentos($cadena) {
        $search = explode(",", "á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±,Ã“,Ã ,Ã‰,Ã ,Ãš,â€œ,â€ ,Â¿,ü");
        $replace = explode(",", "á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ó,Á,É,Í,Ú,\",\",¿,&uuml;");
        $cadena = str_replace($search, $replace, $cadena);

        return $cadena;
    }

}

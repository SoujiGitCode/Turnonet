@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Datos requeridos</h4>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
         <br>
      </div>
      <div class="col-md-8 col-sm-8 col-xs-12">
          <div class="panel hidden-lg hidden-md hidden-sm">
            <div class="panel-heading">
               Configurá los datos requeridos aquí:
            </div>
            <div class="panel-body">
               <br>
               <div class="alert alert-warning" role="alert">
                  Iniciá sesión desde un computador para administrar esta sección.
               </div>
               <br>
            </div>
         </div>
         <div class="panel hidden-xs">
            <div class="panel-heading">
               Configurá los datos requeridos aquí:
            </div>
            <div class="panel-body">
               <form id="form">
                  <input type="hidden" name="id" value="<?php echo $get_business->em_id;?>">
                  <p>Aquí podrá establecer que información le es requerida al cliente para tomar turnos y determinar si son obligatorios o no.</p>
                  <div class="form-group bdashed pptop" style="width: 100%; clear: both;">
                     <div class="row mm-bt">
                        <div class="col-md-5 col-sm-5">
                           <label>Campo</label>
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <label>Tipo</label>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <label>Ver en Reporte</label>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <label>Obligatorio</label>
                        </div>
                     </div>
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field','<=',8)
                        ->orderby('mi_field','asc')
                        ->offset(0)
                        ->limit(1)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select1" id="input-1" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1"  @if($rs->mi_field==1) selected="selected" @endif >Fecha de Nacimiento</option>
                              <option value="5"  @if($rs->mi_field==5) selected="selected" @endif  >Tipo y Nro. de Documento</option>
                              <option value="3"  @if($rs->mi_field==3) selected="selected" @endif  >Obra Social</option>
                              <option value="4"  @if($rs->mi_field==4) selected="selected" @endif  >Plan Obra Social</option>
                              <option value="6"  @if($rs->mi_field==6) selected="selected" @endif  >Nro. de Afiliado Obra Social</option>
                              <option value="7"  @if($rs->mi_field==7) selected="selected" @endif  >Teléfono</option>
                              <option value="8"  @if($rs->mi_field==8) selected="selected" @endif  >Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_0" id="opt-rep1" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep1"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_0" id="opt-1" value="1" @if($rs->mi_ob==1)  checked="checked" @endif>
                              <label for="opt-1"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select1" id="input-1" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1">Fecha de Nacimiento</option>
                              <option value="5">Tipo y Nro. de Documento</option>
                              <option value="3">Obra Social</option>
                              <option value="4">Plan Obra Social</option>
                              <option value="6">Nro. de Afiliado Obra Social</option>
                              <option value="7">Teléfono</option>
                              <option value="8">Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_0" id="opt-rep1" value="1">
                              <label for="opt-rep1"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_0" id="opt-1" value="1" >
                              <label for="opt-1"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field','<=',8)
                         ->orderby('mi_field','asc')
                        ->offset(1)
                        ->limit(1)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select2" id="input-2" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1"  @if($rs->mi_field==1) selected="selected" @endif >Fecha de Nacimiento</option>
                              <option value="5"  @if($rs->mi_field==5) selected="selected" @endif  >Tipo y Nro. de Documento</option>
                              <option value="3"  @if($rs->mi_field==3) selected="selected" @endif  >Obra Social</option>
                              <option value="4"  @if($rs->mi_field==4) selected="selected" @endif  >Plan Obra Social</option>
                              <option value="6"  @if($rs->mi_field==6) selected="selected" @endif  >Nro. de Afiliado Obra Social</option>
                              <option value="7"  @if($rs->mi_field==7) selected="selected" @endif  >Teléfono</option>
                              <option value="8"  @if($rs->mi_field==8) selected="selected" @endif  >Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">

                           <div class="demoo1">
                              <input type="checkbox" name="report_1" id="opt-rep2" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep2"><span></span></label>
                           </div>

                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_1" id="opt-2" value="1" @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-2"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select2" id="input-2" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1">Fecha de Nacimiento</option>
                              <option value="5">Tipo y Nro. de Documento</option>
                              <option value="3">Obra Social</option>
                              <option value="4">Plan Obra Social</option>
                              <option value="6">Nro. de Afiliado Obra Social</option>
                              <option value="7">Teléfono</option>
                              <option value="8">Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">


                           <div class="demoo1">
                              <input type="checkbox" name="report_1" id="opt-rep2" value="1">
                              <label for="opt-rep2"><span></span></label>
                           </div>

                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_1" id="opt-2" value="1"  >
                              <label for="opt-2"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field','<=',8)
                         ->orderby('mi_field','asc')
                        ->offset(2)
                        ->limit(1)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select3" id="input-3" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1"  @if($rs->mi_field==1) selected="selected" @endif >Fecha de Nacimiento</option>
                              <option value="5"  @if($rs->mi_field==5) selected="selected" @endif  >Tipo y Nro. de Documento</option>
                              <option value="3"  @if($rs->mi_field==3) selected="selected" @endif  >Obra Social</option>
                              <option value="4"  @if($rs->mi_field==4) selected="selected" @endif  >Plan Obra Social</option>
                              <option value="6"  @if($rs->mi_field==6) selected="selected" @endif  >Nro. de Afiliado Obra Social</option>
                              <option value="7"  @if($rs->mi_field==7) selected="selected" @endif  >Teléfono</option>
                              <option value="8"  @if($rs->mi_field==8) selected="selected" @endif  >Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">

                           <div class="demoo1">
                              <input type="checkbox" name="report_2" id="opt-rep3" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep3"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_2" id="opt-3" value="1" @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-3"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else 
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select3" id="input-3" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1">Fecha de Nacimiento</option>
                              <option value="5">Tipo y Nro. de Documento</option>
                              <option value="3">Obra Social</option>
                              <option value="4">Plan Obra Social</option>
                              <option value="6">Nro. de Afiliado Obra Social</option>
                              <option value="7">Teléfono</option>
                              <option value="8">Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_2" id="opt-rep3" value="1">
                              <label for="opt-rep3"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_2" id="opt-3" value="1" >
                              <label for="opt-3"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field','<=',8)
                         ->orderby('mi_field','asc')
                        ->offset(3)
                        ->limit(1)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select4" id="input-4" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1"  @if($rs->mi_field==1) selected="selected" @endif >Fecha de Nacimiento</option>
                              <option value="5"  @if($rs->mi_field==5) selected="selected" @endif  >Tipo y Nro. de Documento</option>
                              <option value="3"  @if($rs->mi_field==3) selected="selected" @endif  >Obra Social</option>
                              <option value="4"  @if($rs->mi_field==4) selected="selected" @endif  >Plan Obra Social</option>
                              <option value="6"  @if($rs->mi_field==6) selected="selected" @endif  >Nro. de Afiliado Obra Social</option>
                              <option value="7"  @if($rs->mi_field==7) selected="selected" @endif  >Teléfono</option>
                              <option value="8"  @if($rs->mi_field==8) selected="selected" @endif  >Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_3" id="opt-rep4" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep4"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_3" id="opt-4" value="1" @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-4"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select4" id="input-4" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1">Fecha de Nacimiento</option>
                              <option value="5">Tipo y Nro. de Documento</option>
                              <option value="3">Obra Social</option>
                              <option value="4">Plan Obra Social</option>
                              <option value="6">Nro. de Afiliado Obra Social</option>
                              <option value="7">Teléfono</option>
                              <option value="8">Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                            <div class="demoo1">
                              <input type="checkbox" name="report_3" id="opt-rep4" value="1">
                              <label for="opt-rep4"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_3" id="opt-4" value="1" >
                              <label for="opt-4"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field','<=',8)
                         ->orderby('mi_field','asc')
                        ->offset(4)
                        ->limit(1)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select5" id="input-5" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1"  @if($rs->mi_field==1) selected="selected" @endif >Fecha de Nacimiento</option>
                              <option value="5"  @if($rs->mi_field==5) selected="selected" @endif  >Tipo y Nro. de Documento</option>
                              <option value="3"  @if($rs->mi_field==3) selected="selected" @endif  >Obra Social</option>
                              <option value="4"  @if($rs->mi_field==4) selected="selected" @endif  >Plan Obra Social</option>
                              <option value="6"  @if($rs->mi_field==6) selected="selected" @endif  >Nro. de Afiliado Obra Social</option>
                              <option value="7"  @if($rs->mi_field==7) selected="selected" @endif  >Teléfono</option>
                              <option value="8"  @if($rs->mi_field==8) selected="selected" @endif  >Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_4" id="opt-rep5" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep5"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_4" id="opt-5" value="1" @if($rs->mi_ob==1)  checked="checked" @endif>
                              <label for="opt-5"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select5" id="input-5" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1">Fecha de Nacimiento</option>
                              <option value="5">Tipo y Nro. de Documento</option>
                              <option value="3">Obra Social</option>
                              <option value="4">Plan Obra Social</option>
                              <option value="6">Nro. de Afiliado Obra Social</option>
                              <option value="7">Teléfono</option>
                              <option value="8">Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_4" id="opt-rep5" value="1">
                              <label for="opt-rep5"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_4" id="opt-5" value="1" >
                              <label for="opt-5"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field','<=',8)
                         ->orderby('mi_field','asc')
                        ->offset(5)
                        ->limit(1)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select6" id="input-6" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1"  @if($rs->mi_field==1) selected="selected" @endif >Fecha de Nacimiento</option>
                              <option value="5"  @if($rs->mi_field==5) selected="selected" @endif  >Tipo y Nro. de Documento</option>
                              <option value="3"  @if($rs->mi_field==3) selected="selected" @endif  >Obra Social</option>
                              <option value="4"  @if($rs->mi_field==4) selected="selected" @endif  >Plan Obra Social</option>
                              <option value="6"  @if($rs->mi_field==6) selected="selected" @endif  >Nro. de Afiliado Obra Social</option>
                              <option value="7"  @if($rs->mi_field==7) selected="selected" @endif  >Teléfono</option>
                              <option value="8"  @if($rs->mi_field==8) selected="selected" @endif  >Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_6" id="opt-rep6" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep6"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_5" id="opt-6" value="1" @if($rs->mi_ob==1) checked="checked" @endif >
                              <label for="opt-6"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select6" id="input-6" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1">Fecha de Nacimiento</option>
                              <option value="5">Tipo y Nro. de Documento</option>
                              <option value="3">Obra Social</option>
                              <option value="4">Plan Obra Social</option>
                              <option value="6">Nro. de Afiliado Obra Social</option>
                              <option value="7">Teléfono</option>
                              <option value="8">Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_5" id="opt-rep6" value="1">
                              <label for="opt-rep6"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_5" id="opt-6" value="1" >
                              <label for="opt-6"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field','<=',8)
                         ->orderby('mi_field','asc')
                        ->offset(6)
                        ->limit(1)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select7" id="input-7" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1"  @if($rs->mi_field==1) selected="selected" @endif >Fecha de Nacimiento</option>
                              <option value="5"  @if($rs->mi_field==5) selected="selected" @endif  >Tipo y Nro. de Documento</option>
                              <option value="3"  @if($rs->mi_field==3) selected="selected" @endif  >Obra Social</option>
                              <option value="4"  @if($rs->mi_field==4) selected="selected" @endif  >Plan Obra Social</option>
                              <option value="6"  @if($rs->mi_field==6) selected="selected" @endif  >Nro. de Afiliado Obra Social</option>
                              <option value="7"  @if($rs->mi_field==7) selected="selected" @endif  >Teléfono</option>
                              <option value="8"  @if($rs->mi_field==8) selected="selected" @endif  >Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_6" id="opt-rep7" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep7"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_6" id="opt-7" value="1" @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-7"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <select name="input[]" class="form-control form-date-1 select7" id="input-7" onchange="select_input()">
                              <option value="">--Seleccioná la opción deseada--</option>
                              <option value="1">Fecha de Nacimiento</option>
                              <option value="5">Tipo y Nro. de Documento</option>
                              <option value="3">Obra Social</option>
                              <option value="4">Plan Obra Social</option>
                              <option value="6">Nro. de Afiliado Obra Social</option>
                              <option value="7">Teléfono</option>
                              <option value="8">Celular</option>
                           </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_6" id="opt-rep7" value="1">
                              <label for="opt-rep7"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_6" id="opt-7" value="1" >
                              <label for="opt-7"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field',9)
                        ->get();  
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-8" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="<?php echo $rs->mi_gentxt;?>">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_7" id="optipo_7" class="form-control form-date-1">
                           <option value="1" @if($rs->mi_tipofield==1) selected="selected" @endif >Campo de Texto</option>
                           <option value="2"  @if($rs->mi_tipofield==2) selected="selected" @endif >Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">

                           <div class="demoo1">
                              <input type="checkbox" name="report_7" id="opt-rep8" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep8"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_7" id="opt-8" value="1"  @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-8"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-8" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_7" id="optipo_7" class="form-control form-date-1">
                              <option value="1" selected="">Campo de Texto</option>
                              <option value="2">Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">

                           <div class="demoo1">
                              <input type="checkbox" name="report_7" id="opt-rep8" value="1">
                              <label for="opt-rep8"><span></span></label>
                           </div>

                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_7" id="opt-8" value="1" >
                              <label for="opt-8"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field',10)
                        ->get();  
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-9" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="<?php echo $rs->mi_gentxt;?>">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_8" id="optipo_8" class="form-control form-date-1">
                           <option value="1" @if($rs->mi_tipofield==1) selected="selected" @endif >Campo de Texto</option>
                           <option value="2"  @if($rs->mi_tipofield==2) selected="selected" @endif >Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">

                           <div class="demoo1">
                              <input type="checkbox" name="report_8" id="opt-rep9" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep9"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_8" id="opt-9" value="1"  @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-9"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-9" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_8" id="optipo_8" class="form-control form-date-1">
                              <option value="1" selected="">Campo de Texto</option>
                              <option value="2">Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_8" id="opt-rep9" value="1">
                              <label for="opt-rep9"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_8" id="opt-9" value="1" >
                              <label for="opt-9"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)
 
 
                         ->where('mi_field',11)
                         ->get();  
                         ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-10" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="<?php echo $rs->mi_gentxt;?>">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_9" id="optipo_9" class="form-control form-date-1">
                           <option value="1" @if($rs->mi_tipofield==1) selected="selected" @endif >Campo de Texto</option>
                           <option value="2"  @if($rs->mi_tipofield==2) selected="selected" @endif >Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_9" id="opt-rep10" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep10"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_9" id="opt-10" value="1"  @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-10"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-10" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_9" id="optipo_9" class="form-control form-date-1">
                              <option value="1" selected="">Campo de Texto</option>
                              <option value="2">Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_9" id="opt-rep10" value="1" >
                              <label for="opt-rep10"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_9" id="opt-10" value="1" >
                              <label for="opt-10"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field',12)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-11" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="<?php echo $rs->mi_gentxt;?>">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_10" id="optipo_10" class="form-control form-date-1">
                           <option value="1" @if($rs->mi_tipofield==1) selected="selected" @endif >Campo de Texto</option>
                           <option value="2"  @if($rs->mi_tipofield==2) selected="selected" @endif >Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_10" id="opt-rep11" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep11"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_10" id="opt-11" value="1"  @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-11"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-11" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_10" id="optipo_10" class="form-control form-date-1">
                              <option value="1" selected="">Campo de Texto</option>
                              <option value="2">Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_10" id="opt-rep11" value="1">
                              <label for="opt-rep11"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_10" id="opt-11" value="1" >
                              <label for="opt-11"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif

                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field',13)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-12" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="<?php echo $rs->mi_gentxt;?>">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_11" id="optipo_11" class="form-control form-date-1">
                           <option value="1" @if($rs->mi_tipofield==1) selected="selected" @endif >Campo de Texto</option>
                           <option value="2"  @if($rs->mi_tipofield==2) selected="selected" @endif >Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_11" id="opt-rep12" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep12"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_11" id="opt-12" value="1"  @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-12"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-12" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_11" id="optipo_11" class="form-control form-date-1">
                              <option value="1" selected="">Campo de Texto</option>
                              <option value="2">Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_11" id="opt-rep12" value="1">
                              <label for="opt-rep12"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_11" id="opt-12" value="1" >
                              <label for="opt-12"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif

                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field',14)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-13" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="<?php echo $rs->mi_gentxt;?>">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_12" id="optipo_12" class="form-control form-date-1">
                           <option value="1" @if($rs->mi_tipofield==1) selected="selected" @endif >Campo de Texto</option>
                           <option value="2"  @if($rs->mi_tipofield==2) selected="selected" @endif >Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_12" id="opt-rep13" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep13"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_12" id="opt-13" value="1"  @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-13"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-13" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_12" id="optipo_12" class="form-control form-date-1">
                              <option value="1" selected="">Campo de Texto</option>
                              <option value="2">Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_12" id="opt-rep13" value="1">
                              <label for="opt-rep13"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_12" id="opt-13" value="1" >
                              <label for="opt-13"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif

                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field',15)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-14" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="<?php echo $rs->mi_gentxt;?>">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_13" id="optipo_13" class="form-control form-date-1">
                           <option value="1" @if($rs->mi_tipofield==1) selected="selected" @endif >Campo de Texto</option>
                           <option value="2"  @if($rs->mi_tipofield==2) selected="selected" @endif >Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_13" id="opt-rep14" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep14"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_13" id="opt-14" value="1"  @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-14"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-14" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_13" id="optipo_13" class="form-control form-date-1">
                              <option value="1" selected="">Campo de Texto</option>
                              <option value="2">Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_13" id="opt-rep14" value="1">
                              <label for="opt-rep14"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_13" id="opt-14" value="1" >
                              <label for="opt-14"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif

                     <?php $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field',16)
                        ->get(); 
                        ?>
                     @if(count($input_add)!=0)
                     @foreach($input_add as $rs)
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-15" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="<?php echo $rs->mi_gentxt;?>">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_14" id="optipo_14" class="form-control form-date-1">
                           <option value="1" @if($rs->mi_tipofield==1) selected="selected" @endif >Campo de Texto</option>
                           <option value="2"  @if($rs->mi_tipofield==2) selected="selected" @endif >Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_14" id="opt-rep15" value="1" @if($rs->field_report==1)  checked="checked" @endif>
                              <label for="opt-rep15"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_14" id="opt-15" value="1"  @if($rs->mi_ob==1)  checked="checked" @endif >
                              <label for="opt-15"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="row">
                        <div class="col-md-5 col-sm-5">
                           <input type="text" id="input-15" name="input[]" class="form-control form-date-1" placeholder="Genérico" value="">
                        </div>
                        <div class="col-md-3 col-sm-3">
                           <select name="optipo_14" id="optipo_14" class="form-control form-date-1">
                              <option value="1" selected="">Campo de Texto</option>
                              <option value="2">Selección (Si/No)</option>
                           </select>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="report_14" id="opt-rep15" value="1">
                              <label for="opt-rep15"><span></span></label>
                           </div>
                        </div>
                        <div class="col-md-2 col-sm-2 text-center">
                           <div class="demoo1">
                              <input type="checkbox" name="op_14" id="opt-15" value="1" >
                              <label for="opt-15"><span></span></label>
                           </div>
                        </div>
                     </div>
                     @endif
                  </div>
                  <?php 
                     $input_add=DB::table('tu_emps_md')
                        ->where('mi_empid', $get_business->em_id)


                        ->where('mi_field',3)
                        ->get();  
                        ?>
                  @if(count($input_add)==0)  
                  <div class="row"  style="display: none;" id="sect-social">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label>Obra Social</label>
                        </div>
                        <div class="form-group">
                           <div class="demo-radio">
                              <input type="radio" name="social" id="pc_social" value="1"  checked="checked" >
                              <label for="pc_social"><span></span>Campo de texto</label>
                           </div>
                           <div class="demo-radio">
                              <input type="radio" name="social" id="pc_social-1" value="2">
                              <label for="pc_social-1"><span></span>Desplegable</label>
                           </div>
                        </div>
                        <div class="form-group">
                           <br>
                        </div>
                     </div>
                  </div>
                  @else
                  @foreach($input_add as $rs)
                  <div class="row"  id="sect-social">
                      <?php $social_emp=DB::table('tu_emps_ob')
                  ->where('eob_empid',$get_business->em_id)
                  ->count(); ?>

                  @if($social_emp!=0)
                     <div class="col-md-12">
                        <div class="form-group">
                           <label>Obra Social</label>
                        </div>
                        <div class="form-group">
                           <div class="demo-radio">
                              <input type="radio" name="social" id="pc_social" value="1"  @if($rs->mi_tipofield !=2) checked="checked" @endif >
                              <label for="pc_social"><span></span>Campo de texto</label>
                           </div>
                        
                           <div class="demo-radio">
                              <input type="radio" name="social" id="pc_social-1" value="2" @if($rs->mi_tipofield==2) checked="checked" @endif>
                              <label for="pc_social-1"><span></span>Desplegable</label>
                           </div>
                        
                        </div>
                        <div class="form-group">
                           <br>
                        </div>
                     </div>
                     @endif
                  </div>
                  @endforeach
                  @endif
                  <div class="form-group">
                     <button type="button" onclick="update_required()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
         @include('includes.business')
      </div>
   </div>
</div>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
<script type="text/javascript">
   select_input();
</script>
@stop
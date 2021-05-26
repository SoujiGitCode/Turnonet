<!DOCTYPE html>
<html>
   <head>
      <title><?php echo $user->name;?></title>
   </head>
   <style type="text/css">
      .roboto {
      font-family: Arial, Helvetica, sans-serif;
      }
      .main {
      font-family: Arial, Helvetica, sans-serif;
      width: 100%;
      }
      .main-1 {
      font-family: Arial, Helvetica, sans-serif;
      width: 100%;
      margin-top: 20px;
      border: 1px solid #ddd;
      border-bottom: none;
      border-radius: 4px;
      }
      .heading>td {
      background: #f3f3f3;
      padding: 10px 10px;
      border-bottom: 1px solid #ddd;
      font-family: Arial, Helvetica, sans-serif;
      font-weight: bold;
      text-transform: uppercase;
      }
      .t-body {
      border-bottom: 1px solid #ddd;
      }
      .t-body>td {
      color: #808080!important;
      padding: 10px 10px;
      font-size: 14px;
      border-bottom: 1px solid #ddd;
      }
      .t-body:last-child {
      border-bottom: none;
      }
      .nm {
      margin: 0
      }
      .tr {
      text-align: right;
      }
      .uk-text-center {
      text-align: center;
      }
      .uk-text-upper {
      text-transform: uppercase;
      }
      .uk-text-italic {
      font-style: italic;
      }
      .uk-text-small {
      font-size: 14px!important;
      }
      .uk-text-muted {
      color: #808080!important;
      }
      .uk-text-strong {
      font-weight: 700;
      }
      .uk-text-danger {
      color: #e53935!important;
      }
      .uk-text-success {
      color: #808080!important;
      }
      .tl {
      text-align: left;
      }
      .bold {
      font-weight: bold
      }
      .t14 {
      font-size: 14px
      }
      .t13 {
      font-size: 13px
      }
      .t16 {
      font-size: 16px
      }
      .t18 {
      font-size: 18px
      }
      .t22 {
      font-size: 22px
      }
      .f300 {
      font-weight: normal;
      }
      .fact {
      width: 100%;
      margin-top: 30px;
      font-family: Arial, Helvetica, sans-serif;
      ;
      }
      .fact tr th {
      font-style: normal;
      font-weight: 400;
      color: #fff;
      font-size: 16px;
      background: #021063;
      text-align: center;
      }
      .fact tr td {
      font-size: 14px;
      text-align: center;
      border-bottom: 1px solid #aaa;
      padding: 5px 0;
      background: #ecebeb
      }
      .ffa {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 18px;
      font-weight: bold
      }
      .nbor {
      border-width: 0px!important;
      }
      .bbottom {
      border-bottom: 1px solid #fff;
      border-spacing: 0px;
      }
      .art {
      text-align: right
      }
      table {
      border-spacing: 0px;
      border-collapse: separate;
      }
      .pie {
      font-family: Arial, Helvetica, sans-serif;
      position: absolute;
      height: 40px;
      width: 100%;
      text-align: center;
      bottom: 10px;
      font-size: 11px;
      color: #808080!important;
      }
      .pie img {
      width: 120px;
      margin-bottom: 5px;
      text-align: center;
      }
   </style>
   <body>
      <?php 
         function format_phone($phone) {
           
             if (!isset($phone{3})) {
                 return $phone;
             }
             $phone = preg_replace("/[^0-9]/", "", $phone);
             $length = strlen($phone);
             switch ($length) {
                 case 7:
                 return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                 break;
                 case 8:
                 return preg_replace("/([0-9]{4})([0-9]{4})/", "$1-$2", $phone);
                 break;
                 case 9:
                 return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1 $2-$3", $phone);
                 break;
                 case 10:
                 return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1 $2-$3", $phone);
                 break;
                 case 11:
                 return preg_replace("/([0-9]{1})([0-9]{2})([0-9]{4})([0-9]{4})/", "$1 $2 $3-$4", $phone);
                 break;
                 default:
                 return $phone;
                 break;
             } 
         }         
         ?>
      <table class="main">
         <tr >
            <td class="bbottom">
               <?php
                  $image=$_SERVER['DOCUMENT_ROOT'].'/img/empty.jpg';
                  if(isset($business)!=0 && $business->em_pfot!=""){
                    $image=env('PATH_BUSINESS').$business->em_pfot;
                  }
                  ?>
               <img src="<?php echo $image;?>" style="width: 100px">
            </td>
            <td width="49%" class="bbottom art">
              <h1 class="t18 uk-text-upper " style="color: #000000;margin-bottom: 0px; margin-top:0px; padding-top:0px;padding-bottom: 0px; font-weight: bold;"><?php echo mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8');?></h1>
               <p class="nm tl t13 art f300" style=" color: #808080!important;">Teléfono: {{format_phone($business->em_tel)}}</p>
               <p class="nm tl t13 art f300" style=" color: #808080!important;">Correo electrónico: {{$business->em_email}}</p>
            </td>
         </tr>
      </table>
      <table class="main-1">
         <tr class="heading">
            <td class="bbottom" colspan="2">DATOS DE CLIENTE:</td>
         </tr>
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Nombre y Apellido:</td>
            <td style="width: 60%"><?php echo $user->name;?></td>
         </tr>
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Correo electrónico:</td>
            <td style="width: 60%"><?php echo $user->email;?></td>
         </tr>
           @if(isset($data)!=0)
         @foreach($inputs_add as $row)
         @if($row->field_report=="1" && $row->mi_field==1)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Fecha de nacimiento:</td>
            <td style="width: 60%"><?php if($data->usm_fecnac!="0000-00-00"){ echo date("d/m/Y",strtotime($data->usm_fecnac)); }?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==2)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Tipo de documento:</td>
            <td style="width: 60%"><?php echo $data->usm_tipdoc;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==3)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Obra social:</td>
            <td style="width: 60%"><?php echo $data->usm_obsoc;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==4)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Plan Obra Social:</td>
            <td style="width: 60%"><?php echo $data->obsocpla;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==5)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Tipo y Nro. de Documento:</td>
            <td style="width: 60%"><?php echo $data->usm_numdoc;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==6)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Nro. de Afiliado Obra Social:</td>
            <td style="width: 60%"><?php echo $data->usm_afilnum;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==7)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Teléfono:</td>
            <td style="width: 60%"><?php echo format_phone($data->usm_tel);?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==8)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%">Celular:</td>
            <td style="width: 60%"><?php echo $data->usm_cel;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==9)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%"><?php echo ucfirst(mb_strtolower($row->mi_gentxt));?>:</td>
            <td style="width: 60%"><?php echo $data->usm_gen1;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==10)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%"><?php echo ucfirst(mb_strtolower($row->mi_gentxt));?>:</td>
            <td style="width: 60%"><?php echo $data->usm_gen2;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==11)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%"><?php echo ucfirst(mb_strtolower($row->mi_gentxt));?>:</td>
            <td style="width: 60%"><?php echo $data->usm_gen3;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==12)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%"><?php echo ucfirst(mb_strtolower($row->mi_gentxt));?>:</td>
            <td style="width: 60%"><?php echo $data->usm_gen4;?></td>
         </tr>
         @endif
         @if($row->field_report=="1" && $row->mi_field==13)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%"><?php echo ucfirst(mb_strtolower($row->mi_gentxt));?>:</td>
            <td style="width: 60%"><?php echo $data->usm_gen5;?></td>
         </tr>
         @endif

         @if($row->field_report=="1" && $row->mi_field==14)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%"><?php echo ucfirst(mb_strtolower($row->mi_gentxt));?>:</td>
            <td style="width: 60%"><?php echo $data->usm_gen6;?></td>
         </tr>
         @endif

         @if($row->field_report=="1" && $row->mi_field==15)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%"><?php echo ucfirst(mb_strtolower($row->mi_gentxt));?>:</td>
            <td style="width: 60%"><?php echo $data->usm_gen7;?></td>
         </tr>
         @endif

         @if($row->field_report=="1" && $row->mi_field==16)
         <tr class="t-body">
            <td class="bbottom" style="width: 30%"><?php echo ucfirst(mb_strtolower($row->mi_gentxt));?>:</td>
            <td style="width: 60%"><?php echo $data->usm_gen8;?></td>
         </tr>
         @endif

         @endforeach
         @endif
      </table>
      <div class="pie">
         <p style="margin-bottom: 0.1px"><img src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/uploads/icons/logo_gris.png';?>"></p>
         <p style="margin-top: 0px"> v4.0 Turnonet 2011-<?php echo date("Y");?> </p>
      </div>
   </body>
</html>
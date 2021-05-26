<!DOCTYPE html>
<html>
   <head>
      <title><?php echo mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8');?></title>
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

                $site = DB::table('site')->where('id', '1')->first();

                  $image=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$site->image_1;
                  
                  ?>
               <img src="<?php echo $image;?>" style="width: 250px">
            </td>
            <td width="49%" class="bbottom art">
            
               <p class="nm tl t13 art f300" style=" color: #808080!important;">Teléfono: +54 (11) 3530 9345</p>
               <p class="nm tl t13 art f300" style=" color: #808080!important;">Correo electrónico: info@turnonet.com</p>
            </td>
         </tr>
      </table>

       <h1 class="t18 uk-text-upper main" style="color: #000000;margin-bottom: 0px; margin-top:0px; padding-top:0px;padding-bottom: 0px; font-weight: bold; text-align: center;"><br><?php echo mb_convert_encoding($business->em_nomfan, 'UTF-8', 'UTF-8');?></h1>

      @if($business->em_smscontrol=="ALTA")
      <table class="main-1">
         <tr class="heading">
            <td class="bbottom" colspan="2">MENSAJES DE TEXTO ENVIADOS <?php echo date('d/m/Y',strtotime($init));?> - <?php echo date('d/m/Y',strtotime($end));?></td>
         </tr>
SMS
         <tr class="t-body">
            <td class="bbottom" style="width: 70%">Total SMS</td>
            <td style="width: 30%; text-align:center"><?php echo $sms;?></td>
         </tr>
@endif
      </table>
      <table class="main-1">
         <tr class="heading">
            <td class="bbottom" colspan="2">TURNOS AGENDADOS A PRESTADORES <?php echo date('d/m/Y',strtotime($init));?> - <?php echo date('d/m/Y',strtotime($end));?></td>
         </tr>
 @foreach($lenders as $rs)
                              <?php  $lender=DB::table('tu_tmsp')->where('tmsp_id',$rs->pres_id)->first();  ?>
         <tr class="t-body">
            <td class="bbottom" style="width: 70%"><?php echo $lender->tmsp_tit;?> <?php echo $lender->tmsp_pnom;?></td>
            <td style="width: 30%; text-align:center"><?php echo $rs->total;?></td>
         </tr>
@endforeach
      </table>
     

      <table class="main-1">
         <tr class="heading">
            <td class="bbottom" colspan="2">HISTÓRICO DE TURNOS <?php echo date('d/m/Y',strtotime($init));?> - <?php echo date('d/m/Y',strtotime($end));?></td>
         </tr>

         <tr class="t-body">
            <td class="bbottom" style="width: 70%">Total Atendidos/Por atender:</td>
            <td style="width: 30%; text-align:center"><?php echo $actives;?></td>
         </tr>
         <tr class="t-body">
            <td class="bbottom" style="width: 70%">Total Cancelados:</td>
            <td style="width: 30%; text-align:center"><?php echo $cancels;?></td>
         </tr>
                           <tr class="t-body">
            <td class="bbottom" style="width: 70%">Total Agendados:</td>
            <td style="width: 30%; text-align:center"><?php echo $shifts;?></td>
         </tr>
      </table>

      <div class="pie">
         <p style="margin-bottom: 0.1px"><img src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/uploads/icons/logo_gris.png';?>"></p>
         <p style="margin-top: 0px"> v4.0 Turnonet 2011-<?php echo date("Y");?> </p>
      </div>
   </body>
</html>
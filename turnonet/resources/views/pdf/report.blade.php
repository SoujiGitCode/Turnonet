<!DOCTYPE html>
<html>
   <head>
      <title><?php echo $title;?></title>
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
      position: relative;
      width: 100%;
      height: 40px;
      float: right;
      text-align: right;
      top: -30px;
      font-size: 09px;
      color: #808080!important;
      }
      .pie img {
      width: 80px;
      margin-bottom: 5px;
      text-align: right;
      }
.row-h th {
      font-size: 10px;
        font-family: Arial, Helvetica, sans-serif;
        text-align: left;
      }
      .row-t td {
      font-size: 11px;
        font-family: Arial, Helvetica, sans-serif;
        border-bottom:2px solid #eee;
        text-align: left;
        padding: 3px
       }
   </style>
   <body>
    <table class="main">
         <tr >
            <td class="bbottom">
               <h1 class="t14 uk-text-upper " style="color: #000;margin-top:0px; padding-top:0px;margin-bottom: 10px; font-weight: bold;font-family: Arial, Helvetica, sans-serif"><?php echo $title;?></h1>
            </td>
            <td width="19%" class="bbottom art">
               <div class="pie">
         <p style="margin-bottom: 0.1px"><img src="<?php echo $_SERVER['DOCUMENT_ROOT'].'/uploads/icons/logo_gris.png';?>"></p>
         <p style="margin-top: 0px"> v4.0 Turnonet 2011-<?php echo date("Y");?> </p>
      </div>
            </td>
         </tr>
      </table>
      
    
     <table style="width: 100%" width="100%" >

      <tr class="row-h">
         
         @if($type==1 && $count_branch>1)
         <th><strong>SUCURSAL</strong></th>
         @endif
         @if($type==1 || $type==2 )
         <th><strong>PRESTADOR</strong></th>
         @endif
         <th><strong>HORA</strong></th>
         <th><strong>CLIENTE</strong></th>

         @if($rep_type!=0)

         @foreach($inputs_add as $row)
         @if($row->field_report=="1" && $row->mi_field==1 )
         <th><strong>FECHA DE NACIMIENTO</strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==2)
         <th><strong>TIPO  DOCUMENTO</strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==3)
         <th><strong>OBRA SOCIAL</strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==4)
         <th><strong>PLAN OBRA SOCIAL</strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==5)
         <th><strong>NRO. DOCUMENTO</strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==6)
         <th><strong>NRO.  OBRA SOCIAL</strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==7)
         <th><strong>TELÉFONO</strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==8)
         <th><strong>CELULAR</strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==9)
         <th><strong><?php echo mb_substr(mb_strtoupper($row->mi_gentxt),0,15);?></strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==10)
         <th><strong><?php echo mb_substr(mb_strtoupper($row->mi_gentxt),0,15);?></strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==11)
         <th><strong><?php echo mb_substr(mb_strtoupper($row->mi_gentxt),0,15);?></strong></th>
         @endif
         @if($row->field_report=="1" && $row->mi_field==12)
         <th><strong><?php echo mb_substr(mb_strtoupper($row->mi_gentxt),0,15);?></strong></th>
         @endif

         @if($row->field_report=="1" && $row->mi_field==13)
         <th><strong><?php echo mb_substr(mb_strtoupper($row->mi_gentxt),0,15);?></strong></th>
         @endif

         @if($row->field_report=="1" && $row->mi_field==14)
         <th><strong><?php echo mb_substr(mb_strtoupper($row->mi_gentxt),0,15);?></strong></th>
         @endif

         @if($row->field_report=="1" && $row->mi_field==15)
         <th><strong><?php echo mb_substr(mb_strtoupper($row->mi_gentxt),0,15);?></strong></th>
         @endif


         @if($row->field_report=="1" && $row->mi_field==16)
         <th><strong><?php echo mb_substr(mb_strtoupper($row->mi_gentxt),0,15);?></strong></th>
         @endif

         @endforeach

         @endif
      </tr>
 
      @foreach($data as $rs)
      <tr class="row-t">
        

         @if($type==1 && $count_branch>1)
         <td style="vertical-align: middle;">{{ $rs['branch'] }}</td>
         @endif
         @if($type==1 || $type==2 )
         <td style="vertical-align: middle;">{{ $rs['lender'] }}</td>
         @endif
 <td style="vertical-align: middle;">{{ $rs['hour'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['name'] }}</td>

         @if($rep_type!=0)

         @foreach($inputs_add as $row)
         @if($row->field_report=="1" && $row->mi_field==1)
         <td style="vertical-align: middle;">

            @if(isset($rs['detail']))

            @if($rs['detail']->usm_fecnac!="0000-00-00")
                {{ date("d/m/Y",strtotime($rs['detail']->usm_fecnac))}}
            @endif

            @endif
         </td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==2)
         <td style="vertical-align: middle;">

            @if(isset($rs['detail'])) {{ $rs['detail']->usm_tipdoc }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==3)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ mb_strtoupper($rs['detail']->usm_obsoc) }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==4)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ mb_strtoupper($rs['detail']->usm_obsocpla) }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==5)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_numdoc }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==6)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_afilnum }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==7)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_tel }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==8)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_cel}} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==9)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_gen1 }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==10)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_gen2 }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==11)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_gen3 }} @endif</td>
         @endif
         @if($row->field_report=="1" && $row->mi_field==12)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_gen4 }} @endif</td>
         @endif

         @if($row->field_report=="1" && $row->mi_field==13)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_gen5 }} @endif</td>
         @endif

         @if($row->field_report=="1" && $row->mi_field==14)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_gen6}} @endif</td>
         @endif

         @if($row->field_report=="1" && $row->mi_field==15)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_gen7}} @endif</td>
         @endif

         @if($row->field_report=="1" && $row->mi_field==16)
         <td style="vertical-align: middle;">
            @if(isset($rs['detail'])) {{ $rs['detail']->usm_gen8 }} @endif</td>
         @endif
         @endforeach
         @endif
      </tr>
      @endforeach

</table>
   </body>
</html
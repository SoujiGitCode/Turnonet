<?php
function clearText($value) {

 $value=strip_tags($value);
 $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
 $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
 $value = str_replace($no_permitidas, $permitidas ,$value);

 $value=preg_replace('/[^a-zA-Z0-9[\s]/s', '', $value);

 $value=trim($value);
 
 return $value;
}
?>
<table style="width: 100%" width="100%">
   <thead>
      <tr>
         <th  width="30" style="width: 30px;"><strong>TURNO</strong></th>
         <th  width="20" style="width: 20px;"><strong>FECHA</strong></th>
         <th  width="20" style="width: 20px;"><strong>HORA</strong></th>
         <th  width="20" style="width: 20px;"><strong>ESTADO</strong></th>
         <th  width="20" style="width: 20px;"><strong>ASISTENCIA</strong></th>
         <th  width="20" style="width: 20px;"><strong>AGENDADO</strong></th>
         <th  width="20" style="width: 20px;"><strong>SOBRETURNO</strong></th>
         <th  width="40" style="width: 40px;"><strong>EMPRESA</strong></th>
         <th  width="40" style="width: 40px;"><strong>SUCURSAL</strong></th>
         <th  width="20" style="width: 20px;"><strong>PRESTADOR</strong></th>
         <th  width="40" style="width: 40px;"><strong>SERVICIOS</strong></th>
         <th  width="50" style="width: 50px;"><strong>CLIENTE</strong></th>
         <th  width="40" style="width: 40px;"><strong>CORREO ELECTRÓNICO</strong></th>
         @foreach($inputs_add as $row)
         @if($row->mi_field==1)
         <th  width="30" style="width: 30px;"><strong>FECHA DE NACIMIENTO</strong></th>
         @endif
         @if($row->mi_field==2)
         <th  width="30" style="width: 30px;"><strong>TIPO Y NRO. DE DOCUMENTO</strong></th>
         @endif
         @if($row->mi_field==3)
         <th  width="30" style="width: 30px;"><strong>OBRA SOCIAL</strong></th>
         @endif
         @if($row->mi_field==4)
         <th  width="30" style="width: 30px;"><strong>PLAN OBRA SOCIAL</strong></th>
         @endif
         @if($row->mi_field==5)
         <th  width="30" style="width: 30px;"><strong>NÚMERO DE DOCUMENTO</strong></th>
         @endif
         @if($row->mi_field==6)
         <th  width="30" style="width: 30px;"><strong>NRO. DE AFILIADO OBRA SOCIAL</strong></th>
         @endif
         @if($row->mi_field==7)
         <th  width="30" style="width: 30px;"><strong>TELÉFONO</strong></th>
         @endif
         @if($row->mi_field==8)
         <th  width="30" style="width: 30px;"><strong>CELULAR</strong></th>
         @endif
         @if($row->mi_field==9)
         <th  width="40" style="width: 40px;"><strong><?php echo mb_strtoupper($row->mi_gentxt);?></strong></th>
         @endif
         @if($row->mi_field==10)
         <th  width="40" style="width: 40px;"><strong><?php echo mb_strtoupper($row->mi_gentxt);?></strong></th>
         @endif
         @if($row->mi_field==11)
         <th  width="40" style="width: 40px;"><strong><?php echo mb_strtoupper($row->mi_gentxt);?></strong></th>
         @endif
         @if($row->mi_field==12)
         <th  width="40" style="width: 40px;"><strong><?php echo mb_strtoupper($row->mi_gentxt);?></strong></th>
         @endif
         @endforeach
      </tr>
   </thead>
   <tbody>
      @foreach($data as $rs)
      <tr>
         <td style="vertical-align: middle;">Cod: {{strval($rs['code'])}}</td>
         <td style="vertical-align: middle;">{{ $rs['created_at'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['hour'] }} {{ $rs['time'] }}</td>
          <td style="vertical-align: middle;">{{ $rs['status'] }}</td>
         <td style="vertical-align: middle;text-transform: uppercase;">
            @if($rs['tu_asist']=='1') ATENDIDO @endif
            @if($rs['tu_asist']=='0') AUSENCIA @endif
            @if($rs['tu_asist']=='2') ASISTENCIA PARCIAL @endif
            @if($rs['tu_asist']=='3') POR ANTENDER @endif
            @if($rs['tu_asist']=='4') SIN DEFINIR @endif
         </td>
         <td style="vertical-align: middle;">
            @if($rs['tu_carga']=='0') PRESTADOR @else CLIENTE @endif
         </td>
         <td style="vertical-align: middle;">
            @if($rs['tu_st']=='0') N/A @else SI @endif
         </td>
         <td style="vertical-align: middle;">{{ $rs['business'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['branch'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['lender'] }}</td>
         <td style="vertical-align: middle;">{{ mb_strtoupper($rs['services']) }}</td>
         <td style="vertical-align: middle;">{{ $rs['name'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['email'] }}</td>


         @foreach($inputs_add as $row)
         @if($row->mi_field==1)
         <td style="vertical-align: middle;">

            @if(isset($rs['detail']))
            @if($rs['detail']->usm_fecnac!="0000-00-00")
                {{ date("d/m/Y",strtotime($rs['detail']->usm_fecnac))}}
            @endif
            @endif
         </td>
         @endif
         @if($row->mi_field==2)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText($rs['detail']->usm_tipdoc); } ?></td>
         @endif
         @if($row->mi_field==3)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText(mb_strtoupper($rs['detail']->usm_obsoc)); } ?></td>
         @endif
         @if($row->mi_field==4)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText(mb_strtoupper($rs['detail']->usm_obsocpla)); } ?></td>
         @endif
         @if($row->mi_field==5)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText($rs['detail']->usm_numdoc); } ?></td>
         @endif
         @if($row->mi_field==6)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText($rs['detail']->usm_afilnum); } ?></td>
         @endif
         @if($row->mi_field==7)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo "tel.".clearText($rs['detail']->usm_tel); } ?></td>
         @endif
         @if($row->mi_field==8)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo "tel.".clearText($rs['detail']->usm_cel); } ?></td>
         @endif
         @if($row->mi_field==9)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText($rs['detail']->usm_gen1); } ?></td>
         @endif
         @if($row->mi_field==10)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText($rs['detail']->usm_gen2); } ?></td>
         @endif
         @if($row->mi_field==11)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText($rs['detail']->usm_gen3); } ?></td>
         @endif
         @if($row->mi_field==12)
         <td style="vertical-align: middle;"><?php if(isset($rs['detail'])){ echo clearText($rs['detail']->usm_gen4); } ?></td>
         @endif
         @endforeach
      </tr>
      @endforeach
   </tbody>
</table>


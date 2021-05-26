<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width">
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
</head><body style="width: 100% !important;min-width: 100%;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100% !important;margin: 0;padding: 0;background-color: #FFFFFF;">
      <title>Turnonet</title>
   <style type="text/css">
      strong{
      font-family: 'Montserrat',sans-serif!important;
      font-weight: 800;
   }
   </style>
   </head>
   <body style="width:100% !important; color:#ffffff; background:#fdfdfd; font-family: 'Montserrat',sans-serif; font-size:13px; line-height:1;" >
      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="background: #fdfdfd">
         @if(isset($btn_email))
      	<tr>
            <td align="left">
               <table cellpadding="0" cellspacing="0" border="0" width="640" align="center">
                  <tr>
                     <tr>
                        <td height="30" style="text-align: center!important; font-size: 11px; color: #666666!important;    height: 30px;font-family: 'Montserrat',sans-serif!important">
                           ¿Tienes problemas para abrir este correo? <a href="<?php echo $btn_email;?>" style="color: #FF5722!important;">Haga clic AQUÍ.</a>
                           <br>
                        </td>
                     </tr>
                  </tr>
               </table>
            </td>
         </tr>
         @endif
         <tr>
            <td align="left">
               <table cellpadding="0" cellspacing="0" border="0" width="640" align="center" style="background: #ffffff!important;
                  border: 1px solid #f7f6f6;
                  border-radius: 5px;">
                  <tr>
                     <td style="text-align: center;    text-align: center;
                        background: #FF5722;
                        border-top-left-radius: 5px;
                        border-top-right-radius: 5px;
                        padding-bottom: 15px;
                        padding-top: 13px;">
                        <a href="https://www.turnonet.com/" target="_blank" style="text-align: center;">
                        <img src="<?php echo url('/')?>/uploads/icons/logo_white.png" alt="Turnonet" width="250" style="width: 250px">
                        </a>
                     </td>
                  </tr>
                  <!--  2 -->
                  <tr>
                     <td height="30">
                     </td>
                  </tr>
                  <tr>
                     <td style="    font-size: 23px;
                        color: #727272;
                        font-weight: 800;
                        height: 5px;
                        text-align: center!important;
                        text-transform: uppercase;
                        font-family: 'Montserrat',sans-serif!important;"><?php echo $title;?></td>
                  </tr>
                  <!-- 3 -->
                  <tr >
                     <td style="color: #666666!important; font-size:14px!important;font-family: 'Montserrat',sans-serif!important; padding: 40px; height: 5px;    line-height: 25px!important;"> <?php  echo $content;?> </td>
                  </tr>
                  <tr>
                     <td style="    padding: 6px;">
                        @foreach($data as $rs)
                        <table style="width: 90%; margin: 0 auto; background: #F4F4F4; border: 2.5px solid #f3f0f0; border-radius: 4px; margin-bottom: 12px; margin-top: 3px" cellpadding="0" cellspacing="0" width="100%" bgcolor="transparent">
                           <tr>
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">Hora:</strong> {{ $rs['hour'] }} {{ $rs['time'] }} 
                              </td>
                           </tr>
                           <tr>
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">Sucursal:</strong> {{ $rs['branch'] }}
                              </td>
                           </tr>
                           <tr>
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">Prestador:</strong> {{ $rs['lender'] }}
                              </td>
                           </tr>
                           @if($rs['services']!="")
                           <tr>
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">Servicios:</strong> {{ mb_strtoupper($rs['services']) }}
                              </td>
                           </tr>
                           @endif
                           <tr>
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">Cliente:</strong> {{ $rs['name'] }}
                              </td>
                           </tr>

                           @if($rep_type!=0)

                           @foreach($inputs_add as $row)
                           @if(isset($rs['detail'])!=0)
                           <tr>
                              @if($row->field_report=="1" && $row->mi_field==1)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">FECHA DE NACIMIENTO:</strong>
                                 @if($rs['detail']->usm_fecnac!="0000-00-00")
                                 {{ date("d/m/Y",strtotime($rs['detail']->usm_fecnac))}}
                                 @endif
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==2)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">TIPO Y NRO. DE DOCUMENTO</strong>
                                 {{ $rs['detail']->usm_tipdoc }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==3)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">OBRA SOCIAL:</strong>
                                 {{ mb_strtoupper($rs['detail']->usm_obsoc) }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==4)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">PLAN OBRA SOCIAL:</strong>
                                 {{ mb_strtoupper($rs['detail']->usm_obsocpla) }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==5)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">NÚMERO DE DOCUMENTO:</strong>
                                 {{ $rs['detail']->usm_numdoc }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==6)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">NRO. DE AFILIADO OBRA SOCIAL:</strong>
                                 {{ $rs['detail']->usm_afilnum }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==7)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">TELÉFONO:</strong>
                                 {{ $rs['detail']->usm_tel }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==8)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;">
                                 <strong style="color: #FF5722!important;text-transform:uppercase">CELULAR:</strong>
                                 {{ $rs['detail']->usm_cel}}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==9)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;text-transform:uppercase">
                                 <strong style="color: #FF5722!important;text-transform:uppercase"><?php echo mb_strtoupper($row->mi_gentxt);?>:</strong>
                                 {{ $rs['detail']->usm_gen1 }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==10)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;text-transform:uppercase">
                                 <strong style="color: #FF5722!important;text-transform:uppercase"><?php echo mb_strtoupper($row->mi_gentxt);?>:</strong>
                                 {{ $rs['detail']->usm_gen2 }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==11)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;text-transform:uppercase">
                                 <strong style="color: #FF5722!important;text-transform:uppercase"><?php echo mb_strtoupper($row->mi_gentxt);?>:</strong>
                                 {{ $rs['detail']->usm_gen3 }}
                              </td>
                              @endif
                              @if($row->field_report=="1" && $row->mi_field==12)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;text-transform:uppercase">
                                 <strong style="color: #FF5722!important;text-transform:uppercase"><?php echo mb_strtoupper($row->mi_gentxt);?>:</strong>
                                 {{ $rs['detail']->usm_gen4 }}
                              </td>
                              @endif

                              @if($row->field_report=="1" && $row->mi_field==13)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;text-transform:uppercase">
                                 <strong style="color: #FF5722!important;text-transform:uppercase"><?php echo mb_strtoupper($row->mi_gentxt);?>:</strong>
                                 {{ $rs['detail']->usm_gen5 }}
                              </td>
                              @endif

                              @if($row->field_report=="1" && $row->mi_field==14)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;text-transform:uppercase">
                                 <strong style="color: #FF5722!important;text-transform:uppercase"><?php echo mb_strtoupper($row->mi_gentxt);?>:</strong>
                                 {{ $rs['detail']->usm_gen6 }}
                              </td>
                              @endif

                              @if($row->field_report=="1" && $row->mi_field==15)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;text-transform:uppercase">
                                 <strong style="color: #FF5722!important;text-transform:uppercase"><?php echo mb_strtoupper($row->mi_gentxt);?>:</strong>
                                 {{ $rs['detail']->usm_gen7 }}
                              </td>
                              @endif


                              @if($row->field_report=="1" && $row->mi_field==16)
                              <td  style="color: #666666!important;font-size:12px!important;font-family: 'Montserrat',sans-serif!important;padding:6px;text-transform:uppercase">
                                 <strong style="color: #FF5722!important;text-transform:uppercase"><?php echo mb_strtoupper($row->mi_gentxt);?>:</strong>
                                 {{ $rs['detail']->usm_gen8 }}
                              </td>
                              @endif
                           </tr>
                           @endif
                           @endforeach


                           @endif
                          
                        </table>
                       
                        @endforeach
                     </td>
                  </tr>
                  <tr>
                     <td height="140">
                     </td>
                  </tr>
                  <tr>
                     <td >
                        <table style="width: 100%; margin: 0 auto" cellpadding="0" cellspacing="0" width="100%" bgcolor="transparent">
                           <tbody>
                              <tr style="vertical-align:top">
                                 <td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top;text-align:center;font-size:0;background-color:#ffffff">
                                    <div style="display:inline-block;vertical-align:top;text-align:center;width:200px">
                                       <table style="border-spacing:0;border-collapse:collapse;vertical-align:top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                                          <tbody>
                                             <tr style="vertical-align:top">
                                                <td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top;background-color:#ffffff;font-size:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;">
                                                   <table style="border-spacing:0;border-collapse:collapse;vertical-align:top" border="0" cellspacing="0" cellpadding="0" width="100%">
                                                      <tbody>
                                                         <tr>
                                                            <td style="text-align: center; padding-top: 4px; padding-bottom: 4px" align="center">
                                                               <div style="font-size:12px;font-style:normal;font-weight:400">
                                                                  <a href="https://cognitive.la/"><img src="<?php echo url('/');?>/uploads/icons/cognitive.png" width="180" style="width: 180px"></a>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table style="width: 100%; margin: 0 auto" cellpadding="0" cellspacing="0" width="100%" bgcolor="transparent">
                           <tbody>
                              <tr style="vertical-align:top">
                                 <td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top;text-align:center;font-size:0;background-color:#ffffff">
                                    <div style="display:inline-block;vertical-align:top;text-align:center;width:200px">
                                       <table style="border-spacing:0;border-collapse:collapse;vertical-align:top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                                          <tbody>
                                             <tr style="vertical-align:top">
                                                <td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top;background-color:#ffffff;font-size:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;">
                                                   <table style="border-spacing:0;border-collapse:collapse;vertical-align:top" border="0" cellspacing="0" cellpadding="0" width="100%">
                                                      <tbody>
                                                         <tr>
                                                            <td style="text-align: center; padding-top: 4px; padding-bottom: 4px" align="center">
                                                               <a href="https://www.emailfacil.com.ar"><img src="<?php echo url('/');?>/uploads/icons/emailfacil.png" width="160" style="width: 180px"></a>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                    <div style="display:inline-block;vertical-align:top;text-align:center;width:200px">
                                       <table style="border-spacing:0;border-collapse:collapse;vertical-align:top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                                          <tbody>
                                             <tr style="vertical-align:top">
                                                <td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top;background-color:#ffffff;font-size:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;">
                                                   <table style="border-spacing:0;border-collapse:collapse;vertical-align:top" border="0" cellspacing="0" cellpadding="0" width="100%">
                                                      <tbody>
                                                         <tr>
                                                            <td style="text-align: center; padding-top: 4px; padding-bottom: 4px" align="center">
                                                               <a href="https://www.smsfacil.com.ar"><img src="<?php echo url('/');?>/uploads/icons/smsfacil.png" width="160" style="width: 180px"></a>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                    <div style="display:inline-block;vertical-align:top;text-align:center;width:200px">
                                       <table style="border-spacing:0;border-collapse:collapse;vertical-align:top" cellpadding="0" cellspacing="0" align="center" width="100%" border="0">
                                          <tbody>
                                             <tr style="vertical-align:top">
                                                <td style="word-break:break-word;border-collapse:collapse!important;vertical-align:top;background-color:#ffffff;font-size:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;">
                                                   <table style="border-spacing:0;border-collapse:collapse;vertical-align:top" border="0" cellspacing="0" cellpadding="0" width="100%">
                                                      <tbody>
                                                         <tr>
                                                            <td style="text-align: center; padding-top: 4px; padding-bottom: 4px" align="center">
                                                               <div style="font-size:12px;font-style:normal;font-weight:400">
                                                                  <a href=""><img src="<?php echo url('/');?>/uploads/icons/whatabot.png" width="160" style="width: 180px"></a>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
                  <tr >
                     <td  style="text-align: center!important; font-size: 11px!important; color: #666666!important;font-family: 'Montserrat',sans-serif!important; border-top: 1px solid #f7f6f6;">
                        <div style="display:inline-block;vertical-align:top;text-align:center;width:200px">
                           <p style="text-align: center!important; font-size: 11px; color: #666666!important;font-family: 'Montserrat',sans-serif!important">
                              <a href="tlf:+541135309345" target="_blank" style=" color: #666666!important; text-decoration: none;font-family: 'Montserrat',sans-serif!important;text-align: center!important;"><img border="0" src="<?php echo url('/');?>/uploads/icons/phone.png" width="16" height="16"  style="width:16px; height: 16px"> +54 (11) 3530 9345 </a> 
                           </p>
                        </div>
                        <div style="display:inline-block;vertical-align:top;text-align:center;width:200px">
                           <p style="text-align: center!important; font-size: 11px; color: #666666!important;font-family: 'Montserrat',sans-serif!important">
                              <a href="mailto:info@turnonet.com" style=" color: #666666!important; text-decoration: none;font-family: 'Montserrat',sans-serif!important;text-align: center!important;"><img border="0" src="<?php echo url('/');?>/uploads/icons/email.png" width="16" height="16"  style="width:16px; height: 16px">  info@turnonet.com </a>
                           </p>
                        </div>
                     </td>
                  </tr>
                  <tr>
                     <td height="3">
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
            <td align="left">
               <table cellpadding="0" cellspacing="0" border="0" width="640" align="center">
                  <tr>
                     <td height="30" style="text-align: center!important; font-size: 11px; color: #666666!important;    height: 30px;font-family: 'Montserrat',sans-serif!important">
                        <p stye="text-align: center!important; font-size: 11px; color: #666666!important;    height: 30px;font-family: 'Montserrat',sans-serif!important">Turnonet &copy; 2011-<?php echo date("Y");?>. Todos los derechos reservados</p>
                     </td>
                     <tr>
                        <td height="30" style="text-align: center!important; font-size: 11px; color: #666666!important;    height: 30px;font-family: 'Montserrat',sans-serif!important">
                           ¿No deseas recibir noticias de Turnonet? <a href="https://www.turnonet.com" style="color: #FF5722!important;">Date de Baja</a>
                        </td>
                     </tr>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
      
   </body>
</html>
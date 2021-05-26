@extends('layouts.template_frame')
@section('content')
<?php 
   function nameMonth($month) {
     switch ($month) {
       case 1:
       $month = 'Enero';
       break;
       case 2:
       $month = 'Febrero';
       break;
       case 3:
       $month = 'Marzo';
       break;
       case 4:
       $month = 'Abril';
       break;
       case 5:
       $month = 'Mayo';
       break;
       case 6:
       $month = 'Junio';
       break;
       case 7:
       $month = 'Julio';
       break;
       case 8:
       $month = 'Agosto';
       break;
       case 9:
       $month = 'Septiembre';
       break;
       case 10:
       $month = 'Octubre';
       break;
       case 11:
       $month = 'Noviembre';
       break;
       case 12:
       $month = 'Diciembre';
       break;
     }
     return $month;
   }
   
   function nameDay($day) {
   
           switch ($day) {
               case 0:
               $day = 'Domingo';
               break;
               case 1:
               $day = 'Lunes';
               break;
               case 2:
               $day = 'Martes';
               break;
               case 3:
               $day = 'Miércoles';
               break;
               case 4:
               $day = 'Jueves';
               break;
               case 5:
               $day = 'Viernes';
               break;
               case 6:
               $day = 'Sábado';
               break;
           }
           return $day;
    }
   
   ?>

<?php  $frame = DB::table('frame')->where('emp_id', $get_business->em_id)->first(); ?>

<?php $amount=0;?>
@if($lender->tmsp_pagoA=='ALTA' && date("Y-m-d")<date("Y-m-d",strtotime($get_business->expired_mp)) && $get_business->access_token !="" )
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<?php echo Html::script('frame/js/jquery.numeric.min.js')?>
@endif

@if($frame->header==0)
<a href="<?php echo url('/');?>/<?php echo $frame->url;?>/disponibilidad/<?php echo $lender->url;?>" class="btn-back"></a>
@endif
<form id="regmdtur">
   <input type="hidden" id="shift" name="shift" value="<?php echo $get_shift->tu_id;?>">
   <div class="container container-single ptop">
      @if($frame->name==0)
      <div class="row">
         <div class="col-md-2 col-sm-3 col-xs-12 hidden-xs">
            <?php
               $image=url('/').'/img/emptylogo.png';
               
               $get_business=DB::table('tu_emps')->where('em_id',$lender->emp_id)->first();
               
               $sucursal=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first();
               
               if(isset($get_business)!=0 && $get_business->em_pfot!=""){
                 $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
               }
               if(isset($sucursal)!=0 && $sucursal->suc_pfot!=""){
                 $image="https://www.turnonet.com/fotos/sucursales/".$sucursal->suc_pfot;
               }
               if($lender->tmsp_pfot!=""){
                 $image="https://www.turnonet.com/fotos/prestadores/".$lender->tmsp_pfot;
               }
               
               ?>
            <img src="<?php echo $image;?>" class="img-press-1">
         </div>
         <div class="col-md-10 col-sm-9 col-xs-12 no-padding-desktop">
            <div class="tit" style="margin-bottom: 0vw;">
               <?php echo $lender->tmsp_tit;?> <?php echo mb_strtoupper($lender->tmsp_pnom);?>     
            </div>
            <?php $address=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first(); ?>
            @if(isset($address)!=0)
            <div class="subtit "> <?php echo $address->suc_dom;?> <?php echo $address->suc_domnum;?> <?php echo $address->suc_dompiso;?> </div>
            @endif
         </div>
      </div>
      @endif
      <div class="row">
         <div class="col-md-8 col-sm-8 hidden-xs">
         </div>
         <div class=" col-md-4 col-sm-4 hidden-xs text-right">
            <div class="volver2">
               <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
               <a href="<?php echo url('/');?>/<?php echo $frame->url;?>/disponibilidad/<?php echo $lender->url;?>">Solicitar Turno</a>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12 Datosturn" id="Datosturn">
            <div class="nom">Datos del turno</div>
            <div class="turin">
               <strong>Fecha:</strong> <?php echo nameDay(date("w", strtotime($get_shift->tu_fec))) . ' ' . date("d", strtotime($get_shift->tu_fec)) . ' de ' . NameMonth(date("m", strtotime($get_shift->tu_fec))); ?><br>
               <strong>Horario:</strong> <?php echo date("H:i", strtotime($get_shift->tu_hora));?> hs.
               <br>
               @if(isset($address)!=0)
               <strong>Sucursal:</strong> <?php echo $address->suc_dom;?> <?php echo $address->suc_domnum;?> <?php echo $address->suc_dompiso;?> <br> 
               @endif
               @if($get_shift->tu_servid!="0" &&  $get_shift->tu_servid!="")
               <strong>Servicios Solicitados:</strong><br>
               <?php
                  if (substr_count($get_shift->tu_servid, '-') <= 0){
                  
                  
                  $get_service = DB::table('tu_emps_serv')->where('serv_id',$get_shift->tu_servid)->first();
                  
                   if (isset($get_service) != 0) {
                    
                  if( $get_business->em_mp=='1' && $lender->tmsp_pagoA=='ALTA' &&  $get_service->serv_price!=0){
                     echo trim($get_service->serv_nom).' $ '.number_format($get_service->serv_price, 2, ",", ".").'<br>';
                   }else{
                  
                    echo trim($get_service->serv_nom).'<br>';
                  
                  }
                     $amount=$get_service->serv_price;
                  }
                  
                  }else{
                  
                  
                  for ($i = 0; $i <= substr_count($get_shift->tu_servid, '-'); $i++) {
                   $service = explode('-', $get_shift->tu_servid);
                   $get_service = DB::table('tu_emps_serv')->where('serv_id',$service[$i])->first();
                  
                   if (isset($get_service) != 0) {
                  
                     if( $get_business->em_mp=='1' && $lender->tmsp_pagoA=='ALTA' && $get_service->serv_price!=0){
                     echo trim($get_service->serv_nom).' $ '.number_format($get_service->serv_price, 2, ",", ".").'<br>';
                   }else{
                  
                    echo trim($get_service->serv_nom).'<br>';
                  
                  }
                    
                     $amount=$amount+$get_service->serv_price;
                  }
                  
                  }
                  }
                  
                  ?>
               @endif
            </div>
         </div>
      </div>
      <div class="row">
         @if(count($inputs_add)!=0)
         <div class="col-md-12">
            <div class="inforeq">
               <b><br>El prestador requiere de los siguientes datos adicionales para confirmar el turno.</b><br><br>
               <span>
               El turno se encuentra reservado por los próximos 10<small style="color: #fd2923">*</small> minutos mientras completa los datos solicitados. En caso de superar este tiempo, el turno quedara liberado.
               </span>
            </div>
         </div>
         <div class="col-md-12">
            <br>
            @if(isset($data)!=0)
            @foreach($inputs_add as $rs)
            @if($rs->mi_field==1)
            <?php 
               if($data->usm_fecnac=='0000-00-00'){
               
                  $day='';
                  $month='';
                  $year='';
               
               
               }else{
                  $date = explode('-',$data->usm_fecnac);
               $day=$date[2];
                  $month=$date[1];
                  $year=$date[0];
               
               }
               
               
               
               ?>
            <div class="form-group">
               <label>Fecha de nacimiento @if($rs->mi_ob==1) * @endif</label>
               <div class="row">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                     {!!Form::select('date_'.$rs->mi_field.'_dd', ['01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31'],$day,['placeholder' => 'Día','class' => 'form-control','id'=>'date_'.$rs->mi_field.'_dd'])!!}
                  </div>
                  <div class="col-md-4 col-sm-4 col-xs-4">
                     {!!Form::select('date_'.$rs->mi_field.'_mm', ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'],$month,['placeholder' => 'Mes','class' => 'form-control','id'=>'date_'.$rs->mi_field.'_mm'])!!}
                  </div>
                  <div class="col-md-4 col-sm-4 col-xs-4"> 
                     {!!Form::select('date_'.$rs->mi_field, ['2020'=>'2020','2019'=>'2019','2018'=>'2018','2017'=>'2017','2016'=>'2016','2015'=>'2015', '2014'=>'2014', '2013'=>'2013','2012'=>'2012','2011'=>'2011','2010'=>'2010','2009'=>'2009','2008'=>'2008', '2007'=>'2007','2006'=>'2006','2005'=>'2005','2004'=>'2004','2003'=>'2003','2002'=>'2002','2001'=>'2001','2000'=>'2000', '1999'=>'1999', '1998'=>'1998', '1997'=>'1997', '1996'=>'1996', '1995'=>'1995', '1994'=>'1994','1993'=>'1993', '1992'=>'1992', '1991'=>'1991', '1990'=>'1990', '1989'=>'1989', '1988'=>'1988', '1987'=>'1987','1986'=>'1986', '1985'=>'1985', '1984'=>'1984', '1983'=>'1983', '1982'=>'1982', '1981'=>'1981', '1980'=>'1980','1979'=>'1979', '1978'=>'1978', '1977'=>'1977', '1976'=>'1976', '1975'=>'1975', '1974'=>'1974', '1973'=>'1973','1972'=>'1972', '1971'=>'1971', '1970'=>'1970', '1969'=>'1969', '1968'=>'1968', '1967'=>'1967', '1966'=>'1966','1965'=>'1965', '1964'=>'1964', '1963'=>'1963', '1962'=>'1962', '1961'=>'1961', '1960'=>'1960', '1959'=>'1959','1958'=>'1958', '1957'=>'1957', '1956'=>'1956', '1955'=>'1955', '1954'=>'1954', '1953'=>'1953', '1952'=>'1952','1951'=>'1951', '1950'=>'1950','1949'=>'1949','1948'=>'1948', '1947'=>'1947', '1946'=>'1946', '1945'=>'1945', '1944'=>'1944', '1943'=>'1943', '1942'=>'1942','1941'=>'1941', '1940'=>'1940','1939'=>'1939','1938'=>'1938', '1937'=>'1937', '1936'=>'1936', '1935'=>'1935', '1934'=>'1934', '1933'=>'1933', '1932'=>'1932','1931'=>'1931', '1930'=>'1930','1929'=>'1929','1928'=>'1928', '1927'=>'1927', '1926'=>'1926', '1925'=>'1925', '1924'=>'1924', '1923'=>'1923', '1922'=>'1922','1921'=>'1921', '1920'=>'1920','1919'=>'1919','1918'=>'1918', '1917'=>'1917', '1916'=>'1916', '1915'=>'1915', '1914'=>'1914', '1913'=>'1913', '1912'=>'1912','1911'=>'1911', '1910'=>'1910'],$year,['placeholder' =>'Año','class' => 'form-control','id'=>'date_'.$rs->mi_field])!!}
                  </div>
               </div>
            </div>
            @endif
            @if($rs->mi_field==2)
            <div class="form-group">
               <label>Tipo de documento @if($rs->mi_ob==1) * @endif</label>
               <select name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control">
                  <option value="">Seleccionar</option>
                  <option value="DNI" @if($rs->usm_tipdoc=='DNI') selected="selected" @endif>DNI</option>
                  <option value="CI" @if($rs->usm_tipdoc=='CI') selected="selected" @endif>CI</option>
                  <option value="LE" @if($rs->usm_tipdoc=='LE') selected="selected" @endif>LE</option>
                  <option value="LC" @if($rs->usm_tipdoc=='LC') selected="selected" @endif>LC</option>
               </select>
            </div>
            @endif
            @if($rs->mi_field==3)
            <div class="form-group">
               <label>Obra social @if($rs->mi_ob==1) * @endif</label>
               @if($rs->mi_tipofield!=2)
               <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control"  placeholder="Ingresá la obra social" value="<?php echo $data->usm_obsoc;?>" />
               @else
               <select name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control">
                  <option value="">Seleccioná la obra social</option>
                  <?php $social_work=DB::table('tu_emps_ob')->where('eob_presid',$lender->tmsp_id)->get(); ?>
                  @foreach($social_work as $rows)
                  <?php   $get_social = DB::table('tu_obsoc')->where('os_id',$rows->eob_obid)->first(); ?>
                  <option value="<?php echo mb_strtoupper($get_social->os_nomp);?>"  @if(mb_strtoupper($get_social->os_nomp)==$data->usm_obsoc) selected="selected" @endif><?php echo mb_strtoupper($get_social->os_nomp);?></option>
                  @endforeach
               </select>
               @endif
            </div>
            @endif
            @if($rs->mi_field==4)
            <div class="form-group">
               <label>Plan Obra Social @if($rs->mi_ob==1) * @endif</label>
               <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el plan" value="<?php echo $data->usm_obsocpla;?>" />
            </div>
            @endif
            @if($rs->mi_field==5)
            <div class="form-group">
               <label>Tipo y Nro. de Documento @if($rs->mi_ob==1) * @endif</label>
               <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de documento" value="<?php echo $data->usm_numdoc;?>" />
            </div>
            @endif
            @if($rs->mi_field==6)
            <div class="form-group">
               <label>Nro. de Afiliado Obra Social @if($rs->mi_ob==1) * @endif</label>
               <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de afiliado" value="<?php echo $data->usm_afilnum;?>"/>
            </div>
            @endif
            @if($rs->mi_field==7)
            <div class="form-group">
               <label>Teléfono @if($rs->mi_ob==1) * @endif</label>
               <input type="tel" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de teléfono" value="<?php echo $data->usm_tel;?>"/>
            </div>
            @endif
            @if($rs->mi_field==8)
            <div class="form-group">
               <label>Celular @if($rs->mi_ob==1) * @endif</label>
               <input type="tel" name="f_<?php echo $rs->mi_field;?>" required pattern="[0-9]+" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número celular" value="<?php echo $data->usm_cel;?>" />
            </div>
            @endif
            @if($rs->mi_field==9)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) * @endif</label>
            </div>
            <div class="form-group">
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value="<?php echo $data->usm_gen1;?>"/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" @if($data->usm_gen1=="SI") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO" @if($data->usm_gen1=="NO") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif
            @if($rs->mi_field==10)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) * @endif</label>
            </div>
            <div class="form-group">
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value="<?php echo $data->usm_gen2;?>"/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" @if($data->usm_gen2=="SI") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO" @if($data->usm_gen2=="NO") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif
            @if($rs->mi_field==11)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) * @endif</label>
            </div>
            <div class="form-group">
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value="<?php echo $data->usm_gen3;?>"/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" @if($data->usm_gen3=="SI") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO" @if($data->usm_gen3=="NO") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif
            @if($rs->mi_field==12)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) * @endif</label>
            </div>
            <div class="form-group">
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value="<?php echo $data->usm_gen4;?>" />
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" @if($data->usm_gen4=="SI") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO" @if($data->usm_gen4=="NO") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif


            @if($rs->mi_field==13)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) * @endif</label>
            </div>
            <div class="form-group">
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value="<?php echo $data->usm_gen5;?>" />
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" @if($data->usm_gen5=="SI") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO" @if($data->usm_gen5=="NO") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif


            @if($rs->mi_field==14)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) * @endif</label>
            </div>
            <div class="form-group">
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value="<?php echo $data->usm_gen6;?>" />
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" @if($data->usm_gen6=="SI") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO" @if($data->usm_gen6=="NO") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif

            @if($rs->mi_field==15)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) * @endif</label>
            </div>
            <div class="form-group">
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value="<?php echo $data->usm_gen7;?>" />
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" @if($data->usm_gen7=="SI") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO" @if($data->usm_gen7=="NO") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif



            @if($rs->mi_field==16)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) * @endif</label>
            </div>
            <div class="form-group">
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value="<?php echo $data->usm_gen8;?>" />
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" @if($data->usm_gen8=="SI") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO" @if($data->usm_gen8=="NO") checked="checked" @endif>
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif



            @endforeach
            @else
            @foreach($inputs_add as $rs)
            @if($rs->mi_field==1)
            <div class="form-group">
               <label>Fecha de nacimiento @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               <div class="row">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                     <select name="date_<?php echo $rs->mi_field;?>_dd" id="date_<?php echo $rs->mi_field;?>_dd" class="form-control">
                        <option selected="selected" value="">Día</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                     </select>
                  </div>
                  <div class="col-md-4 col-sm-4 col-xs-4">
                     <select class="form-control" name="date_<?php echo $rs->mi_field;?>_mm" id="date_<?php echo $rs->mi_field;?>_mm">
                        <option selected="selected" value="">Mes</option>
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                     </select>
                  </div>
                  <div class="col-md-4 col-sm-4 col-xs-4">
                     <select class="form-control" name="date_<?php echo $rs->mi_field;?>" id="date_<?php echo $rs->mi_field;?>">
                        <option selected="selected" value="">Año</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                        <option value="2016">2016</option>
                        <option value="2015">2015</option>
                        <option value="2014">2014</option>
                        <option value="2013">2013</option>
                        <option value="2012">2012</option>
                        <option value="2011">2011</option>
                        <option value="2010">2010</option>
                        <option value="2009">2009</option>
                        <option value="2008">2008</option>
                        <option value="2007">2007</option>
                        <option value="2006">2006</option>
                        <option value="2005">2005</option>
                        <option value="2004">2004</option>
                        <option value="2003">2003</option>
                        <option value="2002">2002</option>
                        <option value="2001">2001</option>
                        <option value="2000">2000</option>
                        <option value="1999">1999</option>
                        <option value="1998">1998</option>
                        <option value="1997">1997</option>
                        <option value="1996">1996</option>
                        <option value="1995">1995</option>
                        <option value="1994">1994</option>
                        <option value="1993">1993</option>
                        <option value="1992">1992</option>
                        <option value="1991">1991</option>
                        <option value="1990">1990</option>
                        <option value="1989">1989</option>
                        <option value="1988">1988</option>
                        <option value="1987">1987</option>
                        <option value="1986">1986</option>
                        <option value="1985">1985</option>
                        <option value="1984">1984</option>
                        <option value="1983">1983</option>
                        <option value="1982">1982</option>
                        <option value="1981">1981</option>
                        <option value="1980">1980</option>
                        <option value="1979">1979</option>
                        <option value="1978">1978</option>
                        <option value="1977">1977</option>
                        <option value="1976">1976</option>
                        <option value="1975">1975</option>
                        <option value="1974">1974</option>
                        <option value="1973">1973</option>
                        <option value="1972">1972</option>
                        <option value="1971">1971</option>
                        <option value="1970">1970</option>
                        <option value="1969">1969</option>
                        <option value="1968">1968</option>
                        <option value="1967">1967</option>
                        <option value="1966">1966</option>
                        <option value="1965">1965</option>
                        <option value="1964">1964</option>
                        <option value="1963">1963</option>
                        <option value="1962">1962</option>
                        <option value="1961">1961</option>
                        <option value="1960">1960</option>
                        <option value="1959">1959</option>
                        <option value="1958">1958</option>
                        <option value="1957">1957</option>
                        <option value="1956">1956</option>
                        <option value="1955">1955</option>
                        <option value="1954">1954</option>
                        <option value="1953">1953</option>
                        <option value="1952">1952</option>
                        <option value="1951">1951</option>
                        <option value="1950">1950</option>
                        <option value="1949">1949</option>
                        <option value="1948">1948</option>
                        <option value="1947">1947</option>
                        <option value="1946">1946</option>
                        <option value="1945">1945</option>
                        <option value="1944">1944</option>
                        <option value="1943">1943</option>
                        <option value="1942">1942</option>
                        <option value="1941">1941</option>
                        <option value="1940">1940</option>
                        <option value="1939">1939</option>
                        <option value="1938">1938</option>
                        <option value="1937">1937</option>
                        <option value="1936">1936</option>
                        <option value="1935">1935</option>
                        <option value="1934">1934</option>
                        <option value="1933">1933</option>
                        <option value="1932">1932</option>
                        <option value="1931">1931</option>
                        <option value="1930">1930</option>
                        <option value="1929">1929</option>
                        <option value="1928">1928</option>
                        <option value="1927">1927</option>
                        <option value="1926">1926</option>
                        <option value="1925">1925</option>
                        <option value="1924">1924</option>
                        <option value="1923">1923</option>
                        <option value="1922">1922</option>
                        <option value="1921">1921</option>
                        <option value="1920">1920</option>
                        <option value="1919">1919</option>
                        <option value="1918">1918</option>
                        <option value="1917">1917</option>
                        <option value="1916">1916</option>
                        <option value="1915">1915</option>
                        <option value="1914">1914</option>
                        <option value="1913">1913</option>
                        <option value="1912">1912</option>
                        <option value="1911">1911</option>
                        <option value="1910">1910</option>
                     </select>
                  </div>
               </div>
            </div>
            @endif
            @if($rs->mi_field==2)
            <div class="form-group">
               <label>Tipo de documento @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               <select name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control">
                  <option value="">Seleccionar</option>
                  <option value="DNI" @if($rs->usm_tipdoc=='DNI') selected="selected" @endif>DNI</option>
                  <option value="CI" @if($rs->usm_tipdoc=='CI') selected="selected" @endif>CI</option>
                  <option value="LE" @if($rs->usm_tipdoc=='LE') selected="selected" @endif>LE</option>
                  <option value="LC" @if($rs->usm_tipdoc=='LC') selected="selected" @endif>LC</option>
               </select>
            </div>
            @endif
            @if($rs->mi_field==3)
            <div class="form-group">
               <label>Obra social @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
              @if($rs->mi_tipofield!=2)
               <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control"  placeholder="Ingresá la obra social" value="" />
               @else
               <select name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control">
                  <option value="">Seleccioná la obra social</option>
                  <?php $social_work=DB::table('tu_emps_ob')->where('eob_presid',$lender->tmsp_id)->get(); ?>
                  @foreach($social_work as $rows)
                  <?php   $get_social = DB::table('tu_obsoc')->where('os_id',$rows->eob_obid)->first(); ?>
                  <option value="<?php echo mb_strtoupper($get_social->os_nomp);?>"><?php echo mb_strtoupper($get_social->os_nomp);?></option>
                  @endforeach
               </select>
               @endif
            </div>
            @endif
            @if($rs->mi_field==4)
            <div class="form-group">
               <label>Plan Obra Social @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el plan" value="" />
            </div>
            @endif
            @if($rs->mi_field==5)
            <div class="form-group">
               <label>Tipo y Nro. de Documento @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de documento" value="" />
            </div>
            @endif
            @if($rs->mi_field==6)
            <div class="form-group">
               <label>Nro. de Afiliado Obra Social @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de afiliado" value=""/>
            </div>
            @endif
            @if($rs->mi_field==7)
            <div class="form-group">
               <label>Teléfono @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               <input type="tel" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de teléfono" value=""/>
            </div>
            @endif
            @if($rs->mi_field==8)
            <div class="form-group">
               <label>Celular @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               <input type="tel" name="f_<?php echo $rs->mi_field;?>" required pattern="[0-9]+" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número celular" value="" />
            </div>
            @endif
            @if($rs->mi_field==9)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" checked="checked">
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO">
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif
            @if($rs->mi_field==10)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" checked="checked">
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO">
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif
            @if($rs->mi_field==11)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" checked="checked">
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO">
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif
            @if($rs->mi_field==12)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" checked="checked">
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO">
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif


            @if($rs->mi_field==13)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" checked="checked">
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO">
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif


            @if($rs->mi_field==14)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" checked="checked">
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO">
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif


            @if($rs->mi_field==15)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" checked="checked">
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO">
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif


            @if($rs->mi_field==16)
            <div class="form-group">
               <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> @if($rs->mi_ob==1) <small style="color: #fd2923">*</small> @endif</label>
               @if($rs->mi_tipofield==1)
               <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
               @else
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI" checked="checked">
                  <label for="f_<? echo $rs->mi_field; ?>_1"><span></span>SI</label>
               </div>
               <div class="demo-radio" style="clear: both;">
                  <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_2" value="NO">
                  <label for="f_<? echo $rs->mi_field; ?>_2"><span></span>NO</label>
               </div>
               @endif
            </div>
            @endif



            @endforeach
            @endif
            @if($lender->tmsp_pagoA!='ALTA')
            <div class="form-group">
               <label style="color: #fd2923!important">Campos requeridos (*)</label>
            </div>
            <div class="form-group text-right" >
               <button  type="button" class="btn btn-default" onclick="ValidarFormulario()" style="float: right;" id="btn-create" ><i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Datos</button>
            </div>
            @endif
         </div>
         @endif
      </div>
      @if($lender->tmsp_pagoA=='ALTA' && $amount!=0 && date("Y-m-d")<date("Y-m-d",strtotime($get_business->expired_mp)) && $get_business->access_token !="" )
      <div class="row">
         <div class="col-md-8 col-sm-8 col-xs-8">
            <div class="nom">
               <strong>Realizá tu Pago
               </strong>
            </div>
            <p class="text-p">Por favor Ingresá aquí sus datos de pago:</p>
            <br>
         </div>
         <div class="col-md-4 col-sm-4 col-xs-4">
            <div class="img-header">
               <img src="https://www.turnonet.com/frame/imagenes/mplogo.png" class="img-pay">
            </div>
         </div>
         <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
               <label>Número de tarjeta de crédito o débito: <small style="color: #fd2923">*</small></label>
               <input type="text" id="cardNumber" data-checkout="cardNumber" class="form-control" placeholder="Número de tarjeta de crédito o débito"
                  maxlength="16" />
            </div>
            <div class="form-group">
               <label>Últimos 3 números al dorso de la tarjeta: <small style="color: #fd2923">*</small></label>
               <input type="text" id="securityCode" class="form-control" data-checkout="securityCode" placeholder="Código de seguridad"
                  maxlength="3" />
            </div>
            <div class="form-group">
               <label>Fecha de vencimiento de la tarjeta: <small style="color: #fd2923">*</small></label>
               <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-6">
                     <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" class="form-control" placeholder="Mes"
                        maxlength="2" />
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6">
                     <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" class="form-control" placeholder="Año"
                        maxlength="4" />
                  </div>
               </div>
            </div>
            <div class="form-group">
               <label>Títular de la tarjeta:  <small style="color: #fd2923">*</small></label>
               <input type="text" id="cardholderName" data-checkout="cardholderName" class="form-control" placeholder="Nombre de titular"
                  />
            </div>
            <div class="form-group">
               <label>Tipo de documento: <small style="color: #fd2923">*</small></label>
               <select id="docType" class="form-control" data-checkout="docType">
               </select>
            </div>
            <div class="form-group">
               <label>Número del documento: <small style="color: #fd2923">*</small></label>
               <input type="text" id="docNumber" data-checkout="docNumber" class="form-control" placeholder="Número del documento" />
            </div>
            <div class="form-group">
               <label style="color: #fd2923!important">Campos requeridos (*)</label>
            </div>
            <div class="form-group ">
               <div class="nom nom-movil">Total a pagar : $ <?php echo number_format($amount, 2, ",", ".");?></div>
               </h6>
            </div>
            <div class="form-group" style="text-align: right;">
               <br>
               <input type="hidden" id="paymentMethodId" name="paymentMethodId" />
               <input type="hidden" id="token" name="token" />
               <input type="hidden" id="status" name="status" />
               <input type="hidden" id="cause_code" name="cause_code" />
               <input type="hidden" name="amount" value="<?php echo $amount;?>">
               <button type="button" onclick="doPay()" id="btn-paye" class="btn btn-pay" >Pagar con Mercado Pago</button>
            </div>
         </div>
      </div>
      @endif
   </div>
   <input type="hidden" name="emp_id" id="empid" value="<?php echo $lender->emp_id;?>">
   <input type="hidden" name="suc_id" id="sucid" value="<?php echo $lender->suc_id;?>">
   <input type="hidden" name="pres_id" id="presid" value="<?php echo $lender->tmsp_id;?>">
   <input type="hidden" id="url_business" name="url_business" value="<?php echo $frame->url;?>">

<input type="hidden" id="url_lender" name="url_lender" value="<?php echo $lender->url;?>">

   <input type="hidden" name="tu_fec" id="date" value="<?php echo $get_shift->tu_fec;?>">
   <input type="hidden" name="tu_hora"  id="times" value="<?php echo $get_shift->tu_hora;?>">
   <input type="hidden" name="vadcod" id="vadcod" value="<?php echo substr($get_business->em_valcod,0,4);?>">
</form>
<script type="text/javascript">
   function formValidation() {
        @foreach($inputs_add as $rs)
        @if($rs->mi_field == '1' && $rs->mi_ob == '1')
        if ($("#date_1_dd").val() == "") {
            launch_toast("Ingresá el día");
            $("#date_1_dd").focus();
            return false;
        }
        if ($("#date_1_mm").val() == "") {
            launch_toast("Ingresá el mes");
             $("#date_1_mm").focus();
            return false;
        }
        if ($("#date_1").val() == "") {
            launch_toast("Ingresá el año");
             $("#date_1").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '2' && $rs->mi_ob == '1')
        if ($("#f_2").val() == "") {
            launch_toast("Seleccioná el tipo de documento");
             $("#f_2").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '3' && $rs->mi_ob == '1')
        if ($("#f_3").val() == "") {
            launch_toast("Ingresá la obra social");
             $("#f_3").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '4' && $rs->mi_ob == '1')
        if ($("#f_4").val() == "") {
            launch_toast("Ingresá el plan");
             $("#f_4").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '5' && $rs->mi_ob == '1')
        if ($("#f_5").val() == "") {
            launch_toast("Ingresá el número de documento");
             $("#f_5").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '6' && $rs->mi_ob == '1')
        if ($("#f_6").val() == "") {
            launch_toast("Ingresá el número de afiliado");
             $("#f_6").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '7' && $rs->mi_ob == '1')
        if ($("#f_7").val() == "") {
            launch_toast("Ingresá el número de teléfono");
             $("#f_7").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '8' && $rs->mi_ob == '1')
        if ($("#f_8").val() == "") {
            launch_toast("Ingresá el número de celular");
             $("#f_8").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '9' && $rs->mi_ob == '1')
        if ($("#f_9").val() == "") {
            launch_toast("Ingresá <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>");
             $("#f_9").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '10' && $rs->mi_ob == '1')
        if ($("#f_10").val() == "") {
            launch_toast("Ingresá <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>");
             $("#f_10").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '11' && $rs->mi_ob == '1')
        if ($("#f_11").val() == "") {
            launch_toast("Ingresá <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>");
             $("#f_11").focus();
            return false;
        }
        @endif
        @if($rs->mi_field == '12' && $rs->mi_ob == '1')
        if ($("#f_12").val() == "") {
            launch_toast("Ingresá <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>");
             $("#f_12").focus();
            return false;
        }
        @endif

         @if($rs->mi_field == '13' && $rs->mi_ob == '1')
        if ($("#f_13").val() == "") {
            launch_toast("Ingresá <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>");
             $("#f_13").focus();
            return false;
        }
        @endif

        @if($rs->mi_field == '14' && $rs->mi_ob == '1')
        if ($("#f_14").val() == "") {
            launch_toast("Ingresá <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>");
             $("#f_14").focus();
            return false;
        }
        @endif

        @if($rs->mi_field == '15' && $rs->mi_ob == '1')
        if ($("#f_15").val() == "") {
            launch_toast("Ingresá <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>");
             $("#f_15").focus();
            return false;
        }
        @endif

        @if($rs->mi_field == '16' && $rs->mi_ob == '1')
        if ($("#f_16").val() == "") {
            launch_toast("Ingresá <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>");
             $("#f_16").focus();
            return false;
        }
        @endif
        @endforeach
        return true;
    }
   @foreach($inputs_add as $rs)
   @if($rs->mi_field == '1')
    document.getElementById("date_1_mm").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890".indexOf(chr) < 0)
        return false;
   };
   
   document.getElementById("date_1_dd").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890".indexOf(chr) < 0)
        return false;
   };
   
   document.getElementById("date_1").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890".indexOf(chr) < 0)
        return false;
   };
   @endif
   @if($rs->mi_field==7)
   document.getElementById("f_7").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890+-".indexOf(chr) < 0)
        return false;
   };
   
   @endif
   @if($rs->mi_field==8)
   document.getElementById("f_8").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if ("1234567890+-".indexOf(chr) < 0)
        return false;
   };
   
   @endif
   @endforeach
   @if($lender->tmsp_pagoA=='ALTA'  && $amount!=0 && date("Y-m-d")<date("Y-m-d",strtotime($get_business->expired_mp)) && $get_business->access_token !="" )
   Mercadopago.setPublishableKey("<?php echo $get_business->public_key?>");
    //Mercadopago.setPublishableKey("TEST-99d244b3-6eff-4806-a51d-94917cbf8c7f");
   @endif

   function ValidarFormulario() {
    if (formValidation()) {
        var route = $("#url").val() + '/actualizar_turno';
        $("#btn-create").prop('disabled', true);
        $("#btn-create").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: route,
            type: 'POST',
            dataType: 'json',
            data: $("#regmdtur").serialize(),
            success: function(data) {
               
                if (data.msg == 'false') {
                    location.reload();
                } else {
                   window.location = $("#url").val() + '/'+$("#url_business").val()+"/"+$("#url_lender").val()+"/turno/"+$("#shift").val();
                }
            },
            error: function(msj) {
                $("#btn-create").prop('disabled', false);
                $("#btn-create").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Datos');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}

</script>

@if($lender->tmsp_pagoA=='ALTA'  &&  date("Y-m-d")<date("Y-m-d",strtotime($get_business->expired_mp)) && $get_business->access_token !="" )
<?php echo Html::script('frame/js/mercadopago.js?v='.time())?>
@endif
@stop
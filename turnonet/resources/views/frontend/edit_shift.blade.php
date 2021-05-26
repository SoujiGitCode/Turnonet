@extends('layouts.template_frontend_inside')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-8 col-sm-8 col-xs-12">
         <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
         <h4 class="title-date-2">Actualizar cliente</h4>
         <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px">
            <div class="panel">
               <div class="panel-heading">
                  Actualizá la información de tu cliente para el turno <?php echo $shift->tu_code;?> aquí:
               </div>
               <div class="panel-body">
                  <form  method="POST" id="form">
                     <input type="hidden" id="id" name="id" value="<?php echo $user->id;?>">
                     <input type="hidden" id="shift" name="shift" value="<?php echo $shift->tu_id;?>">
                     <input type="hidden" id="code" name="code" value="<?php echo $shift->tu_code;?>">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label>Nombre *</label>
                              <input type="text" id="name" name="name" class="form-control"
                                 placeholder="Ingresá el nombre" value="<?php echo $user->name;?>">
                           </div>
                           <div class="form-group">
                              <label>Correo electrónico *</label>
                              <input type="text" id="email" name="email" class="form-control"
                                 placeholder="Ingresá el correo electrónico" value="<?php echo $user->email;?>">
                           </div>
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
                              <textarea name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>"><?php echo $data->usm_gen1;?></textarea>
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
                              <textarea name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>"><?php echo $data->usm_gen2;?></textarea>
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
                              <textarea name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>"><?php echo $data->usm_gen3;?></textarea>
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
                              <textarea name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>"><?php echo $data->usm_gen4;?></textarea>
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
                              <textarea name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>"><?php echo $data->usm_gen5;?></textarea>
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
                              <textarea name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>"><?php echo $data->usm_gen6;?></textarea>
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
                              <textarea name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>"><?php echo $data->usm_gen7;?></textarea>
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
                              <textarea name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>"><?php echo $data->usm_gen8;?></textarea>
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
                           @endif
                           <div class="form-group">
                              <p>Campos obligatorios (*)</p>
                           </div>
                           <div class="form-group">
                              <button type="button" onclick="update_data_cliente_turno()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                           </div>
                  </form>
                  </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12 n-ppding">
         @include('includes.business')
      </div>
   </div>
</div>
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
</script>
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
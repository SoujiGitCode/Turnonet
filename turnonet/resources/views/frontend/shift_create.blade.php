@extends('layouts.template_frontend_inside')
@section('content')
<form id="form">
   <div class="container" ng-app="myApp" ng-controller="post">
      <div class="row">
         <div class="col-md-8 col-sm-8 col-xs-12">
            <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
            <h4 class="title-date-2">AGENDA</h4>
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px">
               <div class="panel">
                  <div class="panel-heading">
                     Nuevo Turno
                  </div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                           <div class="panel-pres">
                              <div class="col-xs-1 no-padding-desktop">
                                 <?php
                                    $image=url('/').'/img/empty.jpg';
                                    
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
                                 <img src="<?php echo $image;?>" class="img-pres">
                              </div>
                              <div class="col-xs-11 no-padding-desktop">
                                 <div class="tit" style="margin-bottom: 0vw;">
                                    <?php echo mb_strtoupper($lender->tmsp_pnom);?>     
                                 </div>
                                 <?php $address=DB::table('tu_emps_suc')->where('suc_id',$lender->suc_id)->first(); ?>
                                 @if(isset($address)!=0)
                                 <div class="subtit "> <?php echo $address->suc_dom;?> <?php echo $address->suc_domnum;?> <?php echo $address->suc_dompiso;?> </div>
                                 @endif
                              </div>
                           </div>
                           <div class="form-group-1 ss-i">
                              <p class="form-desc"> Seleccioná un cliente  existente o registrá uno nuevo haciendo <strong style="color: #f15424; cursor: pointer;" onclick="window.location='<?php echo url('directorio/nuevo/'.$get_business->em_id);?>'">click aquí</strong> </p>
                           </div>
                           <div class="name-search ss-i">
                              <div class="input-group">
                                 <span class="input-group-addon" style="border-top-left-radius: 3rem;
                                    border-bottom-left-radius: 3rem;" onclick="filtro()"><i class="fa fa-search"></i></span>
                                 <input type="text" id="search" onkeypress="enter_filtro(event)" onchange="filtro()"  type="text" placeholder="Ingresá el nombre o correo del cliente" class="form-control" style="border-top-right-radius: 3rem; border-bottom-right-radius: 3rem" />
                              </div>
                              <ul class="search-items" style="display: none;">
                                 <li ng-repeat="row in list | limitTo:5" ng-click="asignarValor(row)">@{{row.name}} <span class="turr">@{{row.shift}}</span>
                                 </li>
                                 <li ng-show="totalItems == 0">No hay resultados para mostrar</li>
                              </ul>
                           </div>
                           <div class="form-group btop"  id="panel-user" style="display: none;">
                              <label class="label-1">DATOS DEL CLIENTE:</label>
                              <div id="data-user"></div>
                              <label class="edit-profile-1" onclick="removeUser()" title="Eliminar cliente"><i class="fa fa-times"></i></label>
                           </div>
                           <div id="d-add" style="clear: both; width: 100%; display: none;">
                              @if(count($inputs_add)!=0)
                              <div class="form-group btop"> 
                                 <label class="label-1">DATOS ADICIONALES DEL CLIENTE PARA EL TURNO:</label>
                              </div>
                              @endif
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
                                 <label>Tipo de documento </label>
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
                                 <label>Obra social </label>
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
                                 <label>Plan Obra Social </label>
                                 <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el plan" value="" />
                              </div>
                              @endif
                              @if($rs->mi_field==5)
                              <div class="form-group">
                                 <label>Tipo y Nro. de Documento </label>
                                 <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de documento" value="" />
                              </div>
                              @endif
                              @if($rs->mi_field==6)
                              <div class="form-group">
                                 <label>Nro. de Afiliado Obra Social </label>
                                 <input type="text" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de afiliado" value=""/>
                              </div>
                              @endif
                              @if($rs->mi_field==7)
                              <div class="form-group">
                                 <label>Teléfono </label>
                                 <input type="tel" name="f_<?php echo $rs->mi_field;?>" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número de teléfono" value=""/>
                              </div>
                              @endif
                              @if($rs->mi_field==8)
                              <div class="form-group">
                                 <label>Celular </label>
                                 <input type="tel" name="f_<?php echo $rs->mi_field;?>" required pattern="[0-9]+" id="f_<?php echo $rs->mi_field;?>" class="form-control" placeholder="Ingresá el número celular" value="" />
                              </div>
                              @endif
                              @if($rs->mi_field==9)
                              <div class="form-group">
                                 <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> </label>
                              </div>
                              <div class="form-group">
                                 @if($rs->mi_tipofield==1)
                                 <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
                                 @else
                                 <div class="demo-radio" style="clear: both;">
                                    <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI">
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
                                 <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> </label>
                              </div>
                              <div class="form-group">
                                 @if($rs->mi_tipofield==1)
                                 <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
                                 @else
                                 <div class="demo-radio" style="clear: both;">
                                    <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI">
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
                                 <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> </label>
                              </div>
                              <div class="form-group">
                                 @if($rs->mi_tipofield==1)
                                 <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
                                 @else
                                 <div class="demo-radio" style="clear: both;">
                                    <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI">
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
                                 <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> </label>
                              </div>
                              <div class="form-group">
                                 @if($rs->mi_tipofield==1)
                                 <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
                                 @else
                                 <div class="demo-radio" style="clear: both;">
                                    <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI">
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
                                 <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> </label>
                              </div>
                              <div class="form-group">
                                 @if($rs->mi_tipofield==1)
                                 <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
                                 @else
                                 <div class="demo-radio" style="clear: both;">
                                    <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI">
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
                                 <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> </label>
                              </div>
                              <div class="form-group">
                                 @if($rs->mi_tipofield==1)
                                 <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
                                 @else
                                 <div class="demo-radio" style="clear: both;">
                                    <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI">
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
                                 <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> </label>
                              </div>
                              <div class="form-group">
                                 @if($rs->mi_tipofield==1)
                                 <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
                                 @else
                                 <div class="demo-radio" style="clear: both;">
                                    <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI">
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
                                 <label><?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?> </label>
                              </div>
                              <div class="form-group">
                                 @if($rs->mi_tipofield==1)
                                 <input type="text" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>" class="form-control"  placeholder="Ingresá  <?php echo ucfirst(mb_strtolower($rs->mi_gentxt));?>" value=""/>
                                 @else
                                 <div class="demo-radio" style="clear: both;">
                                    <input type="radio" name="f_<? echo $rs->mi_field; ?>" id="f_<? echo $rs->mi_field; ?>_1" value="SI">
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
                           </div>
                           @if(count($services)!=0)
                           @if(count($services)==1)
                           @foreach($services as $rs)
                           <input type="hidden" id="service_select" name="service_select" value="<?php echo $rs->serv_id;?>">
                           @endforeach
                           @else
                           <input type="hidden" id="service_select" name="service_select" value="">
                           <div class="form-group input-serv ">
                              <br>
                              <label class="text-uppercase" style="font-family: 'Montserrat-SemiBold';">Servicio*</label>
                              <select name="service" id="service" class="form-control">
                                 <option value="" >Seleccioná</option>
                                 @foreach($services as $rs)
                                 <option value="<?php echo $rs->serv_id;?>"><?php echo trim($rs->serv_nom);?></option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="form-group input-serv">
                              <ul class="list-opt" id="list-opt"></ul>
                           </div>
                           @endif
                           @endif

                           <div class="form-group">
                            <br>
                             <div class="demoo1">
                              <input type="checkbox" name="aviso" id="aviso" value="1" checked="checked">
                              <label for="aviso"><span></span>Enviar email de aviso al cliente</label>
                            </div>
                            <br>
                          </div>
                           <div class="form-group">
                              <label class="switch-wrap">
                                 <input type="checkbox" id="tu_st" name="tu_st" value="1"  onchange="upSobreturno()">
                                 <div class="switch"></div>
                              </label>
                              <span>Cargar como sobreturno</span>
                           </div>
                           <div id="capa">
                              <div class="col-xs-12 content-spinner" id="prevloader">
                                 <div class="spinner">
                                    <div class="double-bounce1"></div>
                                    <div class="double-bounce2"></div>
                                 </div>
                              </div>
                              <div class="col-xs-12" id="prevcalendario" style="display: none; padding: 0px">
                                 <div class="lodcal">
                                    <div id="s_cal">
                                       <div class="row s_day" style="margin-left:0; margin-right:0  ">
                                          <div class="col-xs-2">
                                             <a ng-click="prevMonth()" ng-show="prevmonth!=month_act || month_act!=month_select"  class="relcal"><img src="https://i.imgur.com/FGybL6Z.png" class="arrow-prev" id="arrowprev"></a>
                                          </div>
                                          <div class="col-xs-8">
                                             <div class="">@{{name_month}}</div>
                                          </div>
                                          <div class="col-xs-2">
                                             <a class="relcal" ng-click="nextMonth()"><img src="https://i.imgur.com/R8865BH.png" class="arrow-next"></a>
                                          </div>
                                       </div>
                                       <div class="s_sd">L</div>
                                       <div class="s_sd">M</div>
                                       <div class="s_sd">M</div>
                                       <div class="s_sd">J</div>
                                       <div class="s_sd">V</div>
                                       <div class="s_sd">S</div>
                                       <div class="s_sd" style="border-right: none!important;">D</div>
                                       <div ng-repeat="row in list_calendar" class="@{{row.class}}">
                                          <div ng-show="row.date!='' && row.active=='1'" class="circlegreen" ng-click="selectDay(row.date)">@{{row.day}}</div>
                                          <div ng-show="row.date!='' && row.active=='0'" class="circlepink">@{{row.day}}</div>
                                       </div>
                                    </div>
                                    </script>                   
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <p>Campos obligatorios (*)</p>
                           </div>
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
      <div id="myModal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <h3 id="title-modal">Horarios</h3>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class=" col-xs-12">
                        <div class="col-md-12  col-sm-12 col-xs-12 content-spinner" id="prevloadertimes">
                           <div class="spinner">
                              <div class="double-bounce1"></div>
                              <div class="double-bounce2"></div>
                           </div>
                        </div>
                        <div class="col-md-12 col-xs-12" style="display: none; padding: 0px" id="prevtimes">
                           <p class="text-date-33 text-center">@{{name_day}}</p>
                           <div class="col-md-12 col-xs-12 text-center" ng-show="totalItems_times==0">
                              <img src="<?php echo url('/');?>/uploads/icons/noresult.png" class="img-not-res">
                              <p class="text-date text-center">No quedan turnos disponibles para la fecha seleccionada.<br><br></p>
                              <div class="row">
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button  class="btn btn-block btn-primary" name="button" type="button" onclick="closeModal()">Seleccioná otra fecha</button>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12 col-xs-12 text-center" style="clear: both; padding: 0px"  ng-show="totalItems_times!=0">
                              <div class="times" ng-repeat="row in list_times">
                                 <div class="hora" >
                                    <a ng-click="selectTime(row.time,row.id)" id="time-@{{row.id}}" title="@{{row.title}}" class="cturno">@{{row.time_format}}</a>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12 col-xs-12" style="clear: both; padding: 0px"  ng-show="totalItems_times!=0">
                              <p class="text-date-33 text-center">Toca para seleccioná un horario</p>
                              <div class="row">
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <button  class="btn btn-block btn-primary" name="button" type="button" onclick="closeModal()">Seleccioná otra fecha</button>
                                    <button class="btn btn-block btn-info" id="btn-create" type="button" disabled name="button" onclick="guardar()">Agendar Turno</button>
                                 </div>
                                 <div class="col-md-3 col-sm-3 col-xs-12">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
   <input type="hidden" name="emp_id" id="empid" value="<?php echo $lender->emp_id;?>">
   <input type="hidden" name="suc_id" id="sucid" value="<?php echo $lender->suc_id;?>">
   <input type="hidden" name="pres_id" id="presid" value="<?php echo $lender->tmsp_id;?>">
   <input type="hidden" name="us_id" id="us_id" value="">
   <input type="hidden" name="vadcod" id="vadcod" value="<?php echo substr($get_business->em_valcod,0,4);?>">
   <input type="hidden" name="tu_fec" id="date">
   <input type="hidden" name="tu_hora"  id="times">
   <input type="hidden" id="month" value="<?php echo date("m");?>">
   <input type="hidden" id="year" value="<?php echo date("Y");?>">
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
</script>
<input type="hidden" id="redirect" value="/agenda/prestadores/<?php echo $get_business->em_id;?>">
<?php echo Html::script('frontend/js/create_shift.js?v='.time())?>
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
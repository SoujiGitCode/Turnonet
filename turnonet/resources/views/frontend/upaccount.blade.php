@extends('layouts.template_frontend_inside')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h2 class="title-section-2">Mi CUENTA</h2>
                <h4 class="title-date-2">Administra la información de tu cuenta</h4>
                <br>
                <ul class="nav nav-pills">
                    <li><a href="<?php echo url('mi-perfil');?>">General</a></li>
                    <li  class="active"><a href="<?php echo url('actualizar-cuenta');?>">Datos adicionales</a></li>
                    <li><a href="<?php echo url('actualizar-clave');?>">Actualizar contraseña</a></li>
                </ul>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <form  method="POST" id="form">
                    <div class="panel">
                        <div class="panel-heading">
                            Actualizá la información de tu cuenta aquí:
                        </div>
                        <div class="panel-body">
                            @if(isset($user)!=0)
                                <div class="form-group">
                                    <label>Email de Recuperación *</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                           placeholder="Ingresá tu email" value="<?php echo $user->ud_emalt;?>">
                                </div>
                                <div class="form-group">
                                    <label>País *</label>
                                        <select name="country" class="form-control" id="country">
                                        <option value="">Seleccioná</option>
                                        @foreach($countries as $rs)
                                            <option value="<?php echo $rs->pa_id;?>" @if($user->ud_pres==$rs->pa_id) selected="selected" @endif >
                                                <?php echo $rs->pa_nom;?>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <?
                                $query2=DB::table('tu_prov')->select('prov_id', 'prov_nom', 'pais_id', 'prov_orden')->where('pais_id','1')->orderBy('prov_nom','asc')->get();
                                ?>
                                <div class="form-group">
                                    <label>Provincia *</label>
                                    <select name="province" class="form-control" id="province">
                                        <option value="">Seleccioná</option>
                                        @foreach($query2 as $item)
                                            <option value={{$item->prov_id}} @if($user->ud_prov==$item->prov_id) selected="selected" @endif>{{$item->prov_nom}}
                                                <?//echo $user->ud_prov.'-'.$item->prov_id;?>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <?
                                $query=DB::table('tu_locbar')->select('loc_id', 'loc_nom', 'prov_id')->where('prov_id',$user->ud_prov)->orderBy('loc_nom','asc')->get();
                                ?>
                                <div class="form-group">
                                    <label>Localidad *</label>
                                    <select name="location" class="form-control" id="location">
                                        <option value="">Seleccioná</option>
                                        @foreach($query as $item)
                                            <option value={{$item->loc_id}} @if($user->ud_locbar==$item->loc_id) selected="selected" @endif>{{$item->loc_nom}}
                                                <?//echo $user->ud_locbar.'-'.$item->loc_id;?>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Dirección *</label>
                                    <input type="text" id="dir" name="dir" class="form-control"
                                           placeholder="Ingresá una dirección" value="<?php echo $user->ud_dire;?>">
                                </div>
                                <div class="form-group">
                                    <label>Código Postal *</label>
                                    <input type="text" id="postalc" name="postalc" class="form-control"
                                           placeholder="Ingresá un código postal" value="<?php echo $user->ud_cp; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Teléfono *</label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                           placeholder="Ingresá un número de teléfono" value="<?php echo $user->ud_tel;?>">
                                </div>
                                <div class="form-group">
                                    <label>Celular *</label>
                                    <input type="text" id="cellphone" name="cellphone" class="form-control"
                                           placeholder="Ingresá un número de celular" value="<?php echo $user->ud_cel; ?>">
                                </div>
                                <?php
                                if($user->ud_fnac=='0000-00-00'){

                                    $day='';
                                    $month='';
                                    $year='';

                                }else{
                                    $date = explode('-',$user->ud_fnac);
                                    $day=$date[2];
                                    $month=$date[1];
                                    $year=$date[0];

                                }
                                ?>
                                <div class="form-group">
                                    <label>Fecha de Nacimiento</label>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                           {!!Form::select('day', ['01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31'],$day,['placeholder' => 'Día','class' => 'form-control','id'=>'day'])!!}
                                       </div>
                                       <div class="col-md-4 col-sm-4 col-xs-4">
                                           {!!Form::select('month', ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'],$month,['placeholder' => 'Mes','class' => 'form-control','id'=>'month'])!!}
                                       </div>
                                       <div class="col-md-4 col-sm-4 col-xs-4"> 
                                           {!!Form::select('year', ['2020'=>'2020','2019'=>'2019','2018'=>'2018','2017'=>'2017','2016'=>'2016','2015'=>'2015', '2014'=>'2014', '2013'=>'2013','2012'=>'2012','2011'=>'2011','2010'=>'2010','2009'=>'2009','2008'=>'2008', '2007'=>'2007','2006'=>'2006','2005'=>'2005','2004'=>'2004','2003'=>'2003','2002'=>'2002','2001'=>'2001','2000'=>'2000', '1999'=>'1999', '1998'=>'1998', '1997'=>'1997', '1996'=>'1996', '1995'=>'1995', '1994'=>'1994','1993'=>'1993', '1992'=>'1992', '1991'=>'1991', '1990'=>'1990', '1989'=>'1989', '1988'=>'1988', '1987'=>'1987','1986'=>'1986', '1985'=>'1985', '1984'=>'1984', '1983'=>'1983', '1982'=>'1982', '1981'=>'1981', '1980'=>'1980','1979'=>'1979', '1978'=>'1978', '1977'=>'1977', '1976'=>'1976', '1975'=>'1975', '1974'=>'1974', '1973'=>'1973','1972'=>'1972', '1971'=>'1971', '1970'=>'1970', '1969'=>'1969', '1968'=>'1968', '1967'=>'1967', '1966'=>'1966','1965'=>'1965', '1964'=>'1964', '1963'=>'1963', '1962'=>'1962', '1961'=>'1961', '1960'=>'1960', '1959'=>'1959','1958'=>'1958', '1957'=>'1957', '1956'=>'1956', '1955'=>'1955', '1954'=>'1954', '1953'=>'1953', '1952'=>'1952','1951'=>'1951', '1950'=>'1950','1949'=>'1949','1948'=>'1948', '1947'=>'1947', '1946'=>'1946', '1945'=>'1945', '1944'=>'1944', '1943'=>'1943', '1942'=>'1942','1941'=>'1941', '1940'=>'1940','1939'=>'1939','1938'=>'1938', '1937'=>'1937', '1936'=>'1936', '1935'=>'1935', '1934'=>'1934', '1933'=>'1933', '1932'=>'1932','1931'=>'1931', '1930'=>'1930','1929'=>'1929','1928'=>'1928', '1927'=>'1927', '1926'=>'1926', '1925'=>'1925', '1924'=>'1924', '1923'=>'1923', '1922'=>'1922','1921'=>'1921', '1920'=>'1920','1919'=>'1919','1918'=>'1918', '1917'=>'1917', '1916'=>'1916', '1915'=>'1915', '1914'=>'1914', '1913'=>'1913', '1912'=>'1912','1911'=>'1911', '1910'=>'1910'],$year,['placeholder' =>'Año','class' => 'form-control','id'=>'year'])!!}
                                       </div>
                                   </div>
                               </div>
                            @else
                                <div class="form-group">
                                    <label>Email de Recuperación *</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                           placeholder="Ingresá un Email" value="">
                                </div>
                                <div class="form-group">
                                    <label>País *</label>
                                        <select name="country" class="form-control" id="country">
                                        <option value="">Seleccioná</option>
                                        @foreach($countries as $rs)
                                            <option value="<?php echo $rs->pa_id;?>">
                                                <?php echo $rs->pa_nom;?>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <?
                                $query2=DB::table('tu_prov')->select('prov_id', 'prov_nom', 'pais_id', 'prov_orden')->where('pais_id','1')->orderBy('prov_nom','asc')->get();
                                ?>
                                <div class="form-group">
                                    <label>Provincia *</label>
                                    <select name="province" class="form-control" id="province">
                                        <option value="">Seleccioná</option>
                                        @foreach($query2 as $item)
                                            <option value={{$item->prov_id}}>{{$item->prov_nom}}
                                           
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <?
                                $query=DB::table('tu_locbar')->select('loc_id', 'loc_nom', 'prov_id')->orderBy('loc_nom','asc')->get();
                                ?>
                                <div class="form-group">
                                    <label>Localidad *</label>
                                    <select name="location" class="form-control" id="location">
                                        <option value="">Seleccioná</option>
                                        @foreach($query as $item)
                                            <option value={{$item->loc_id}}>{{$item->loc_nom}}
                                           
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Dirección *</label>
                                    <input type="text" id="dir" name="dir" class="form-control"
                                           placeholder="Ingresá una dirección" value="">
                                </div>
                                <div class="form-group">
                                    <label>Código Postal *</label>
                                    <input type="text" id="postalc" name="postalc" class="form-control"
                                           placeholder="Ingresá un código postal" value="">
                                </div>
                                <div class="form-group">
                                    <label>Teléfono *</label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                           placeholder="Ingresá un número de teléfono" value="">
                                </div>
                                <div class="form-group">
                                    <label>Celular *</label>
                                    <input type="text" id="cellphone" name="cellphone" class="form-control"
                                           placeholder="Ingresá un número de celular" value="">
                                </div>
                                <div class="form-group">
                                    <label>Fecha de Nacimiento</label>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                           {!!Form::select('day', ['01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31'],null,['placeholder' => 'Día','class' => 'form-control','id'=>'day'])!!}
                                       </div>
                                       <div class="col-md-4 col-sm-4 col-xs-4">
                                           {!!Form::select('month', ['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'],null,['placeholder' => 'Mes','class' => 'form-control','id'=>'month'])!!}
                                       </div>
                                       <div class="col-md-4 col-sm-4 col-xs-4"> 
                                           {!!Form::select('year', ['2020'=>'2020','2019'=>'2019','2018'=>'2018','2017'=>'2017','2016'=>'2016','2015'=>'2015', '2014'=>'2014', '2013'=>'2013','2012'=>'2012','2011'=>'2011','2010'=>'2010','2009'=>'2009','2008'=>'2008', '2007'=>'2007','2006'=>'2006','2005'=>'2005','2004'=>'2004','2003'=>'2003','2002'=>'2002','2001'=>'2001','2000'=>'2000', '1999'=>'1999', '1998'=>'1998', '1997'=>'1997', '1996'=>'1996', '1995'=>'1995', '1994'=>'1994','1993'=>'1993', '1992'=>'1992', '1991'=>'1991', '1990'=>'1990', '1989'=>'1989', '1988'=>'1988', '1987'=>'1987','1986'=>'1986', '1985'=>'1985', '1984'=>'1984', '1983'=>'1983', '1982'=>'1982', '1981'=>'1981', '1980'=>'1980','1979'=>'1979', '1978'=>'1978', '1977'=>'1977', '1976'=>'1976', '1975'=>'1975', '1974'=>'1974', '1973'=>'1973','1972'=>'1972', '1971'=>'1971', '1970'=>'1970', '1969'=>'1969', '1968'=>'1968', '1967'=>'1967', '1966'=>'1966','1965'=>'1965', '1964'=>'1964', '1963'=>'1963', '1962'=>'1962', '1961'=>'1961', '1960'=>'1960', '1959'=>'1959','1958'=>'1958', '1957'=>'1957', '1956'=>'1956', '1955'=>'1955', '1954'=>'1954', '1953'=>'1953', '1952'=>'1952','1951'=>'1951', '1950'=>'1950','1949'=>'1949','1948'=>'1948', '1947'=>'1947', '1946'=>'1946', '1945'=>'1945', '1944'=>'1944', '1943'=>'1943', '1942'=>'1942','1941'=>'1941', '1940'=>'1940','1939'=>'1939','1938'=>'1938', '1937'=>'1937', '1936'=>'1936', '1935'=>'1935', '1934'=>'1934', '1933'=>'1933', '1932'=>'1932','1931'=>'1931', '1930'=>'1930','1929'=>'1929','1928'=>'1928', '1927'=>'1927', '1926'=>'1926', '1925'=>'1925', '1924'=>'1924', '1923'=>'1923', '1922'=>'1922','1921'=>'1921', '1920'=>'1920','1919'=>'1919','1918'=>'1918', '1917'=>'1917', '1916'=>'1916', '1915'=>'1915', '1914'=>'1914', '1913'=>'1913', '1912'=>'1912','1911'=>'1911', '1910'=>'1910'],null,['placeholder' =>'Año','class' => 'form-control','id'=>'year'])!!}
                                       </div>
                                   </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <p>Campos obligatorios (*)</p>
                            </div>
                            <div class="form-group">
                                <button type="button" onclick="update_data()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        document.getElementById("day").onkeypress = function(e) {
            var chr = String.fromCharCode(e.which);
            if ("1234567890".indexOf(chr) < 0)
                return false;
        };
        document.getElementById("month").onkeypress = function(e) {
            var chr = String.fromCharCode(e.which);
            if ("1234567890".indexOf(chr) < 0)
                return false;
        };
        document.getElementById("year").onkeypress = function(e) {
            var chr = String.fromCharCode(e.which);
            if ("1234567890".indexOf(chr) < 0)
                return false;
        };
    </script>
@stop
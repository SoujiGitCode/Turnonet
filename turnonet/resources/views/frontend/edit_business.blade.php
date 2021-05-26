@extends('layouts.template_frontend_inside')
@section('content')
<form  enctype="multipart/form-data" id="form" method="post">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?></h2>
                <h4 class="title-date-2">Editar Empresa</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <br>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="panel">
                    <div class="panel-heading text-center">
                        Actualizá el logo de tu empresa aquí:
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12"></div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <?php

                                $image=url('/').'/img/empty.jpg';
                                if($get_business->em_pfot!=""){
                                    $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
                                }

                                ?>
                                <div class="form-group text-center">
                                    <div class="img-emp" > <img src="<?php echo $image;?>" alt="<?php echo $get_business->em_nomfan?>" title="<?php echo $get_business->em_nomfan?>" id="avs" >
                                    </div>
                                    <div class="form-group ">
          <input type="file" id="attachment-1" accept="image/x-png,image/gif,image/jpeg" name="em_pfot" class="inputfile inputfile-1">
                              <label for="attachment-1" id="iborrainputfile" title="Subir una imagen desde el equipo"><i class="fa fa-picture-o" aria-hidden="true"></i> SELECCIONAR IMAGEN</label>
                           </div>
                                    <p class="form-desc text-center">Ingresá una imagen de 200x200 px. Del tipo .JPG, .PNG ó .GIF. </p>
                                    <br>

                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12"></div>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-heading">
                        Actualizá la información de tu empresa aquí:
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" value="<?php echo $get_business->em_id;?>">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label>Nombre *</label>
                                <input type="text" id="em_nomfan" name="em_nomfan" class="form-control"
                                       placeholder="Ingresá el nombre" value="<?php echo $get_business->em_nomfan?>">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label>Correo electrónico *</label>
                                <input type="text" id="em_email" name="em_email" class="form-control"
                                       placeholder="Ingresá el correo electrónico" value="<?php echo $get_business->em_email;?>">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label>Teléfono</label>
                                <input type="text" id="em_tel" name="em_tel" class="form-control"
                                       placeholder="Ingresá el teléfono" value="<?php echo $get_business->em_tel;?>">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label>Contacto *</label>
                                <input type="text" id="em_cont" name="em_cont" class="form-control"
                                       placeholder="Ingresá el contacto" value="<?php echo $get_business->em_cont;?>">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label>País *</label>
                                <select name="em_pais" class="form-control" id="em_pais">
                                    @foreach($countries as $rs)
                                    <option value="<?php echo $rs->pa_id;?>" @if($get_business->em_pais==$rs->pa_id) selected="selected" @endif >
                                        <?php echo $rs->pa_nom;?>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label>Domicilio Legal *</label>
                                <input type="text" id="em_domleg" name="em_domleg" class="form-control"
                                       placeholder="Ingresá el domicilio legal" value="<?php echo $get_business->em_domleg?>">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label>Rubro *</label>
                                <select name="em_rub" class="form-control" id="em_rub">
                                    @foreach($em_rub as $rs)
                                    <option value="<?php echo $rs->rub_cat;?>" @if($get_business->em_rub==$rs->rub_cat) selected="selected" @endif >
                                        <?php echo $rs->rub_cat;?>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <label>Sub Rubro </label>
                                <select name="em_rubs" class="form-control" id="em_rubs">
                                    @if($get_business->em_rubs=="")
<option value="">Seleccioná</option>
                                    @else
                                    @foreach($em_rubs as $rs)
                                    <option value="<?php echo $rs->rub_nom;?>" @if($get_business->em_rubs==$rs->rub_nom) selected="selected" @endif ><?php echo $rs->rub_nom;?></option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <p>Campos obligatorios (*)</p>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                                <button type="button" onclick="update_data_business()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                @include('includes.business')
            </div>
        </div>
    </div>
</form>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<?php echo Html::script('frontend/js/settings_busines.js?v='.time())?>
@stop
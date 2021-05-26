@extends('layouts.template_frontend_inside')
@section('content')
<form  enctype="multipart/form-data" id="form" method="post">
   <div class="container">
      <div class="row">
         <div class="col-xs-12">
            <h2 class="title-section-2"><?php echo mb_substr(mb_convert_encoding($get_business->em_nomfan, 'UTF-8', 'UTF-8'),0,40);?>  > <?php echo mb_convert_encoding($get_branch->suc_nom, 'UTF-8', 'UTF-8');?></h2>
            <h4 class="title-date-2">Editar Sucursal</h4>
         </div>
         <div class="col-md-12 col-sm-12 col-xs-12">
            <br>
         </div>
         <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="panel">
               <div class="panel-heading text-center">
                  Actualizá el logo de tu sucursal aquí:
               </div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-4 col-sm-4 col-xs-12"></div>
                     <div class="col-md-4 col-sm-4 col-xs-12">
                        <?php 

                           $image=url('/').'/img/empty.jpg';
                            $image=url('/').'/img/empty.jpg';
                           if(isset($get_business)!=0 && $get_business->em_pfot!=""){
                            $image="https://www.turnonet.com/fotos/empresas/".$get_business->em_pfot;
                          }
                           if($get_branch->suc_pfot!=""){
                            $image="https://www.turnonet.com/fotos/sucursales/".$get_branch->suc_pfot;
                           }
                           
                           ?>
                        <div class="form-group text-center">
                           <div class="img-emp" > <img src="<?php echo $image;?>" alt="<?php echo $get_branch->suc_nom?>" title="<?php echo $get_branch->suc_nom?>" id="avs" >
                           </div>
                           <div class="form-group ">
            <input type="file" id="attachment-1" accept="image/x-png,image/gif,image/jpeg" name="suc_pfot" class="inputfile inputfile-1">
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
                  <input type="hidden" name="id" value="<?php echo $get_branch->suc_id;?>">
                  <div class="row">
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Nombre *</label>
                        <input type="text" id="suc_nom" name="suc_nom" class="form-control"
                           placeholder="Ingresá el nombre" value="<?php echo $get_branch->suc_nom?>">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Correo electrónico *</label>
                        <input type="text" id="suc_email" name="suc_email" class="form-control"
                           placeholder="Ingresá el correo electrónico"  value="<?php echo $get_branch->suc_email?>">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Teléfono</label>
                        <input type="text" id="suc_tel" name="suc_tel" class="form-control"
                           placeholder="Ingresá el teléfono" value="<?php echo $get_branch->suc_tel?>">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Contacto *</label>
                        <input type="text" id="suc_cont" name="suc_cont" class="form-control"
                           placeholder="Ingresá el contacto" value="<?php echo $get_branch->suc_cont?>">
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>País *</label>
                        <select name="suc_pais" class="form-control" id="suc_pais">
                           <option value="">Seleccioná</option>
                           @foreach($countries as $rs)
                        <option value="<?php echo $rs->pa_id;?>" @if($get_branch->suc_pais==$rs->pa_id) selected="selected" @endif >
                        <?php echo $rs->pa_nom;?>
                        </option>
                        @endforeach
                        </select>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Domicilio Legal *</label>
                        <input type="text" id="suc_dom" name="suc_dom" class="form-control"
                           placeholder="Ingresá el domicilio legal" value="<?php echo $get_branch->suc_dom?>" >
                     </div>
                      
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Nro de domicilio </label>
                        <input type="text" id="suc_dompnum" name="suc_dompnum" class="form-control"
                           placeholder="Ingresá el número de domicilio" value="<?php echo $get_branch->suc_dompnum?>" >
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Piso </label>
                        <input type="text" id="suc_dompiso" name="suc_dompiso" class="form-control"
                           placeholder="Ingresá el piso" value="<?php echo $get_branch->suc_dompiso
                           ?>" >
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Letra/número </label>
                        <input type="text" id="suc_doment" name="suc_doment" class="form-control"
                           placeholder="Ingresá la letra/número" value="<?php echo $get_branch->suc_doment?>" >
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Código Postal </label>
                        <input type="text" id="suc_domcp" name="suc_domcp" class="form-control"
                           placeholder="Ingresá el código postal" value="<?php echo $get_branch->suc_domcp?>">
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Horarios de Atención</label>
                        <input type="text" id="suc_hor" name="suc_hor" class="form-control"
                           placeholder="Ingresá los horarios de atención" value="<?php echo $get_branch->suc_hor?>" >
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <p>Campos obligatorios (*)</p>
                     </div>
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <button type="button" onclick="update_data_branch()" class="btn btn-success" id="boton-1">Actualizar datos</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-4 col-xs-12">
            @include('includes.branch')
         </div>
      </div>
   </div>
</form>
<input type="hidden" name="url" id="url" value="<?php echo url('/');?>">
<input type="hidden" id="select_branch" value="<?php echo $get_branch->suc_id;?>">
<?php echo Html::script('frontend/js/settings_branch.js?v='.time())?>
@stop
@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-clipboard"></i> Planes</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Planes</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper">
      {!!Form::model($plan,['route'=>['plans.update',$plan],'method'=>'PUT'])!!}
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s12">
                  <div class="widget z-depth-1">
                     <div class="widget-title">
                        <h3><i class="ti-pencil"></i> Ingresá el contenido de tu plan aquí</h3>
                     </div>
                     <div class="widget-content">
                        
                        <div class="input-field col s4" style="clear: both;">
                           <label class="active">Título<small style="color: #fd2923">*</small></label>                     
                           {!!Form::text('title',null,['class' => 'form-control'])!!}
                        </div>
                        <div class="input-field col s4">
                           <label class="active">Precio<small style="color: #fd2923">*</small></label>                     
                           {!!Form::text('price',null,['class' => 'form-control'])!!}
                        </div>
                        <div class="input-field col s4">
                           <label class="active">Precio USD<small style="color: #fd2923">*</small></label>                     
                           {!!Form::text('price_usd',null,['class' => 'form-control'])!!}
                        </div>
                        <div class="input-field col s4">
                           <label>Configuración de la cuenta</label> 
                           {!!Form::select('item_1', ['Bonificada' => 'Bonificada'], $plan->item_1,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Cant. de Clientes</label> 
                           {!!Form::select('item_2', ['Ilimitado'=>'Ilimitado'], $plan->item_2,['class' => 'browser-default'])!!}
                        </div>


                        <div class="input-field col s4">
                           <label>Cant. Turnos por Mes</label> 
                           {!!Form::select('item_3', ['60'=>'60','300'=>'300','300/Cuenta'=>'300/Cuenta'], $plan->item_3,['class' => 'browser-default'])!!}
                        </div>


                        <div class="input-field col s4">
                           <label>Agendas por cuenta</label> 
                           {!!Form::select('item_4', ['1'=>'1','2'=>'2','5'=>'5'], $plan->item_4,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Turnos Simultáneos</label> 
                           {!!Form::select('item_5', ['Ilimitado'=>'Ilimitado'], $plan->item_5,['class' => 'browser-default'])!!}
                        </div>


                        <div class="input-field col s4">
                           <label>Turnos Online 7x24  </label> 
                           {!!Form::select('item_6', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'], $plan->item_6,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Alertas por SMS (*)</label> 
                           {!!Form::select('item_7',  ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'], $plan->item_7,['class' => 'browser-default'])!!}
                        </div>


                        <div class="input-field col s4">
                           <label>Alertas por Email</label> 
                           {!!Form::select('item_8', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'], $plan->item_8,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Reportes e Informes</label> 
                           {!!Form::select('item_9', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'], $plan->item_9,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Soporte por Email</label> 
                           {!!Form::select('item_10', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'], $plan->item_10,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Soporte Telefónico</label> 
                           {!!Form::select('item_11', ['Si'=>'Si','7 Días'=>'7 Días','No'=>'No'], $plan->item_11,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Sitio web</label> 
                           {!!Form::select('item_12', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'], $plan->item_12,['class' => 'browser-default'])!!}
                        </div>


                        <div class="input-field col s4">
                           <label>Pantalla Responsiva para Usuarios</label> 
                           {!!Form::select('item_13', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'],$plan->item_13,['class' => 'browser-default'])!!}
                        </div>


                        <div class="input-field col s4">
                           <label>App Mobile (para administradores)</label> 
                           {!!Form::select('item_14', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'],$plan->item_14,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Upgrades</label> 
                           {!!Form::select('item_15', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'],$plan->item_15,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Mas turnos por cuenta</label> 
                           {!!Form::select('item_16', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'],$plan->item_16,['class' => 'browser-default'])!!}
                        </div>

                        <div class="input-field col s4">
                           <label>Billetera Digital (Mercado Pago)</label> 
                           {!!Form::select('item_17', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'],$plan->item_17,['class' => 'browser-default'])!!}
                        </div>


                        <div class="input-field col s4">
                           <label>Integración con otras plataformas</label> 
                           {!!Form::select('item_18', ['Si'=>'Si','Consultar'=>'Consultar','No'=>'No'],$plan->item_18,['class' => 'browser-default'])!!}
                        </div>
                        
                
                     <div class="input-field col s12" style="clear: both;">
                        <label style="color: #fd2923!important">Campos requeridos (*)</label><br>
                     </div>
                  </div>
               </div>
               
               <div style="width: 100%; text-align: center;"><br><br>
                  <button  class="btn orange" id="enviar"><i class="fa fa-paper-plane" aria-hidden="true"></i> Guardar</button> 
                  <br><br>
               </div>
            </div>
         </div>
      </div>
   </div>
   {!! Form::close() !!}
</div>
</div>
<!-- Content Area -->
<script type="text/javascript">
   $( "#enviar" ).click(function() {
   $("#enviar").prop('disabled', true);
    $("form").submit();
   });
</script>
@stop
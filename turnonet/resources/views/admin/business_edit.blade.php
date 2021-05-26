@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-briefcase"></i> Empresas</h1>
      </div>
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Empresas</li>
      </ul>
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper">
      <div class="row">
         <div class="masonary">
            <div class="col s12">
               <div class="col s6">
                  <div class="widget z-depth-1">

                     <div class="widget-title">
                        <h3><i class="ti-pencil"></i> Actualizar comisión de pago</h3>
                     </div>
                     <div class="widget-content"> 
                     {!!Form::model($business,['route'=>['business.update',$business],'method'=>'PUT','id'=>'form'])!!}

                     <div class="input-field col s6">
                        
                           <div class="demo">
                              <input type="checkbox" id="demo"  name="em_mp" <?php if($business->em_mp=='1') { ?> checked="checked" <?php } ?> value="1"  onchange="actCommision()">
                              <label for="demo"><span></span>Habilitar Mercado Pago</label>
                           </div>
                        </div>

                        <div class="input-field col s12" @if($business->em_mp!=1) style="display: none"  @endif id="sect-cmm">

                          
                           <label class="active">% Comisión <small style="color: #fd2923">*</small></label>
                           {!!Form::text('commission',$business->commission,['class' => 'form-control','id'=>'commission'])!!}
                        </div>

                     
                     <div class="input-field col s12">
                           <label style="color: #ec0e08!important">Campos requeridos (*)</label><br>
                        </div>
                     <div class="input-field col s12">
                        {!!Form::label(' ')!!}
                        {!!Form::button('Actualizar', ['class' => 'btn waves-effect waves-light orange','onclick'=>'validarEmp()'])!!}
                     </div>
                     {!! Form::close() !!}
                  </div>
               </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   function actCommision(){
        if (!$('#demo:checked').length) {
            $("#sect-cmm").hide();
        }else{
            $("#sect-cmm").show();

        }
    }
    function validarEmp(){
        if($("#commission").val()==""){
            $.growl.warning({
                        title: "<i class='fa fa-exclamation-circle'></i> Atención",
                        message: "Debe ingresar el % de comisión"
                    });
            $("#commission").focus();
            return false;

        }
        else{
            $("#form").submit();
        }
    }
</script>
@stop

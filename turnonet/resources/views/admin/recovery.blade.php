@extends('layouts.template_recovery')
@section('content')


  
{!! Form::open(['id'=>'form','onsubmit'=>'return false']) !!}
<input type="hidden" name="captcha" id="captcha" value="">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="input-field">
   <label for="email">Correo electrónico</label>
   {!!Form::text('email',null,['id'=>'email-recovery','class' => 'form-control'])!!}  
</div>
<div class="uk-margin-medium-top">
   {!!Form::button('Enviar', ['id'=>'btn-recovery','class' => 'btn waves-effect waves-light orange'])!!}
</div>
{!! Form::close() !!}


@stop
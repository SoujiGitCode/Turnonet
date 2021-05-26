@extends('layouts.template')
@section('content')
<div class="content-area">
   <div class="breadcrumb-bar">
      <div class="page-title">
         <h1><i class="ti-email"></i> Buzón</h1>
      </div>
      
      <ul class="admin-breadcrumb">
         <li><a href="{{url('dashboard')}}" title="Escritorio"><i class="ti-desktop"></i> Escritorio</a></li>
         <li>Buzón</li>
      </ul>
   
   </div>
   <!-- Breadcrumb Bar -->
   <div class="widgets-wrapper">
      <div class="row">
         <div class="masonary">
            <div class="widget z-depth-1">
               <div class="widget-title">
                  <h3><i class="ti-pencil"></i> Responder Mensaje</h3>
               </div>
               <div class="widget-content">
                 {!!Form::model($buzon,['route'=>['comments.update',$buzon],'method'=>'PUT'])!!}
                  <div class="row">
                     <div class="col-md-12">
                        <?php $user = DB::table('tu_users')->where('us_id', $buzon->user)->first(); ;?>
                        <input type="hidden" name="email" value="<?php echo $user->us_mail;?>">
                        <input type="hidden" name="name" value="<?php echo $user->us_nom;?>">
                        <input type="hidden" name="id" value="<?php echo $buzon->id;?>">
                        <p>De:  <?php echo $user->us_nom;?> <strong onclick="open.window('mailto:<?php echo $user->us_mail;?>')"><?php echo $user->us_mail;?></strong></p>

                       
                     </div>
                     
                     <div class="col-md-12">
                        <p><?php echo $buzon->message;?></p>
                     </div>
                     <div class="col-md-12"><br></div>
                     <div class="col-md-12">
                        <?php $message='Hola, ' . ucwords(mb_strtolower(mb_convert_encoding($user->us_nom, 'UTF-8', 'UTF-8'))).''; ?>
                        {!! Form::textarea('response',$message,['class'=>"materialize-textarea ",'cols'=>'5','rows'=>'5','id'=>'editor1']) !!}
                     </div>
                     <div class="col-md-12" style="clear: both;">
                        <label style="color: #ec0e08!important"><br>Campos requeridos (*)</label>
                        <br>
                     </div>
                     <div class="col-md-12">
                        <br><br>
                        <a class="btn waves-effect waves-light red btn-w200" href="javascript:history.back()">CANCELAR</a>
                        <button class="btn waves-effect waves-light green btn-w200"><i class="fa fa-paper-plane" aria-hidden="true"></i> RESPONDER</button>
                     </div>
                  </div>
                   {!! Form::close() !!}
               </div>
            </div>
         </div>
         <!-- Mail System -->       
      </div>
   </div>
   <!-- Masonary -->
</div>
</div>
@stop
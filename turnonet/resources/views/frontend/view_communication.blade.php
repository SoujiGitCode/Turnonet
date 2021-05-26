@extends('layouts.template_frontend_inside')
@section('content')

<div class="container">
   <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
         <h2 class="title-section-2"><?php echo $post->title;?></h2>
         <div class="panel">
            <div class="panel-body">
            	 <?php echo $post->content ;?>
            </div>
          </div>
         </div>
     </div>
 </div>

@stop
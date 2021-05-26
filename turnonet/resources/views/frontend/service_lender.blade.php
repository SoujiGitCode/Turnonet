 @if(count($services)!=0)
                           @if(count($services)==1)
                           @foreach($services as $rs)
                           <input type="hidden" id="service_select" name="service_select" value="<?php echo $rs->serv_id;?>">

                           <script type="text/javascript">
                              
                              selectService()
                           </script>
                           @endforeach
                           @else
                           <input type="hidden" id="service_select" name="service_select" value="">
                           <div class="form-group input-serv ">
                              <br>
                              <label class="text-uppercase" style="font-family: 'Montserrat-SemiBold';">Servicio</label>
                              <select name="service" id="service" class="form-control" onchange="selectService()">
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
<input type="hidden" name="tu_hora"  id="times">
                              <input type="hidden" name="emp_id" id="empid" value="<?php echo $lender->emp_id;?>">
   <input type="hidden" name="suc_id" id="sucid" value="<?php echo $lender->suc_id;?>">
   <input type="hidden" name="pres_id" id="presid" value="<?php echo $lender->tmsp_id;?>">
    <input type="hidden" name="us_id" id="us_id" value="">
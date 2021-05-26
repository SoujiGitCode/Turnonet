<div id="myModalC" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content-1">
         <div class="modal-header-1">
            <h3 id="title-modal">Cancelar Turnos</h3>
            <button type="button" class="close" onclick="closeModalCancel()">&times;</button>
         </div>
         <div class="modal-body-1">
            <form id="form-c">
               <input type="hidden" name="all_shift" id="all_shift">
                <div class="row">
                     <div class="col-md-12 col-xs-12 text-center">
                        <br>
                        <p>Cancela quí los turnos seleccionados en tu agenda.  
                        <br>
                     </div>
                  </div>
               <div class="form-group btop" >
                  <label class="label-1">Motivo de cancelación:</label>
                  {!! Form::textarea('message',null,['id'=>'message','placeholder'=>'Ingresá un comentario*','class'=>'form-control','cols'=>'5','rows'=>'5']) !!}
               </div>
               <div class="form-group">
                  <div class="demoo1">
                     <input type="checkbox" name="bloqueo" id="bloqueo" value="1">
                     <label for="bloqueo"><span></span>Mantener el horario bloqueado en el frame de turnos</label>
                  </div>
               </div>
               <div class="form-group">
                  <div class="demoo1">
                     <input type="checkbox" name="aviso" id="aviso-1" value="1" checked="checked">
                     <label for="aviso-1"><span></span>Enviar email de aviso al cliente</label>
                  </div>
               </div>
               <div class="form-group">
                  <p>Campos obligatorios (*)</p>
               </div>
               <div class="form-group">
                  <br>
                  <button type="button" onclick="cancela_turnos()" class="btn btn-success" id="boton-3">Cancelar Turnos</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
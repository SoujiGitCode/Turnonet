function update_settings() {

    if ($("#title").val() == "") {
        launch_toast("Ingresá un título");
        $("#title").focus();
        return false;
    }

    if ($("#url").val() == "") {
        launch_toast("Ingresá un alias");
        $("#url").focus();
        return false;
    }

    else{
 
        swal("Confirmá que querés actualizar la configuración del frame", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $("#boton-1").prop('disabled', true);
                    $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Procesando');
                    var route = $("#url_page").val() + '/update_frame';
                    $.ajax({
                        url: route,
                        type: 'POST',
                        dataType: 'json',
                        data: $("#form").serialize(),
                        success: function(data) {
                            if (data.msg != "error") {
                                $("#boton-1").prop('disabled', false);
                                $("#boton-1").html('Actualizar datos');
                                swal("Sus datos fueron actualizados con éxito", {
                                    allowOutsideClick: false,
                                    closeOnClickOutside: false
                                })
                                    .then((value) => {
                                        location.reload();
                                    });
                            }else{

                                 $("#boton-1").prop('disabled', false);
                                $("#boton-1").html('Actualizar datos');

                                launch_toast('El alias ya se encuentra en uso, por favor intenta con otro');
 $("#url").focus();

                            }
                        },
                        error: function(msj) {
                            $("#boton-1").prop('disabled', false);
                            $("#boton-1").html('Actualizar datos');
                            launch_toast('Ha ocurrido un error por favor intente más tarde');
                        }
                    });
                }
            });
    }
}


function setDesktop(){
$("#framebody").css("width", "100%");
}

function setMovil(){
$("#framebody").css("width", "45%");
}
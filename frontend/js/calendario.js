var URL_WEB = 'https://des.turnonet.com';
var sobreturno=0;

var lenders;
$(document).ready(function() {
	 if ($("#tu_st").is(":checked")) {
        sobreturno = "1";
    } else{ sobreturno = "0" };
    loadMap();
   
});

function loadMap(){
 $('#firstnaveg').addClass('byenaveg');
    $("#capa").load(URL_WEB + "/e/app/cal/" + $("#empid").val() + "/" + $("#sucid").val() + "/" + $("#presid").val() + "/" + $("#month").val() + "/" + $("#year").val() + "/" + $("#vadcod").val()+"/"+sobreturno);
}

function setMonth(id) {
    $("#capa").load(URL_WEB + "/e/app/cal/" + $("#empid").val() + "/" + $("#sucid").val() + "/" + $("#presid").val() + "/" + $("#" + id).data("month") + "/" + $("#vadcod").val()+"/"+sobreturno);
}

function show_error(e) {
    $.growl.error({
        title: "<i class='fa fa-exclamation-circle'></i> Error",
        message: e
    });
}

function selectDay(id) {
    if ($("#us_id").val() == "") {
        show_error('Seleccioná un paciente');
        $("#name").focus();
        return false;
    } else if ($("#service_select").length && $("#service_select").val() == "") {
        show_error('Seleccioná un servicio');
        $("#service").focus();
        return false;
    } 
    if($("#message").length && $("#message").val() == ""){
      show_error('Ingresá un comentario');
      $("#message").focus();
      return false;
  }else {
     if (formValidation()) {
        $("#myModal").modal('show');
        $("#times").val('');
        $("#date").val($("#" + id).data("day"));
        $('#btn-create').attr("disabled", true);
        $("#lodinf").load(URL_WEB + "/e/app/tur/" + $("#empid").val() + "/" + $("#sucid").val() + "/" + $("#presid").val() + "/" + $("#" + id).data("day") + "/" + $("#vadcod").val()+"/"+sobreturno);
        $('#btn-create').attr("disabled", true);
    }
}
}

function closeModal() {
    $('#btn-create').attr("disabled", true);
    $("#myModal").modal('hide');
    $("#times").val('');
}

function selectHour(id) {
    $(".cturno").removeClass('times_active');
    $("#" + id).addClass('times_active');
    $("#times").val($("#" + id).data("time"));
    $('#btn-create').attr("disabled", false);
}

function removeLi(id) {
    services = $("#service_select").val();
    var patron = "-" + id;
    var patron_1 = id;
    services = services.replace(patron, '');
    services = services.replace(patron_1, '');
    $("#service_select").val(services);
    $("#serv-" + id).remove();
    $("#service option[value=" + id + "]").removeAttr('disabled');
}
$(document).ready(function() {
    $('#service').on('change', function() {
        if ($("#service").val() != "") {
            $("#list-opt").append('<li id="serv-' + $("#service").val() + '">' + $('#service option:selected').text() + '<label class="edit-profile" title="Eliminar cliente" onclick="removeLi(' + $("#service").val() + ')"><i class="fa fa-times"></i></label></li>');
            var services;
            if ($("#service_select").val() == "") {
                services = $("#service").val();
            } else {
                services = $("#service_select").val();
                services = services + "-" + $("#service").val();
            }
            $("#service_select").val(services);
            $("#service option[value=" + $("#service").val() + "]").attr('disabled', true);
            $("#service").val('');
        }
    });
});
$(function() {
   
});

function removeUser() {
    $("#panel-user").hide();
    $("#us_id").val('');
      $(".ss-i").show();
       $(".search-items").show();
}



function guardar() {

    if ($("#tu_id").length) {
        var route = $("#url").val() + '/reasing_shift';
    } else {
        var route = $("#url").val() + '/store_shift';
    }
   

     $("#btn-create").prop('disabled', true);
      $("#btn-create").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');


    $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: $("#form").serialize(),
        success: function(data) {

            $("#btn-create").prop('disabled', false);
            $("#btn-create").html('Agendar Turno');

            if (data.msg == 'false') {
                launch_toast('El turno para el día y hora solicitado ya fue ocupado');
            } else {

                $("#myModal").modal('hide');
                    
                if ($("#tu_id").length) {
                    swal("Su turno ha sido reasignado con éxito", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.location = $("#url").val() + "/agenda/turno/" + data.code
                        });
                } else {
                    swal("Su turno ha sido agendado con éxito", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.location = $("#url").val() + "/agenda/turno/" + data.code
                        });
                }
            }
        },
        error: function(msj) {


            $("#btn-create").prop('disabled', false);
            $("#btn-create").html('Agendar Turno');

            launch_toast('Ha ocurrido un error por favor intente más tarde');
        }
    });

}

function upSobreturno() {
    if ($("#tu_st").is(":checked")) {
        sobreturno = "1";
    } else{ sobreturno = "0" };
    loadMap()
    
}

var p_utr = 0;

function goToEmp(url) {
    console.log(url);
    if (p_utr == 0) {

        p_utr = 1;
        window.location = $("#url").val() + '/' + url + '/' + $("#lender").val();
        return false;
    }
}
$('#lender').on('change', function() {
    var loc = window.location;
    var pathName = loc.pathname;
    if (pathName != '/prestadores') {
        var url = '';
        var count = pathName.split("/").length - 1;
        for (var i = 0; i < count; i++) {
            var value = pathName.split('/');
            url += value[i] + '/';
        }
        url += $("#lender").val();
        if (pathName != url) {
            window.location = $("#url").val() + url;
        }
    } else {
        window.location = $("#url").val() + '/prestador/editar/' + $("#lender").val();
    }
});

function update_work() {
    swal("Confirmá que querés actualizar las obras sociales a la que aplica el prestador", {
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
                var route = $("#url").val() + '/update_works_lender';
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

function update_notifications() {
    $("#boton-1").prop('disabled', true);
    $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Procesando');
    var route = $("#url").val() + '/update_notifications_lender';
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
            }
        },
        error: function(msj) {
            $("#boton-1").prop('disabled', false);
            $("#boton-1").html('Actualizar datos');
            launch_toast('Ha ocurrido un error por favor intente más tarde');
        }
    });

}

function baja_lender() {
    swal("Confirmá que querés dar de baja al prestador", {
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
                var route = $("#url").val() + '/update_status_lender';
                $.ajax({
                    url: route,
                    type: 'POST',
                    dataType: 'json',
                    data: $("#form").serialize(),
                    success: function(data) {
                        if (data.msg != "error") {
                            $("#boton-1").prop('disabled', false);
                            $("#boton-1").html('Dar de baja');
                            swal("El prestador ha sido dado de baja", {
                                    allowOutsideClick: false,
                                    closeOnClickOutside: false
                                })
                                .then((value) => {
                                    window.location = $("#url").val() + "/prestadores";
                                });
                        }
                    },
                    error: function(msj) {
                        $("#boton-1").prop('disabled', false);
                        $("#boton-1").html('Dar de baja');
                        launch_toast('Ha ocurrido un error por favor intente más tarde');
                    }
                });
            }
        });
}

function update_settings() {
    if ($("#hours").val() == "00" && $("#minutes").val() == "00") {
        launch_toast("Seleccioná una duración");
        return false;
    } else {
        swal("Confirmá que querés actualizar la configuración del prestador", {
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
                    var route = $("#url").val() + '/update_settings_lender';
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

function update_shedules() {
    /* Sunday*/
    if ($("#reg_init_10").val() != "" && $("#reg_end_10").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_10").val() == "" && $("#reg_end_10").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_10").val() != "" && $("#reg_end_10").val() != "" && $("#reg_init_10").val() > $("#reg_end_10").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_20").val() != "" && $("#reg_end_20").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_20").val() == "" && $("#reg_end_20").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_20").val() != "" && $("#reg_end_20").val() != "" && $("#reg_init_20").val() > $("#reg_end_20").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_20").val() != "" && $("#reg_init_10").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_10").val() != "" && $("#reg_end_10").val() != "" && $("#reg_init_10").val() == $("#reg_end_10").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_20").val() != "" && $("#reg_end_20").val() != "" && $("#reg_init_20").val() == $("#reg_end_20").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_end_20").val() != "" && $("#reg_end_10").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_20").val() != "" && $("#reg_end_10").val() != "" && $("#reg_end_10").val() > $("#reg_init_20").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_init_20").val() != "" && $("#reg_init_10").val() != "" && $("#reg_init_10").val() > $("#reg_init_20").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    } else if ($("#reg_end_20").val() != "" && $("#reg_end_10").val() != "" && $("#reg_end_10").val() > $("#reg_end_20").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día domingo');
        return false;
    }
    /*Monday*/
    else if ($("#reg_init_11").val() != "" && $("#reg_end_11").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_11").val() == "" && $("#reg_end_11").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_11").val() != "" && $("#reg_end_11").val() != "" && $("#reg_init_11").val() > $("#reg_end_11").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_21").val() != "" && $("#reg_end_21").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_21").val() == "" && $("#reg_end_21").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_21").val() != "" && $("#reg_end_21").val() != "" && $("#reg_init_21").val() > $("#reg_end_21").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_21").val() != "" && $("#reg_init_11").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_11").val() != "" && $("#reg_end_11").val() != "" && $("#reg_init_11").val() == $("#reg_end_11").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_21").val() != "" && $("#reg_end_21").val() != "" && $("#reg_init_21").val() == $("#reg_end_21").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_end_21").val() != "" && $("#reg_end_11").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_21").val() != "" && $("#reg_end_11").val() != "" && $("#reg_end_11").val() > $("#reg_init_21").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_init_21").val() != "" && $("#reg_init_11").val() != "" && $("#reg_init_11").val() > $("#reg_init_21").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    } else if ($("#reg_end_21").val() != "" && $("#reg_end_11").val() != "" && $("#reg_end_11").val() > $("#reg_end_21").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día lunes');
        return false;
    }
    /*Tuesday*/
    else if ($("#reg_init_12").val() != "" && $("#reg_end_12").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_12").val() == "" && $("#reg_end_12").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_12").val() != "" && $("#reg_end_12").val() != "" && $("#reg_init_12").val() > $("#reg_end_12").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_22").val() != "" && $("#reg_end_22").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_22").val() == "" && $("#reg_end_22").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_22").val() != "" && $("#reg_end_22").val() != "" && $("#reg_init_22").val() > $("#reg_end_22").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_22").val() != "" && $("#reg_init_12").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_12").val() != "" && $("#reg_end_12").val() != "" && $("#reg_init_12").val() == $("#reg_end_12").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_22").val() != "" && $("#reg_end_22").val() != "" && $("#reg_init_22").val() == $("#reg_end_22").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_end_22").val() != "" && $("#reg_end_12").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_22").val() != "" && $("#reg_end_12").val() != "" && $("#reg_end_12").val() > $("#reg_init_22").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_init_22").val() != "" && $("#reg_init_12").val() != "" && $("#reg_init_12").val() > $("#reg_init_22").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    } else if ($("#reg_end_22").val() != "" && $("#reg_end_12").val() != "" && $("#reg_end_12").val() > $("#reg_end_22").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día martes');
        return false;
    }
    /*Wednesday*/
    else if ($("#reg_init_13").val() != "" && $("#reg_end_13").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_13").val() == "" && $("#reg_end_13").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_13").val() != "" && $("#reg_end_13").val() != "" && $("#reg_init_13").val() > $("#reg_end_13").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_23").val() != "" && $("#reg_end_23").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_23").val() == "" && $("#reg_end_23").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_23").val() != "" && $("#reg_end_23").val() != "" && $("#reg_init_23").val() > $("#reg_end_23").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_23").val() != "" && $("#reg_init_13").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_13").val() != "" && $("#reg_end_13").val() != "" && $("#reg_init_13").val() == $("#reg_end_13").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_23").val() != "" && $("#reg_end_23").val() != "" && $("#reg_init_23").val() == $("#reg_end_23").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_end_23").val() != "" && $("#reg_end_13").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_23").val() != "" && $("#reg_end_13").val() != "" && $("#reg_end_13").val() > $("#reg_init_23").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_init_23").val() != "" && $("#reg_init_13").val() != "" && $("#reg_init_13").val() > $("#reg_init_23").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    } else if ($("#reg_end_23").val() != "" && $("#reg_end_13").val() != "" && $("#reg_end_13").val() > $("#reg_end_23").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día miércoles');
        return false;
    }
    /*thursday*/
    else if ($("#reg_init_14").val() != "" && $("#reg_end_14").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_14").val() == "" && $("#reg_end_14").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_14").val() != "" && $("#reg_end_14").val() != "" && $("#reg_init_14").val() > $("#reg_end_14").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_24").val() != "" && $("#reg_end_24").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_24").val() == "" && $("#reg_end_24").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_24").val() != "" && $("#reg_end_24").val() != "" && $("#reg_init_24").val() > $("#reg_end_24").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_24").val() != "" && $("#reg_init_14").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_14").val() != "" && $("#reg_end_14").val() != "" && $("#reg_init_14").val() == $("#reg_end_14").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_24").val() != "" && $("#reg_end_24").val() != "" && $("#reg_init_24").val() == $("#reg_end_24").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_end_24").val() != "" && $("#reg_end_14").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_24").val() != "" && $("#reg_end_14").val() != "" && $("#reg_end_14").val() > $("#reg_init_24").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_init_24").val() != "" && $("#reg_init_14").val() != "" && $("#reg_init_14").val() > $("#reg_init_24").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    } else if ($("#reg_end_24").val() != "" && $("#reg_end_14").val() != "" && $("#reg_end_14").val() > $("#reg_end_24").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día jueves');
        return false;
    }
    /*Friday*/
    else if ($("#reg_init_15").val() != "" && $("#reg_end_15").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_15").val() == "" && $("#reg_end_15").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_15").val() != "" && $("#reg_end_15").val() != "" && $("#reg_init_15").val() > $("#reg_end_15").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_25").val() != "" && $("#reg_end_25").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_25").val() == "" && $("#reg_end_25").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_25").val() != "" && $("#reg_end_25").val() != "" && $("#reg_init_25").val() > $("#reg_end_25").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_25").val() != "" && $("#reg_init_15").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_15").val() != "" && $("#reg_end_15").val() != "" && $("#reg_init_15").val() == $("#reg_end_15").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_25").val() != "" && $("#reg_end_25").val() != "" && $("#reg_init_25").val() == $("#reg_end_25").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_end_25").val() != "" && $("#reg_end_15").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_25").val() != "" && $("#reg_end_15").val() != "" && $("#reg_end_15").val() > $("#reg_init_25").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_init_25").val() != "" && $("#reg_init_15").val() != "" && $("#reg_init_15").val() > $("#reg_init_25").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    } else if ($("#reg_end_25").val() != "" && $("#reg_end_15").val() != "" && $("#reg_end_15").val() > $("#reg_end_25").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día viernes');
        return false;
    }
    /*Saturday*/
    else if ($("#reg_init_16").val() != "" && $("#reg_end_16").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_16").val() == "" && $("#reg_end_16").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_16").val() != "" && $("#reg_end_16").val() != "" && $("#reg_init_16").val() > $("#reg_end_16").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_26").val() != "" && $("#reg_end_26").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_26").val() == "" && $("#reg_end_26").val() != "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_26").val() != "" && $("#reg_end_26").val() != "" && $("#reg_init_26").val() > $("#reg_end_26").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_26").val() != "" && $("#reg_init_16").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_16").val() != "" && $("#reg_end_16").val() != "" && $("#reg_init_16").val() == $("#reg_end_16").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_26").val() != "" && $("#reg_end_26").val() != "" && $("#reg_init_26").val() == $("#reg_end_26").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_end_26").val() != "" && $("#reg_end_16").val() == "") {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_26").val() != "" && $("#reg_end_16").val() != "" && $("#reg_end_16").val() > $("#reg_init_26").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_init_26").val() != "" && $("#reg_init_16").val() != "" && $("#reg_init_16").val() > $("#reg_init_26").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else if ($("#reg_end_26").val() != "" && $("#reg_end_16").val() != "" && $("#reg_end_16").val() > $("#reg_end_26").val()) {
        launch_toast('Ha ocurrido un error revisá la configuración para el día sábado');
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Procesando');
        var route = $("#url").val() + '/update_shedules_lender';
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
                }
            },
            error: function(msj) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Actualizar datos');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        })
    }
}

function select_input(value) {
    $("#input-2 option[value=" + value + "]").attr('disabled', true);
}
//reinaldo updated required data
function update_required() {
    var r = false;
    var arrayvals = new Array;
    var count = $("select.select1").children("option:selected").text();
    if (count == "--Seleccioná la opción deseada--") {
        count = 's1'
    }
    arrayvals.push(count);
    //alert(count);
    var count2 = $("select.select2").children("option:selected").text();
    if (count2 == '--Seleccioná la opción deseada--') {
        count2 = 's2'
    }
    arrayvals.push(count2);
    //alert(count2);
    var count3 = $("select.select3").children("option:selected").text();
    if (count3 == '--Seleccioná la opción deseada--') {
        count3 = 's3'
    }
    arrayvals.push(count3);
    //alert(count3);
    var count4 = $("select.select4").children("option:selected").text();
    if (count4 == '--Seleccioná la opción deseada--') {
        count4 = 's4'
    }
    arrayvals.push(count4);
    //alert(count4);
    var count5 = $("select.select5").children("option:selected").text();
    if (count5 == '--Seleccioná la opción deseada--') {
        count5 = 's5'
    }
    arrayvals.push(count5);
    //alert(count5);
    var count6 = $("select.select6").children("option:selected").text();
    if (count6 == '--Seleccioná la opción deseada--') {
        count6 = 's6'
    }
    arrayvals.push(count6);
    //alert(count6);
    var count7 = $("select.select6").children("option:selected").text();
    if (count7 == '--Seleccioná la opción deseada--') {
        count7 = 's7'
    }
    arrayvals.push(count7);
    //alert(count7);
    var count8 = document.getElementById('input-8').value;
    if (count8 == '') {
        count8 = 's8'
    } else {
        arrayvals.push(count8)
    }
    //alert(count8);
    var count9 = document.getElementById('input-9').value;
    if (count9 == '') {
        count9 = 's9'
    } else {
        arrayvals.push(count9)
    }
    //alert(count9);
    var count10 = document.getElementById('input-10').value;
    if (count10 == '') {
        count10 = 's10'
    } else {
        arrayvals.push(count10)
    }
    //alert(count10);
    var count11 = document.getElementById('input-11').value;
    if (count8 == '') {
        count11 = 's11'
    } else {
        arrayvals.push(count11)
    }
    //alert(count10);
    var empty = new Array;
    var recipientsArray = arrayvals.sort();
    var reportRecipientsDuplicate = [];
    for (var i = 0; i < recipientsArray.length - 1; i++) {
        if (recipientsArray[i + 1] == recipientsArray[i]) {
            reportRecipientsDuplicate.push(recipientsArray[i]);
        }
    }
    // comparing both arrays using stringify
    if (JSON.stringify(empty) != JSON.stringify(reportRecipientsDuplicate)) {
        launch_toast('Datos duplicados ' + reportRecipientsDuplicate);
        r = true;
    }
    console.log(reportRecipientsDuplicate);
    //console.log(r);
    if (r == false) {} else {
        event.preventDefault();
    }
}
//fin reinaldo update required data
function setShedules() {
    if ($("#check_up").is(":checked")) {
        $('#boton-1').attr("disabled", false);
        $('.form-date-1').attr("disabled", false);
    } else {
        $('.form-date-1').attr("disabled", true);
        $('#boton-1').attr("disabled", true);
    }
}

$('#attachment').change(function(e) {
    addImage(e);
});
$('#attachment-1').change(function(e) {
    update_data_lender();
});
$('#attachment-2').change(function(e) {
    addImage2(e);
});

function addImage(e) {
    var file = e.target.files[0],
        imageType = /image.*/;
    console.log(document.getElementById("attachment").files[0]);
    if (!file.type.match(imageType))
        return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var result = e.target.result;
        $('#avs').attr("src", result);
    }
    reader.readAsDataURL(file);
}

function addImage2(e) {
    var file = e.target.files[0],
        imageType = /image.*/;
    console.log(document.getElementById("attachment-2").files[0]);
    if (!file.type.match(imageType))
        return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var result = e.target.result;
        $('#avs-2').attr("src", result);
    }
    reader.readAsDataURL(file);
}

$(document).ready(function() {
    $('#business').on('change', function() {
        changeEmpresa();
    });

    $('#branch').on('change', function() {
        changeSucursal();
    });

    changeEmpresa();
});

function changeEmpresa() {

    if ($("#business").val() != "") {
        $('#branch').empty();
        $.get($("#url").val() + "/lists_branch_business/" + $("#business").val() + "", function(res, sta) {
            res.forEach(element => {
                if ($('#select_branch').length != 0 && element.id == $('#select_branch').val()) {
                    $("#branch").append(`<option value=${element.id} selected="selected"> ${element.name} </option>`);
                } else {
                    $("#branch").append(`<option value=${element.id}> ${element.name} </option>`);
                }
            });
            if (res.length <= 0) {
                $("#panel-control").hide();
                $("#branch").append(`<option value=''> No tenés sucursales </option>`);
            } else {
                $("#panel-control").show();
            }
            changeSucursal();
        });
    } else {
        $('#branch').empty();
    }

}

function SetTurnos() {
    if ($("#turnos").is(":checked")) {
        $("#message-can").show();
    } else {
        $("#message-can").hide();
    }
}

function changeSucursal() {

    if ($("#branch").val() != "") {
        $('#lender').empty();
        $.get($("#url").val() + "/lista_branch/" + $("#branch").val() + "", function(res, sta) {
            res.forEach(element => {
                if ($('#select_lender').length != 0 && element.id == $('#select_lender').val()) {
                    $("#lender").append(`<option value=${element.id} selected="selected"> ${element.name} </option>`);
                } else {
                    $("#lender").append(`<option value=${element.id}> ${element.name} </option>`);
                }
            });
            if (res.length <= 0) {
                $("#panel-control").hide();
                $("#lender").append(`<option value=''> No tenés prestadores </option>`);
            } else {
                $("#panel-control").show();
            }
            var loc = window.location;
            var pathName = loc.pathname;
            if (pathName == '/prestadores') {
                angular.element($('[ng-controller="post"]')).scope().rows();
            }
        });
    } else {
        $('#lender').empty();
    }

}

function update_data_lender() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#tmsp_pnom").val() == "") {
        launch_toast("Ingresá un nombre de sucursal");
        $("#tmsp_pnom").focus();
        return false;
    } else if ($("#tmsp_pmail").val() == "") {
        launch_toast("Ingresá un correo electrónico");
        $("#tmsp_pmail").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        a = new FormData(document.getElementById("form"));
        $.ajax({
            url: $("#url").val() + '/update_lender',
            type: "post",
            dataType: "html",
            data: a,
            cache: !1,
            contentType: !1,
            processData: !1
        }).done(function(e) {
            var obj = JSON.parse(e);
            if (obj.msg != "error") {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Guardar');
                swal("Sus datos fueron actualizados con éxito", {
                        allowOutsideClick: false,
                        closeOnClickOutside: false
                    })
                    .then((value) => {
                        location.reload();
                    });
            }
        }).fail(function(jqXHR, textStatus) {
            $("#boton-1").prop('disabled', false);
            $("#boton-1").html('Guardar');
            launch_toast('Ha ocurrido un error por favor intente más tarde');
        });
    }
}

function send_reports() {
    if ($("#day").val() == "") {
        launch_toast("Seleccioná una fecha");
        $("#day").focus();
        return false;
    } else if ($("#tmsp_pmail").val() == "") {
        launch_toast("Ingresá un correo electrónico");
        $("#tmsp_pmail").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        a = new FormData(document.getElementById("form"));
        $.ajax({
            url: $("#url").val() + '/send_reports',
            type: "post",
            dataType: "html",
            data: a,
            cache: !1,
            contentType: !1,
            processData: !1
        }).done(function(e) {

            if (e.msg != "error") {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Enviar Por Correo');
                swal("Su reporte ha sido generado con éxito, sera enviado a su bandeja de correo en los próximos minutos", {
                        allowOutsideClick: false,
                        closeOnClickOutside: false
                    })
                    .then((value) => {
                        location.reload();
                    });
            } else {
                launch_toast('No hay turnos solicitados para esta fecha');
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Enviar Por Correo');
            }
        }).fail(function(jqXHR, textStatus) {
            $("#boton-1").prop('disabled', false);
            $("#boton-1").html('Enviar Por Correo');
            launch_toast('Ha ocurrido un error por favor intente más tarde');
        });
    }
}

function download_report() {

     if ($("#day").val() == "") {
        launch_toast("Seleccioná una fecha");
        $("#day").focus();
        return false;
    } else if ($("#tmsp_pmail").val() == "") {
        launch_toast("Ingresá un correo electrónico");
        $("#tmsp_pmail").focus();
        return false;
    } else {

        $.ajax({
            url: $("#url").val() + '/verify_reports',
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                if (data.msg != "error") {
                    var type = $('input:radio[name=rep_type]:checked').val();
                    window.open($("#url").val() + "/pdf/reporte/3/" + type + "/" + $("#pres_id").val() + "/" + $("#emp_id").val() + "/" + $("#day").val());
                } else {
                    launch_toast('No hay turnos solicitados para esta fecha');

                }
            },
            error: function(msj) {
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });

    }
}

function removeZoom() {
    swal("Confirmá que querés desvincular tu cuenta de zoom en nuestra plataforma", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: $("#url").val() + '/remove_zoom',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: $("#lender").val()
                    },
                    success: function(data) {
                        swal("Su cuenta ha sido desvinculada  con éxito")
                            .then((value) => {
                                location.reload();
                            });
                    },
                    error: function(msj) {
                        launch_toast('Ha ocurrido un error por favor intente más tarde');
                    }
                });
            }
        });
}

function createZoom() {
    swal("Confirmá que querés vincular tu cuenta de zoom en nuestra plataforma", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {

                window.location = $("#url").val()+"/associate-zoom/" + $("#lender").val();
            }
        });
}
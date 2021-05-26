
     

var p_utr = 0;

function goToEmp(url) {
    if (p_utr == 0) {
        p_utr = 1;
        window.location = $("#url").val() + '/' + url + '/' + $("#business").val();
        return false;
    }
}
$('#business').on('change', function() {
    var loc = window.location;
    var pathName = loc.pathname;

    console.log(loc.pathname);
    if (pathName != '/empresas') {
        var url = '';
        var count = pathName.split("/").length - 1;
        for (var i = 0; i < count; i++) {
            var value = pathName.split('/');
            url += value[i] + '/';
        }
        url += $("#business").val();
        if (pathName != url) {
            if ($("#redirect").length != 0) {

                window.location = $("#url").val() + $("#redirect").val();

            } else {
                window.location = $("#url").val() + url;
            }
        }
    }
});

function update_notifications() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#email").val() != "" && !expr.test($("#email").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#email").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Procesando');
        var route = $("#url").val() + '/update_notifications_business';
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
}

function baja_business() {
    swal("Confirmá que querés dar de baja a la empresa", {
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
                var route = $("#url").val() + '/update_status_business';
                $.ajax({
                    url: route,
                    type: 'POST',
                    dataType: 'json',
                    data: $("#form").serialize(),
                    success: function(data) {
                        if (data.msg != "error") {
                            $("#boton-1").prop('disabled', false);
                            $("#boton-1").html('Dar de baja');
                            swal("Sus empresa ha sido dado de baja", {
                                    allowOutsideClick: false,
                                    closeOnClickOutside: false
                                })
                                .then((value) => {
                                    window.location = $("#url").val() + "/empresas";
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
        swal("Confirmá que querés actualizar la configuración de la empresa", {
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
                    var route = $("#url").val() + '/update_settings_business';
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

function update_reports() {
    swal("Confirmá que querés actualizar la configuración de reportes la empresa", {
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
                var route = $("#url").val() + '/update_reports_business';
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

function update_work() {
    swal("Confirmá que querés actualizar las obras sociales a la que aplica  la empresa", {
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
                var route = $("#url").val() + '/update_works_business';
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

function SetTurnos() {
    if ($("#turnos").is(":checked")) {
        $("#message-can").show();
    } else {
        $("#message-can").hide();
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
        var route = $("#url").val() + '/update_shedules_business';
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

function update_shedules_business() {

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
        var route = $("#url").val() + '/update_shedules_business';
        $.ajax({
            url: route,
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                if (data.msg != "error") {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Actualizar datos');

                    swal("Sus horarios fueron actualizados con éxito", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.location = $("#url").val() + "/empresa/nueva/sucursal/" + $("#business").val();
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

function select_input() {

    if ($("#input-1").val() != '3' && $("#input-2").val() != '3' && $("#input-3").val() != '3' && $("#input-4").val() != '3' && $("#input-5").val() != '3' && $("#input-6").val() != '3' && $("#input-7").val() != '3') {
        $("#sect-social").hide();
    } else {
        $("#sect-social").show();
    }
}

//
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
    if (count11 == '') {
        count11 = 's11'
    } else {
        arrayvals.push(count11)
    }
    var count12 = document.getElementById('input-12').value;
    if (count12 == '') {
        count12 = 's12'
    } else {
        arrayvals.push(count12)
    }

    var count13 = document.getElementById('input-13').value;
    if (count13 == '') {
        count13 = 's13'
    } else {
        arrayvals.push(count13)
    }

    var count14 = document.getElementById('input-14').value;
    if (count14 == '') {
        count14 = 's14'
    } else {
        arrayvals.push(count14)
    }

    var count15 = document.getElementById('input-15').value;
    if (count15 == '') {
        count15 = 's15'
    } else {
        arrayvals.push(count15)
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

    if (r == false) {

        var route = $("#url").val() + '/update_required_business';
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

    } else {

        return false;
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

function updateMp() {
    swal("Confirmá que querés renovar tus credenciales de mercado pago en nuestra plataforma", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    url: $("#url").val() + '/renew-mercado-pago',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: $("#business").val()
                    },
                    success: function(data) {
                        if (data.response == "error") {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Error",
                                message: data.msg
                            });
                        } else {
                            swal("Sus credenciales de mercado pago han sido renovadas con con éxito")
                                .then((value) => {
                                    location.reload();
                                });
                        }
                    },
                    error: function(msj) {
                        launch_toast('Ha ocurrido un error por favor intente más tarde');
                    }
                });

            }
        });
}

function removeMp() {
    swal("Confirmá que querés desvincular tu cuenta de mercado pago en nuestra plataforma", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: $("#url").val() + '/remove_mercado_pago',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: $("#business").val()
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

function createMp() {
    swal("Confirmá que querés vincular tu cuenta de mercado pago en nuestra plataforma", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {

                window.location = "https://www.turnonet.com/empresa/associate/" + $("#business").val();
            }
        });
}

function update_data_business() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#em_nomfan").val() == "") {
        launch_toast("Ingresá un nombre de empresa");
        $("#em_nomfan").focus();
        return false;
    } else if ($("#em_email").val() == "") {
        launch_toast("Ingresá un correo electrónico");
        $("#em_email").focus();
        return false;
    } else if (!expr.test($("#em_email").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#em_email").focus();
        return false;
    } else if ($("#em_cont").val() == "") {
        launch_toast("Ingresá un contacto");
        $("#em_cont").focus();
        return false;
    } else if ($("#em_domleg").val() == "") {
        launch_toast("Ingresá un domicilio legal");
        $("#em_domleg").focus();
        return false;
    } else if ($("#em_rub").val() == "") {
        launch_toast("Seleccioná un rubro");
        $("#em_rub").focus();
        return false;
    } else if ($("#em_pais").val() == "") {
        launch_toast("Seleccioná un país");
        $("#em_pais").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        a = new FormData(document.getElementById("form"));
        $.ajax({
            url: $("#url").val() + '/update_business',
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
                $("#boton-1").html('Actualizar datos');
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

function created_data_business() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#em_nomfan").val() == "") {
        launch_toast("Ingresá un nombre de empresa");
        $("#em_nomfan").focus();
        return false;
    } else if ($("#em_email").val() == "") {
        launch_toast("Ingresá un correo electrónico");
        $("#em_email").focus();
        return false;
    } else if (!expr.test($("#em_email").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#em_email").focus();
        return false;
    } else if ($("#em_cont").val() == "") {
        launch_toast("Ingresá un contacto");
        $("#em_cont").focus();
        return false;
    } else if ($("#em_domleg").val() == "") {
        launch_toast("Ingresá un domicilio legal");
        $("#em_domleg").focus();
        return false;
    } else if ($("#em_rub").val() == "") {
        launch_toast("Seleccioná un rubro");
        $("#em_rub").focus();
        return false;
    } else if ($("#em_pais").val() == "") {
        launch_toast("Seleccioná un país");
        $("#em_pais").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        a = new FormData(document.getElementById("form"));
        $.ajax({
            url: $("#url").val() + '/create_business',
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
                swal("Su empresa fue registrada con éxito", {
                        allowOutsideClick: false,
                        closeOnClickOutside: false
                    })
                    .then((value) => {
                        window.location = $("#url").val() + "/empresa/nueva/horarios/" + e.id;
                    });
            }
        }).fail(function(jqXHR, textStatus) {
            $("#boton-1").prop('disabled', false);
            $("#boton-1").html('Guardar');
            launch_toast('Ha ocurrido un error por favor intente más tarde');
        });
    }
}

function created_data_branch() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#suc_nom").val() == "") {
        launch_toast("Ingresá un nombre de sucursal");
        $("#suc_nom").focus();
        return false;
    } else if ($("#suc_email").val() == "") {
        launch_toast("Ingresá un correo electrónico");
        $("#suc_email").focus();
        return false;
    } else if (!expr.test($("#suc_email").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#suc_email").focus();
        return false;
    } else if ($("#suc_cont").val() == "") {
        launch_toast("Ingresá un contacto");
        $("#suc_cont").focus();
        return false;
    } else if ($("#suc_dom").val() == "") {
        launch_toast("Ingresá un domicilio legal");
        $("#suc_dom").focus();
        return false;
    } else if ($("#suc_pais").val() == "") {
        launch_toast("Seleccioná un país");
        $("#suc_pais").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        a = new FormData(document.getElementById("form"));
        $.ajax({
            url: $("#url").val() + '/create_branch',
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
                swal("Su sucursal ha sido registrada con éxito", {
                        allowOutsideClick: false,
                        closeOnClickOutside: false
                    })
                    .then((value) => {
                        window.location = $("#url").val() + "/sucursal/configuracion/" + obj.id;
                    });
            }
        }).fail(function(jqXHR, textStatus) {
            $("#boton-1").prop('disabled', false);
            $("#boton-1").html('Guardar');
            launch_toast('Ha ocurrido un error por favor intente más tarde');
        });
    }
}
$(document).ready(function() {
    $('#em_rub').on('change', function() {
        if ($("#em_rub").val() != "") {
            $('#em_rubs').empty();
            $("#em_rubs").append(`<option value=''>Seleccioná</option>`);
            $.get($("#url").val() + "/subcategories/" + $("#em_rub").val() + "", function(res, sta) {
                res.forEach(element => {
                    $("#em_rubs").append(`<option value=${element.rub_nom}> ${element.rub_nom} </option>`);
                });
            });
        } else {
            $('#em_rubs').empty();
        }
    });
});
$('#attachment').change(function(e) {
    addImage(e);
});
$('#attachment-1').change(function(e) {
    update_data_business();
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
            url: $("#url").val() + '/send_reports_business',
            type: "post",
            dataType: 'json',
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
            url: $("#url").val() + '/verify_reports_business',
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                if (data.msg != "error") {
                    var type = $('input:radio[name=rep_type]:checked').val();
                    window.open($("#url").val() + "/pdf/reporte/1/" + type + "/" + $("#emp_id").val() + "/" + $("#emp_id").val() + "/" + $("#day").val());
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

function send_reports_lender() {
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

function download_report_lender() {

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


function CopyToClipboard() {

    var copyText = document.getElementById("myInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
   launch_toast('Url copiada en el portapapeles');

}

function logout() {
    swal("Confirmá que querés cerrar sesión", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                var stateObj = {
                    foo: "/"
                };
                history.pushState(stateObj, "Iniciar Sesion", "/");
                window.location = $("#url").val() + "/cerrar-sesion"
            }
        });
}

function enter_login(e) {
    if (e.keyCode == 13) {
        sendLogin();
    }
}

function sendLogin() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#email").val() == "") {
        launch_toast("Ingresá tu correo electrónico");
        $("#email").focus();
        return false;
    } else if (!expr.test($("#email").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#email").focus();
        return false;
    } else if ($("#password").val() == "") {
        launch_toast("Ingresá tu contraseña");
        $("#password").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Ingresando');
        var route = $("#url").val() + '/store_signin';
        $.ajax({
            url: route,
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                if (data.response == 'true') {
                    history.pushState({
                        foo: "escritorio"
                    }, "Escritorio", "escritorio");
                    window.location = $("#url").val() + "/escritorio";
                }
                if (data.response == 'invalid') {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Ingresar');
                    launch_toast('Tu usuario se encuentra inactivo, para mayaor información comunicate con el administrador');
                }
                if (data.response == 'inactive') {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Ingresar');
                    launch_toast('Tu cuenta aun no ha sido activada, te hemos enviado un correo electrónico para la activación');
                }
                if (data.response == 'false') {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Ingresar');
                    launch_toast('Los datos ingresados son incorrectos');
                }
            },
            error: function(msj) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Ingresar');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}

function sendCode() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#email").val() == "") {
        launch_toast("Ingresá tu correo electrónico");
        $("#email").focus();
        return false;
    } else if (!expr.test($("#email").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#email").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/store_code',
            type: 'POST',
            dataType: 'json',
            data: $('#form').serialize(),
            success: function(data) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Solicitá tu código');
                if (data.response == 'true') {
                    $("#form")[0].reset();
                    swal("Se ha enviado un código de verificación a su correo electrónico", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.location = $("#url").val() + "/cambiar-clave"
                        });
                } else {
                    launch_toast(data.message);
                }
            },
            error: function(msj) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Solicitá tu código');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}

function update_data() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#email").val() == "") {
        launch_toast("Ingresá tu correo electrónico");
        $("#email").focus();
        return false;
    } else if (!expr.test($("#email").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#email").focus();
        return false;
    } else if ($("#country").val() == "") {
        launch_toast("Seleccioná un país");
        $("#country").focus();
        return false;
    } else if ($("#province").val() == "") {
        launch_toast("Seleccioná una provincia");
        $("#province").focus();
        return false;
    } else if ($("#location").val() == "") {
        launch_toast("Seleccioná una localidad");
        $("#location").focus();
        return false;
    } else if ($("#dir").val() == "") {
        launch_toast("Ingresá una dirección");
        $("#dir").focus();
        return false;
    } else if ($("#postalc").val() == "") {
        launch_toast("Ingresá un código postal");
        $("#postalc").focus();
        return false;
    } else if ($("#phone").val() == "") {
        launch_toast("Ingresá un número de teléfono");
        $("#phone").focus();
        return false;
    } else if ($("#cellphone").val() == "") {
        launch_toast("Ingresá un número de celular");
        $("#cellphone").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/store_update',
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

function store_data_cliente() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#name").val() == "") {
        launch_toast("Ingresá el nombre");
        $("#name").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/store_create_cliente',
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                if (data.msg == "error") {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Guardar');
                    launch_toast('Este correo electrónico ya se encuentra registrado');
                } else if (data.msg == "no-email") {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Guardar');
                    launch_toast('Este correo electrónico es incorrecto');
                } else {
                    $("#form")[0].reset();
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Guardar');
                    swal("Los datos del cliente fueron registrados con éxito", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.location = $("#url").val() + "/directorio/empresa/" + $("#business").val();
                        });
                }
            },
            error: function(msj) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Guardar');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}

function update_data_cliente() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#name").val() == "") {
        launch_toast("Ingresá el nombre");
        $("#name").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/store_update_cliente',
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Actualizar datos');
                if (data.msg != "no-email") {
                    swal("Los datos del cliente fueron actualizados con éxito", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.location = $("#url").val() + "/directorio/empresa/" + $("#business").val();
                        });
                } else {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Guardar');
                    launch_toast('Este correo electrónico es incorrecto');
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

function update_data_cliente_turno() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($("#name").val() == "") {
        launch_toast("Ingresá el nombre");
        $("#name").focus();
        return false;
    } else {
        if (formValidation()) {
            $("#boton-1").prop('disabled', true);
            $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
            $.ajax({
                url: $("#url").val() + '/store_update_cliente_turno',
                type: 'POST',
                dataType: 'json',
                data: $("#form").serialize(),
                success: function(data) {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Actualizar datos');
                    if (data.msg != "no-email") {
                        swal("Los datos del cliente fueron actualizados con éxito", {
                                allowOutsideClick: false,
                                closeOnClickOutside: false
                            })
                            .then((value) => {
                                window.location = $("#url").val() + "/directorio/empresa/" + $("#business").val();
                            });
                    } else {
                        $("#boton-1").prop('disabled', false);
                        $("#boton-1").html('Guardar');
                        launch_toast('Este correo electrónico es incorrecto');
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
}

function update_password() {
    if ($("#password").val() == "") {
        launch_toast('Ingresá tu contraseña');
        $("#password").focus();
        return (false);
    } else if ($("#cpasswordr").val() == "") {
        launch_toast('Confirmá tu contraseña');
        $("#cpasswordr").focus();
        return (false);
    } else if ($("#password").val() != $("#cpasswordr").val()) {
        launch_toast('Las contraseñas no coinciden');
        $("#passwordr").focus();
        return (false);
    } else if ($("#password").val().length < 6) {
        launch_toast('Las contraseñas deben tener al menos 6 caracteres');
        $("#password").focus();
        return (false);
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/store_update_password',
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                if (data.msg != "error") {
                    $("#form")[0].reset();
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Actualizar datos');
                    swal("Sus datos fueron actualizados con éxito", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.location = $("#url").val() + "/mi-perfil"
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

function enter_uppassword(e) {
    if (e.keyCode == 13) {
        update_password();
    }
}

function enter_code(e) {
    if (e.keyCode == 13) {
        sendCode();
    }
}

function sendRecovery() {
    if ($("#code").val() == "") {
        launch_toast("Ingresá tu código");
        $("#code").focus();
        return false;
    } else if ($("#password").val() == "") {
        launch_toast("Ingresá tu contraseña");
        $("#password").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/store_reset',
            type: 'POST',
            dataType: 'json',
            data: $('#form').serialize(),
            success: function(data) {
                if (data.response != 'false') {
                    $("#form")[0].reset();
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('Cambiar Contraseña');
                    swal("Su contraseña ha sido cambiada con éxito", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.location = $("#url").val() + "/";
                        });
                } else {
                    launch_toast(data.message);
                }
            },
            error: function(msj) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Cambiar Contraseña');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}

function enter_recovery(e) {
    if (e.keyCode == 13) {
        sendRecovery();
    }
}

function hideNotys() {
    $("#content-noty").hide();
}
$(document).ready(function() {
    $(document).on("click", function(e) {
        if ($("#content-sidenav").length) {
            var el = document.getElementById('content-sidenav');
            if (el.style.display == 'block') {
                if ($(e.target).attr("class") == 'content-sidenav') {
                    $("#content-sidenav").hide();
                }
            }
        }
        if ($("#content-sidenav").length) {
            var el = document.getElementById('content-sidenav');
            if (el.style.display == 'block') {
                if ($(e.target).attr("class") == 'content-sidenav2 content-sidenav hidden-lg hidden-md hidden-sm') {
                    $("#content-sidenav").hide();
                }
            }
        }
        if ($("#content-noty").length) {
            var el = document.getElementById('content-noty');
            if (el.style.display == 'block') {
                if ($(e.target).attr("class") == 'content-noty') {
                    $("#content-noty").hide();
                }
            }
        }
    });
    $("#btn-toogle").click(function() {
        $("#content-noty").hide();
        var el = document.getElementById('content-sidenav');
        el.style.display = (el.style.display == 'none') ? 'block' : 'none';
    });
    $("#btn-bell").click(function() {
        goToNotyActive();
        var el = document.getElementById('content-noty');
        el.style.display = (el.style.display == 'none') ? 'block' : 'none';
    });
    $("#btn-bell-1").click(function() {
        goToNotyActive();
        $("#content-sidenav").hide();
        var el = document.getElementById('content-noty');
        el.style.display = (el.style.display == 'none') ? 'block' : 'none';
    });
});

function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

function saveBugs() {
    if ($("#subject").val() == "") {
        launch_toast("Ingresá tu asunto");
        $("#subject").focus();
        return false;
    } else if ($("#message").val() == "") {
        launch_toast("Ingresá tu comentario");
        $("#message").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/store_support',
            type: 'POST',
            dataType: 'json',
            data: $('#form').serialize(),
            success: function(data) {
                $("#form")[0].reset();
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Enviar mensaje');
                swal("Su mensaje ha sido enviado, en las próximás horas nos comunicaremos con usted.", {
                        allowOutsideClick: false,
                        closeOnClickOutside: false
                    })
                    .then((value) => {
                        location.reload();
                    });
            },
            error: function(msj) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Enviar mensaje');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}
if ($(".datepicker").length) {
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems);
}

function launch_toast(e) {
    $.growl.error({
        title: "<i class='fa fa-exclamation-circle'></i> Error",
        message: e
    });
}
$(function() {
    $("#btn-search").click(function() {
        if ($(".cntent-ass").length) {
            $(".cntent-ass").hide();
        }
        var t = document.getElementById("bar-search");
        t.style.display = "none" == t.style.display ? "block" : "none", $("#content-sidenav").hide();
    });
    $("#btn-close-search").click(function() {
        if ($(".cntent-ass").length) {
            $(".cntent-ass").hide();
        }
        var t = document.getElementById("bar-search");
        t.style.display = "none" == t.style.display ? "block" : "none", $("#mySidenav").css("width", "0%")
    });
    $('#image').change(function(e) {
        addImage(e);
    });

    function addImage(e) {
        var file = e.target.files[0],
            imageType = /image.*/;
        console.log(document.getElementById("image").files[0]);
        if (!file.type.match(imageType))
            return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var result = e.target.result;
            $('#imgSalida').attr("src", result);
            $("#imgSalida").addClass('border-img');
        }
        reader.readAsDataURL(file);
    }
});
$(window).on("load", function() {
    if ($("#mask").length) {
        $("#mask").hide();
    };
});
$(window).scroll(function() {
    50 <= $(window).scrollTop() ? $("#sticky-header").addClass("sticky") : $("#sticky-header").removeClass("sticky");
});
$(window).on("load", function() {
    50 <= $(window).scrollTop() ? $("#sticky-header").addClass("sticky") : $("#sticky-header").removeClass("sticky");
});

function goToNoty(id, url) {
    var route = $("#url").val() + '/delete_noty';
    $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: {
            id: id
        },
        success: function() {
            window.location = url;
        },
        error: function(msj) {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: 'Ha ocurrido un error por favor intentá más tarde'
            });
        }
    });
}

function goToNoty_type() {
    var route = $("#url").val() + '/delete_noty_type';
    $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: {
            type: '1'
        },
        success: function() {
            window.location = $("#url").val() + '/agenda';
        },
        error: function(msj) {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: 'Ha ocurrido un error por favor intentá más tarde'
            });
        }
    });
}

function goToNotyActive() {
    var route = $("#url").val() + '/delete_noty_active';
    $.ajax({
        url: route,
        type: 'POST',
        dataType: 'json',
        data: {
            type: '1'
        },
        success: function() {
            $(".points-noty").hide();
        },
        error: function(msj) {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: 'Ha ocurrido un error por favor intentá más tarde'
            });
        }
    });
}

function cancela_turno() {
    if ($("#message").val() == "") {
        launch_toast("Ingresá tu comentario");
        $("#message").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/update_status_shift',
            type: 'POST',
            dataType: 'json',
            data: $('#form').serialize(),
            success: function(data) {
                $("#form")[0].reset();
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Enviar mensaje');
                swal("Su turno ha sido cancelado con éxito", {
                        allowOutsideClick: false,
                        closeOnClickOutside: false
                    })
                    .then((value) => {
                        window.location = $("#url").val() + "/agenda";
                    });
            },
            error: function(msj) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('Enviar mensaje');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}

function actModalPac(id, code) {
    $("#lodp").html('');
    if (id != "") {
        $.get($("#url").val() + "/get_user_data/" + id + "/" + code, function(res, sta) {
            var response = '<p>' + res.name + '</p>';
            if (res.email != "") {
                response += '<p id="btn-email" onclick="openEmail(this.id)" data-email="' + res.email + '"><i class="fa fa-envelope-o"></i> ' + res.email + '</p>';
            }
            if (res.phone != "") {
                response += '<p id="btn-phone-3"  onclick="openPhone(this.id)" data-phone="' + res.phone + '"><i class="fa fa-phone"></i> ' + res.phone + '</p>';
            }
            if (res.usm_fecnac != "") {
                response += '<p><strong>Fecha de nacimiento:</strong> ' + res.usm_fecnac + '</p>';
            }
            if (res.usm_numdoc != "") {
                response += '<p><strong>Nro. de Documento:</strong> ' + res.usm_numdoc + '</p>';
            }
            if (res.usm_afilnum != "") {
                response += '<p><strong>Nro. de Afiliado Obra Social:</strong> ' + res.usm_afilnum + '</p>';
            }
            if (res.usm_obsoc != "" && res.usm_obsoc != null) {
                response += '<p><strong>Obra social:</strong> ' + res.usm_obsoc + '</p>';
            }
            if (res.usm_obsocpla != "" && res.usm_obsocpla != null) {
                response += '<p><strong>Plan Obra Social:</strong> ' + res.usm_obsocpla + '</p>';
            }
            if (res.usm_tel != "" && res.usm_tel != null) {
                response += '<p id="btn-phone"  onclick="openPhone(this.id)" data-phone="' + res.usm_tel + '"><strong>Teléfono:</strong> ' + res.usm_tel + '</p>';
            }
            if (res.usm_cel != "" && res.usm_cel != null) {
                response += '<p id="btn-phone-1"  onclick="openPhone(this.id)" data-phone="' + res.usm_cel + '"><strong>Celular:</strong> ' + res.usm_cel + '</p>';
            }
            if (res.text_9 != "" && res.text_9 != null && res.usm_gen1 != "" && res.usm_gen1 != null) {
                response += '<p><strong>' + res.text_9 + ':</strong> ' + res.usm_gen1 + '</p>';
            }
            if (res.text_10 != "" && res.text_10 != null && res.usm_gen2 != "" && res.usm_gen2 != null) {
                response += '<p><strong>' + res.text_10 + ':</strong> ' + res.usm_gen2 + '</p>';
            }
            if (res.text_11 != "" && res.text_11 != null && res.usm_gen3 != "" && res.usm_gen3 != null) {
                response += '<p><strong>' + res.text_11 + ':</strong> ' + res.usm_gen3 + '</p>';
            }
            if (res.text_12 != "" && res.text_12 != null && res.usm_gen4 != "" && res.usm_gen4 != null) {
                response += '<p><strong>' + res.text_12 + ':</strong> ' + res.usm_gen4 + '</p>';
            }

            if (res.text_13 != "" && res.text_13 != null && res.usm_gen5 != "" && res.usm_gen5 != null) {
                response += '<p><strong>' + res.text_13 + ':</strong> ' + res.usm_gen5 + '</p>';
            }

            if (res.text_14 != "" && res.text_14 != null && res.usm_gen6 != "" && res.usm_gen6 != null) {
                response += '<p><strong>' + res.text_14 + ':</strong> ' + res.usm_gen6 + '</p>';
            }

            if (res.text_15 != "" && res.text_15 != null && res.usm_gen7 != "" && res.usm_gen7 != null) {
                response += '<p><strong>' + res.text_15 + ':</strong> ' + res.usm_gen7 + '</p>';
            }

            if (res.text_16 != "" && res.text_16 != null && res.usm_gen8 != "" && res.usm_gen8 != null) {
                response += '<p><strong>' + res.text_16 + ':</strong> ' + res.usm_gen8 + '</p>';
            }


            response += '<div class="form-group"><div class="caret-op9" id="btn-act" onclick="openEdit(this.id)" data-code="' + res.code + '" style="width: 30%;" >Actualizar datos</div></div>';
            $("#lodp").html(response);
            $("#myModalp").modal('show');
        });
    }
}

function openEdit(id) {
    window.location = $("#url").val() + "/agenda/editar/turno/" + $("#btn-act").data("code");
}

function openPhone(id) {
    window.open("tel:" + $("#" + id).data("phone"));
    $("#myModalp").modal('hide');
}

function openEmail(id) {
    window.open("tel:" + $("#" + id).data("email"));
    $("#myModalp").modal('hide');
}

function show_loader() {
    var loader = setInterval(activa_loader, 10);
    var width = 0;

    function activa_loader() {
        if (width == 100) {
            $(".bg-preload").hide();
            $(".bg-list").show();
            clearInterval(loader);
        } else {
            width++;
            $(".bg-preload").show();
            $(".bg-list").hide();
        }
    }
}
$("#item-sb").click(function() {
    var t = document.getElementById("submenu");
    t.style.display = "none" == t.style.display ? "block" : "none", $("#angle").toggleClass("fa fa-angle-up fa fa-angle-down")
});
//Disabled button actualizar
$(document).ready(function() {
    $('#boton-2').prop('disabled', true);
    $('input[type="text"]').keyup(function() {
        if ($(this).val() != '') {
            $('#boton-2').prop('disabled', false);
        }
    });
    $('select').change(function() {
        if ($(this).val() != '') {
            $('#boton-2').prop('disabled', false);
        }
    });
});


function desbloquearHorario(id){


       swal("Confirmá que querés desbloquear el horario del turno", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
               
               $.ajax({
                        url: $("#url").val() + '/delete_shedules_cancel',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El horario del turno ha sido desbloqueado"
                            });
                            location.reload();
                        },
                        error: function(msj) {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Error",
                                message: 'Ha ocurrido un error por favor intente más tarde'
                            });
                        }
                    });
            }
        });


     }
//
$('<img>').attr('src', 'https://www.turnonet.com/frontend/icons/dashboard_act.svg');
$('<img>').attr('src', 'https://www.turnonet.com/frontend/icons/agenda_act.svg');
$('<img>').attr('src', 'https://www.turnonet.com/frontend/icons/work_act.svg');
$('<img>').attr('src', 'https://www.turnonet.com/frontend/icons/sucursal_act.svg');
$('<img>').attr('src', 'https://www.turnonet.com/frontend/icons/lender_act.svg');
$('<img>').attr('src', 'https://www.turnonet.com/frontend/icons/support_act.svg');
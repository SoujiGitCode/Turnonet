new WOW().init();
$("#top-button").click(function() {
    $("html, body").animate({
        scrollTop: "0px"
    })
});
$("#btn-toogle").click(function() {
    var el = document.getElementById('content-sidenav');
    el.style.display = (el.style.display == 'none') ? 'block' : 'none';
});
$(window).scroll(function() {
    60 <= $(window).scrollTop() ? $("#sticky-header").addClass("sticky") : $("#sticky-header").removeClass("sticky"),
        800 >= $(window).scrollTop() ? $("#top-button").css("opacity", "0") : $("#top-button").css("opacity", "1")
});
$(window).scroll(function() {
  
        var home = Math.round($('#home').offset().top);
        var caracteristicas = Math.round($('#caracteristicas').offset().top);
        var precios = Math.round($('#precios').offset().top);
        var faq = Math.round($('#faq').offset().top);
        var contacto = Math.round($('#contacto').offset().top);
        var funcionamiento = Math.round($('#funcionamiento').offset().top);
        if ($(window).width() >= 768) {


            if ($(window).scrollTop() + 20 >= home -60) {
                $(".li-nav").removeClass('active');
                $('#nav-1').addClass('active');
                $('#nav-m1').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= funcionamiento -60) {
                $(".li-nav").removeClass('active');
                $('#nav-2').addClass('active');
                $('#nav-m2').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= caracteristicas -60) {
                $(".li-nav").removeClass('active');
                $('#nav-3').addClass('active');
                $('#nav-m3').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= precios -60) {
                $(".li-nav").removeClass('active');
                $('#nav-5').addClass('active');
                $('#nav-m5').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= faq -60) {
                $(".li-nav").removeClass('active');
                $('#nav-4').addClass('active');
                $('#nav-m4').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= contacto -60) {
                $(".li-nav").removeClass('active');
                $('#nav-6').addClass('active');
                $('#nav-m6').addClass('active');
            }
        } else {
            if ($(window).scrollTop() + 20 >= home -80) {
                $(".li-nav").removeClass('active');
                $('#nav-1').addClass('active');
                $('#nav-m1').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= funcionamiento -80) {
                $(".li-nav").removeClass('active');
                $('#nav-2').addClass('active');
                $('#nav-m2').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= caracteristicas -80) {
                $(".li-nav").removeClass('active');
                $('#nav-3').addClass('active');
                $('#nav-m3').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= precios -80) {
                $(".li-nav").removeClass('active');
                $('#nav-5').addClass('active');
                $('#nav-m5').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= faq -80) {
                $(".li-nav").removeClass('active');
                $('#nav-4').addClass('active');
                $('#nav-m4').addClass('active');
            }
            if ($(window).scrollTop() + 20 >= contacto -80) {
                $(".li-nav").removeClass('active');
                $('#nav-6').addClass('active');
                $('#nav-m6').addClass('active');
            }
        }
    
});

jQuery(window).load(function() {

    if ($(location).attr('hash') == '#about-us') {
        if ($(window).width() >= 768) {
            var targetOffset = $('#home').offset().top -40;
        } else {
            var targetOffset = $('#home').offset().top;
        }
        $('html,body').animate({
            scrollTop: targetOffset
        }, 1000);
        $(".li-nav").removeClass('active');
        $('#nav-1').addClass('active');
        $('#nav-m1').addClass('active');
    }
    if ($(location).attr('hash') == '#funcionamiento') {
        if ($(window).width() >= 768) {
            var targetOffset = $('#funcionamiento').offset().top -40;
        } else {
            var targetOffset = $('#funcionamiento').offset().top;
        }
        $('html,body').animate({
            scrollTop: targetOffset
        }, 1000);
        $(".li-nav").removeClass('active');
        $('#nav-2').addClass('active');
        $('#nav-m2').addClass('active');
    }
    if ($(location).attr('hash') == '#caracteristicas') {
        if ($(window).width() >= 768) {
            var targetOffset = $('#caracteristicas').offset().top -40;
        } else {
            var targetOffset = $('#caracteristicas').offset().top;
        }
        $('html,body').animate({
            scrollTop: targetOffset
        }, 1000);
        $(".li-nav").removeClass('active');
        $('#nav-3').addClass('active');
        $('#nav-m3').addClass('active');
    }

    if ($(location).attr('hash') == '#precios') {
        if ($(window).width() >= 768) {
            var targetOffset = $('#precios').offset().top -40;
        } else {
            var targetOffset = $('#precios').offset().top;
        }
        $('html,body').animate({
            scrollTop: targetOffset
        }, 1000);
        $(".li-nav").removeClass('active');
        $('#nav-5').addClass('active');
        $('#nav-m5').addClass('active');
    }

    if ($(location).attr('hash') == '#faq') {
        if ($(window).width() >= 768) {
            var targetOffset = $('#faq').offset().top -40;
        } else {
            var targetOffset = $('#faq').offset().top;
        }
        $('html,body').animate({
            scrollTop: targetOffset
        }, 1000);
        $(".li-nav").removeClass('active');
        $('#nav-4').addClass('active');
        $('#nav-m4').addClass('active');
    }

    if ($(location).attr('hash') == '#contacto') {
        if ($(window).width() >= 768) {
            var targetOffset = $('#contacto').offset().top -40;
        } else {
            var targetOffset = $('#contacto').offset().top;
        }
        $('html,body').animate({
            scrollTop: targetOffset
        }, 1000);
        $(".li-nav").removeClass('active');
        $('#nav-6').addClass('active');
        $('#nav-m6').addClass('active');
    }


    $("#mask").hide(),
        60 <= $(window).scrollTop() ? $("#sticky-header").addClass("sticky") : $("#sticky-header").removeClass("sticky"),
        800 >= $(window).scrollTop() ? $("#top-button").css("opacity", "0") : $("#top-button").css("opacity", "1")
});

$("#owl-carousel-1").owlCarousel({
    loop: true,
    dots: !1,
    items: 5,
    margin: 10,
    autoplay: true,
    autoplayTimeout: 1e3,
    autoplayHoverPause: !0,
    nav: true,
    navText : ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
    responsive: {
        0: {
            items: 2
        },
        500: {
            items: 3
        },
        768: {
            items: 3
        },
        1e3: {
            items: 3
        }
    }
});
$("#owl-carousel-2").owlCarousel({
    loop: true,
    dots: !1,
    items: 5,
    margin: 10,
    autoplay: true,
    smartSpeed: 1000,
    autoplayTimeout: 1e3,
    autoplayHoverPause: !0,
    nav:false,
    responsive: {
        0: {
            items: 1
        },
        500: {
            items: 1
        },
        768: {
            items: 1
        },
        1e3: {
            items: 1
        }
    }
});
$("#owl-carousel-3").owlCarousel({
    loop: true,
    dots: !1,
    items: 5,
    margin: 10,
    autoplay: true,
    smartSpeed: 1500,
    autoplayTimeout: 1e3,
    autoplayHoverPause: !0,
    nav:false,
    responsive: {
        0: {
            items: 1
        },
        500: {
            items: 1
        },
        768: {
            items: 1
        },
        1e3: {
            items: 1
        }
    }
});
$('a[href*=#]').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
        location.hostname == this.hostname) {
        if ($(this.hash) != '') {
            $(".li-nav").removeClass('active');
            var $target = $(this.hash);
            $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
            if ($target.length) {
                var targetOffset = $target.offset().top -40;
                $('html,body').animate({
                    scrollTop: targetOffset
                }, 1000);
                return false;
            }
        }
    }
});
if ($("#home").length) {
    $("#home").YTPlayer({
        videoURL: $("#home").data("video"),
        host: 'https://www.youtube.com',
        containment: "#home",
        mute: !0,
        loop: !0,
        startAt: 1,
        showControls: !1,
        showYTLogo: !1,
        playerVars: {
            'origin': 'https://devapp.turnonet.com/onepage'
        },
    })
}

function enter_name_1(e) {
    if (e.keyCode == 13) {
        if ($("#name-1").val() != "") {
            $("#email-1").focus();
        }
    }
}

function enter_email_1(e) {
    if (e.keyCode == 13) {
        if ($("#email-1").val() != "") {
            $("#subject").focus();
        }
    }
}

function enter_subject(e) {
    if (e.keyCode == 13) {
        if ($("#subject").val() != "") {
            $("#message").focus();
        }
    }
}

function enter_message(e) {
    if (e.keyCode == 13) {
        if ($("#message").val() != "") {

            sendContact()
        }
    }
}

function sendContact() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    grecaptcha.ready(function() {
        grecaptcha.execute($("#captcha-key").val(), {
            action: 'homepage'
        }).then(function(token) {
            $('#captcha').val(token);
            if ($("#name-1").val() == "") {
                launch_toast("Ingresá tu nombre");
                $("#name-1").focus();
                return false;
            } else if ($("#email-1").val() == "") {
                launch_toast("Ingresá un correo electrónico");
                $("#email-1").focus();
                return false;
            } else if ($("#email-1").val() != "" && !expr.test($("#email-1").val())) {
                launch_toast("Ingresá un correo electrónico válido");
                $("#email-1").focus();
                return false;
            }
            if ($("#subject").val() == "") {
                launch_toast("Ingresá tu asunto");
                $("#subject").focus();
                return false;
            }
            if ($("#message").val() == "") {
                launch_toast("Ingresá tu mensaje");
                $("#message").focus();
                return false;
            } else {
                $("#boton-1").prop('disabled', true);
                $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
                $.ajax({
                    url: $("#url").val() + '/store_contacto',
                    type: 'POST',
                    dataType: 'json',
                    data: $('#form').serialize(),
                    success: function(data) {
                        $("#form-1")[0].reset();
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
        });
    });
}

function launch_toast(e) {
    $.growl.error({
        title: "<i class='fa fa-exclamation-circle'></i> Error",
        message: e
    });
}


$(function() {
    var hash = window.location.hash;
    if (hash != '') {
        $("#category").val(hash.replace('#', ''));
        ShowTabs();
    }
});


function ShowTabs() {
    var category = $("#category").val();
    $(".tab-pane").removeClass('active in');
    $(".opt").removeClass('active');
    $("#" + category).addClass('active in');
    $("#opt" + category).addClass('active');
}


function ShowTabs_1(value) {
    var category = value;
    $(".tab-pane").removeClass('active in');
    $("#" + category).addClass('active in');
}


function openModal(url) {
    $("#myModal").modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
    $('#iframe-video').attr('src',url);
}

function openRegister(url) {
	 $("#form-2")[0].reset();
    $("#myModalRegister").modal('show');

}


function closeModal() {
    $("#myModal").modal('hide');
    $('#iframe-video').attr('src', '');
}

function closeModalRegister() {
    $("#myModalRegister").modal('hide');
     $("#form-2")[0].reset();
}


function guardar(){

	var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if ($("#name").val() == "") {
        launch_toast("Ingresá tu nombre de usuario");
        $("#name").focus();
        return false;
    }
    else if ($("#email").val() == "") {
        launch_toast("Ingresá tu correo electrónico");
        $("#email").focus();
        return false;
    } else if (!expr.test($("#email").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#email").focus();
        return false;
    }else if ($("#password").val() == "") {
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
        $("#boton-2").prop('disabled', true);
        $("#boton-2").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/store_registro',
            type: 'POST',
            dataType: 'json',
            data: $("#form-2").serialize(),
            success: function(data) {
                if (data.msg != "error") {
                    $("#form-2")[0].reset();
                    $("#boton-2").prop('disabled', false);
                    $("#boton-2").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Registrate');
                    swal("Sus registro ha sido procesado con éxito, se ha enviado un código de verificación a su correo electrónico", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            location.reload();
                        });
                } else {
                     $("#boton-2").prop('disabled', false);
                $("#boton-2").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Registrate');
                        launch_toast('Este correo electrónico ya se encuentra registrado');
                    }
            },
            error: function(msj) {
                $("#boton-2").prop('disabled', false);
                $("#boton-2").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Registrate');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }

}

function hideWidget(){

    $("#bottom-bar-1").hide();

}

function setPrices(){
if( $('#usd').prop('checked') ) {
    $(".par").hide();
    $(".pusd").show();
    $("#title-plan").html('Los precios están expresados en USD.');
}else{
   $(".par").show();
    $(".pusd").hide();
    $("#title-plan").html('Los precios están expresados en ARS.');
}
}

function setPrices1(){
if( $('#usd1').prop('checked') ) {
    $(".par").hide();
    $(".pusd").show();
    $("#title-plan").html('Los precios están expresados en USD.');
}else{
   $(".par").show();
    $(".pusd").hide();
    $("#title-plan").html('Los precios están expresados en ARS.');
}
}
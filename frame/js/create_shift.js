var num_services = 0;
$("#text-services").html('No tenés servicios seleccionados');
var app = angular.module('myApp', ['ui.bootstrap']);
app.controller('post', function($scope, $http, $window) {
    $scope.name_day = '';
    $scope.name_month = '';
    $scope.month_act = $("#month").val();
    $scope.year_act = $("#year").val();
    $scope.month_select = '';
    $scope.nextmonth = '';
    $scope.nextyear = '';
    $scope.prevyear = '';
    $scope.prevmonth = '';
    $scope.calendar = function() {
        if ($("#tu_st").is(":checked")) {
            sobreturno = "1";
        } else {
            sobreturno = "0"
        };
        if ($("#service_select").length && $("#service_select").val() == "") {
            var service_select = 0;
        }
        if (!$("#service_select").length) {
            var service_select = 0;
        }
        if ($("#service_select").length && $("#service_select").val() != "") {
            var service_select = $("#service_select").val();
        }
        if (num_services == 0) {
            $("#prevcalendario").hide();
            $("#prevloader").show();
        }
        $http.get($("#url").val() + '/calendario/' + $("#month").val() + "/" + $("#year").val() + "/" + $("#empid").val() + "/" + $("#sucid").val() + "/" + $("#presid").val() + "/" + service_select + "/" + sobreturno).then(function successCallback(response) {
            $scope.list_calendar = response.data.calendar;
            $scope.currentPage_calendar = 1;
            $scope.entryLimit_calendar = 50;
            $scope.filteredItems_calendar = $scope.list_calendar.length;
            $scope.totalItems_calendar = $scope.list_calendar.length;
            $scope.name_month = response.data.name_month;
            $scope.month_select = response.data.month_act;
            $scope.nextmonth = response.data.nextmonth;
            $scope.nextyear = response.data.nextyear;
            $scope.prevyear = response.data.prevyear;
            $scope.prevmonth = response.data.prevmonth;
            $scope.loader = 1;
            $("#prevcalendario").show();
            $("#prevloader").hide();
        });
    }
    $scope.calendar();
    $scope.nextMonth = function() {
        $("#year").val($scope.nextyear);
        $("#month").val($scope.nextmonth);
        $("#prevcalendario").hide();
        $("#prevloader").show();
        angular.element($('[ng-controller="post"]')).scope().calendar();
    }
    $scope.prevMonth = function() {
        $("#year").val($scope.prevyear);
        $("#month").val($scope.prevmonth);
        $("#prevcalendario").hide();
        $("#prevloader").show();
        angular.element($('[ng-controller="post"]')).scope().calendar();
    }
    $scope.times = function() {
        if ($("#tu_st").is(":checked")) {
            sobreturno = "1";
        } else {
            sobreturno = "0"
        };
        if ($("#service_select").length && $("#service_select").val() == "") {
            var service_select = 0;
        }
        if (!$("#service_select").length) {
            var service_select = 0;
        }
        if ($("#service_select").length && $("#service_select").val() != "") {
            var service_select = $("#service_select").val();
        }
        $("#prevtimes").hide();
        $("#prevloadertimes").show();
        $http.get($("#url").val() + "/times/" + $("#date").val() + "/" + $("#empid").val() + "/" + $("#sucid").val() + "/" + $("#presid").val() + "/" + service_select + "/" + sobreturno).then(function successCallback(response) {
            $scope.name_day = response.data.name_day;
            $scope.list_times = response.data.times;
            $scope.currentPage_times = 1;
            $scope.entryLimit_times = 60;
            $scope.filteredItems_times = $scope.list_times.length;
            $scope.totalItems_times = $scope.list_times.length;
            $("#prevtimes").show();
            $("#prevloadertimes").hide();
        });
    }
    $scope.selectTime = function(time, text, id) {
        $(".cturno").removeClass('times_active');
        $("#time-" + id).addClass('times_active');
        $("#times").val(time);
        $("#title-modal").html('CONFIRMAR TURNO');
        $("#fecha").html('Fecha: ' + text);
        $("#datos").show();
        $("#horarios").hide();
    }
    $scope.selectDay = function(date) {
        $("#title-modal").html('Horarios');
        if ($("#service_select").length && $("#service_select").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná un servicio"
            });
            $("#service").focus();
            return false;
        } else {
            $("#horarios").show();
            $("#datos").hide();
            $("#myModal").modal('show');
            $("#times").val('');
            $("#date").val(date);
            $('#btn-create').attr("disabled", true);
            $scope.times();
        }
    }
});

function enter_filtro(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        filtro();
    }
}

function filtro() {
    if ($("#search").val() == "") {
        $(".search-items").hide();
    } else {
        angular.element($('[ng-controller="post"]')).scope().rows();
    }
}
$("#search").keyup(function() {
    if (this.value.length <= 1) {
        $(".search-items").hide();
    } else {
        angular.element($('[ng-controller="post"]')).scope().rows();
    }
});
$(document).ready(function() {
    $('#service').on('change', function() {
        if ($("#service").val() != "") {
            $("#submenu").append('<li id="serv-' + $("#service").val() + '" ><a  onclick="removeLi(' + $("#service").val() + ')">' + $('#service option:selected').text() + '<img src="//www.turnonet.com/frame/imagenes/delete2.png" width="10" height="10" class="img_b"></a></li>');
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
            num_services++;
            $("#text-services").html('Tenés ' + num_services + ' servicios seleccionados');
            angular.element($('[ng-controller="post"]')).scope().calendar();
        }
    });
});
$(function() {});

function closeModal() {
    $('#btn-create').attr("disabled", true);
    $("#myModal").modal('hide');
    $("#datos").hide();
    $("#horarios").show();
    $("#times").val('');
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
    num_services--;
    if (num_services <= 0) {
        num_services = 0;
        $("#text-services").html('No tenés servicios seleccionados');
    } else {
        $("#text-services").html('Tenés ' + num_services + ' servicios seleccionados');
    }
}

function upSobreturno() {
    if ($("#tu_st").is(":checked")) {
        sobreturno = "1";
    } else {
        sobreturno = "0"
    };
    angular.element($('[ng-controller="post"]')).scope().calendar();
}

function guardar() {
    var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($('#nombrec').val() == "") {
        launch_toast("Ingresá el nombre completo");
        $('#nombrec').focus();
        return false;
    } 
    else if ($('#dni').length>0 && $('#dni').val() == "" ) {
        launch_toast("Ingresá el DNI");
        $('#dni').focus();
        return false;
    } 
    else if ($('#emailrec').val() == "") {
        launch_toast("Ingresá el correo electrónico");
        $('#emailrec').focus();
        return false;
    } else if ($('#conemailrec').val() == "") {
        launch_toast("Ingresá el correo electrónico");
        $('#conemailrec').focus();
        return false;
    } else if ($('#emailrec').val() == "") {
        launch_toast("Ingresá el correo electrónico");
        $('#emailrec').focus();
        return false;
    } else if ($('#conemailrec').val() == "") {
        launch_toast("Ingresá el correo electrónico");
        $('#conemailrec').focus();
        return false;
    } else if ($('#emailrec').val() != $('#conemailrec').val()) {
        launch_toast("Los correos electrónicos deben coincidir");
        $('#emailrec').focus();
        return false
    } else if (!expr.test($("#emailrec").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#emailrec").focus();
        return false;
    } else if (!expr.test($("#conemailrec").val())) {
        launch_toast("Ingresá un correo electrónico válido");
        $("#conemailrec").focus();
        return false;
    }



    else{

$("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Procesando');
        var route = $("#url").val() + '/confirmar_turno';
        $.ajax({
            url: route,
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                if (data.response == 'true') {

                    if($("#inputs_add").val()!="0"){
                    	
                     
                        window.location = $("#url").val() + '/'+$("#url_business").val()+"/"+$("#url_lender").val()+"/confirmar/"+data.url;
                    }else{

                       window.location = $("#url").val() + '/'+$("#url_business").val()+"/"+$("#url_lender").val()+"/turno/"+data.url;

                   }
               }

               if (data.response == 'no-email') {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Turno');
                    launch_toast('El correo electrónico no existe');
                }

               if (data.response == 'limit') {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Turno');
                    launch_toast('El usuario ha superado el límite de turnos');
                }
                if (data.response == 'blocking') {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Turno');
                    launch_toast('El usuario se encuentra bloqueado para agendar turnos');
                }
                
                if (data.response == 'false') {
                    $("#boton-1").prop('disabled', false);
                    $("#boton-1").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Turno');
                    launch_toast('El turno para el día y hora solicitado ya fue ocupado');
                }
            },
            error: function(msj) {
                $("#boton-1").prop('disabled', false);
                $("#boton-1").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Turno');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        })


    }
}

$(document).ready(function(){
  $("#conemailrec").on('paste', function(e){
    e.preventDefault();
  })
  
  $("#conemailrec").on('copy', function(e){
    e.preventDefault();
  })
})
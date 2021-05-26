var num_services = 0;
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
    $scope.selectTime = function(time, id) {
        $(".cturno").removeClass('times_active');
        $("#time-" + id).addClass('times_active');
        $("#times").val(time);
        $('#btn-create').attr("disabled", false);
    }
    $scope.selectDay = function(date) {
        if ($("#us_id").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná un paciente"
            });
            $("#name").focus();
            return false;
        } else if ($("#service_select").length && $("#service_select").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná un servicio"
            });
            $("#service").focus();
            return false;
        }
        if ($("#message").length && $("#message").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Ingresá un comentario"
            });
            $("#message").focus();
            return false;
        } else {
            $("#myModal").modal('show');
            $("#times").val('');
            $("#date").val(date);
            $('#btn-create').attr("disabled", true);
            $scope.times();
        }
    }
});
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
            num_services++;
            angular.element($('[ng-controller="post"]')).scope().calendar();
        }
    });
});
$(function() {});

function closeModal() {
    $('#btn-create').attr("disabled", true);
    $("#myModal").modal('hide');
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
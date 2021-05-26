var num_services = 0;
var app = angular.module('myApp', ['ui.bootstrap']);
app.controller('post', function($scope, $http, $window) {
    $scope.loader = 0;
    $scope.name_day = '';
    $scope.name_month = '';
    $scope.month_act = $("#month").val();
    $scope.year_act = $("#year").val();
    $scope.month_select = '';
    $scope.nextmonth = '';
    $scope.nextyear = '';
    $scope.prevyear = '';
    $scope.prevmonth = '';
    $scope.rows = function() {
        if ($("#search").val() == '') {
            $(".search-items").hide();
        } else {
            $http.get($("#url").val() + '/lists_directory_search/' + $("#search").val() + '/' + $("#empid").val()+"/1").then(function successCallback(response) {
                $scope.list = response.data;
                $scope.currentPage = 1;
                $scope.entryLimit = 50;
                $scope.filteredItems = $scope.list.length;
                $scope.totalItems = $scope.list.length;
                $(".search-items").show();
            });
        }
    }
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
                message: "Seleccioná un cliente"
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
    $scope.asignarValor = function(item) {
        $("#us_id").val(item.us_id);
        $(".search-items").hide();
        $(".ss-i").hide();
        var response = '<p class="p-1" style="text-transform: capitalize;">' + item.name + '</p>';
        if (item.email != "") {
            response += '<p class="p-1" id="btn-email" onclick="openEmail(this.id)" data-email="' + item.email + '"><i class="fa fa-envelope-o"></i> ' + item.email + '</p>';
        }
        if ($("#date_1_dd").length != 0) {
            $("#date_1_dd").val(item.day);
        }
        if ($("#date_1_mm").length != 0) {
            $("#date_1_mm").val(item.month);
        }
        if ($("#date_1").length != 0) {
            $("#date_1").val(item.year);
        }
        if ($("#f_2").length != 0) {
            $("#f_2").val(item.usm_tipdoc);
        }
        if ($("#f_3").length != 0) {
            $("#f_3").val(item.usm_obsoc);
        }
        if ($("#f_4").length != 0) {
            $("#f_4").val(item.usm_obsocpla);
        }
        if ($("#f_5").length != 0) {
            $("#f_5").val(item.usm_numdoc);
        }
        if ($("#f_6").length != 0) {
            $("#f_6").val(item.usm_afilnum);
        }
        if ($("#f_7").length != 0) {
            $("#f_7").val(item.usm_tel);
        }
        if ($("#f_8").length != 0) {
            $("#f_8").val(item.usm_cel);
        }
        if ($("#f_9").length != 0) {
            $("#f_9").val(item.usm_gen1);
        }
        if ($("#f_10").length != 0) {
            $("#f_10").val(item.usm_gen2);
        }
        if ($("#f_11").length != 0) {
            $("#f_11").val(item.usm_gen3);
        }
        if ($("#f_12").length != 0) {
            $("#f_12").val(item.usm_gen4);
        }
        if ($("#f_13").length != 0) {
            $("#f_13").val(item.usm_gen5);
        }
        if ($("#f_14").length != 0) {
            $("#f_14").val(item.usm_gen6);
        }
        if ($("#f_15").length != 0) {
            $("#f_15").val(item.usm_gen7);
        }
        if ($("#f_16").length != 0) {
            $("#f_16").val(item.usm_gen8);
        }
        $("#data-user").html(response);
        $("#panel-user").show();
        $("#d-add").show();
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

function removeUser() {
    $("#panel-user").hide();
    $("#us_id").val('');
    $(".ss-i").show();
    $(".search-items").show();
    $("#d-add").hide();
    if ($("#date_1_dd").length != 0) {
        $("#date_1_dd").val('');
    }
    if ($("#date_1_mm").length != 0) {
        $("#date_1_mm").val('');
    }
    if ($("#date_1").length != 0) {
        $("#date_1").val('');
    }
    if ($("#f_2").length != 0) {
        $("#f_2").val('');
    }
    if ($("#f_3").length != 0) {
        $("#f_3").val('');
    }
    if ($("#f_4").length != 0) {
        $("#f_4").val('');
    }
    if ($("#f_5").length != 0) {
        $("#f_5").val('');
    }
    if ($("#f_6").length != 0) {
        $("#f_6").val('');
    }
    if ($("#f_7").length != 0) {
        $("#f_7").val('');
    }
    if ($("#f_8").length != 0) {
        $("#f_8").val('');
    }
    if ($("#f_9").length != 0) {
        $("#f_9").val('');
    }
    if ($("#f_10").length != 0) {
        $("#f_10").val('');
    }
    if ($("#f_11").length != 0) {
        $("#f_11").val('');
    }
    if ($("#f_12").length != 0) {
        $("#f_12").val('');
    }
    if ($("#f_13").length != 0) {
        $("#f_13").val('');
    }
    if ($("#f_14").length != 0) {
        $("#f_14").val('');
    }
    if ($("#f_15").length != 0) {
        $("#f_15").val('');
    }
    if ($("#f_16").length != 0) {
        $("#f_16").val('');
    }
}

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

            if (data.msg == 'no-email') {
                $("#btn-create").prop('disabled', false);
                $("#btn-create").html('<i class="fa fa-check-circle" aria-hidden="true"></i> Confirmar Turno');
                launch_toast('El correo electrónico no existe');
                console.log('here');
            }


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
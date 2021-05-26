var num_services = 0;
$(function() {
    $('#fecha_inicial').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $('#fecha_final').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $('#fecha_turno').datetimepicker({
        format: 'YYYY-MM-DD'
    });
});
var app = angular.module('myApp', ['ui.bootstrap']);
app.filter('startFrom', function() {
    return function(input, start) {
        if (input) {
            start = +start;
            return input.slice(start);
        }
        return [];
    }
});
app.controller('post', function($scope, $http, $window) {
    $scope.predicate = 'timestamp';
    $scope.name_date = '';
    $scope.name_day = '';
    $scope.text_not = "";
    $scope.items_selected = '';
    if ($("#business").val() == "115") {
        $scope.reverse = true;
    } else {
        $scope.reverse = false;
    }
    $scope.rows_customer = function() {
        $scope.items_selected = '';
        if ($("#searchc").val() == '') {
            $(".search-items").hide();
        } else {
            $http.get($("#url").val() + '/lists_directory_search/' + $("#searchc").val() + '/' + $("#business").val() + "/1").then(function successCallback(response) {
                $scope.list_customers = response.data;
                $scope.currentPage_customers = 1;
                $scope.entryLimit_customers = 50;
                $scope.filteredItems_customers = $scope.list_customers.length;
                $scope.totalItems_customers = $scope.list_customers.length;
                $(".search-items").show();
            });
        }
    }
    $scope.rows = function() {
        $scope.items_selected = '';
        $(".cntent-ass").hide();
        $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_diary/' + $("#date").val() + '/' + $("#status").val() + '/' + $("#type_shift").val() + '/' + $("#lenders").val() + '/' + $("#branch").val() + '/' + $("#business").val()).then(function successCallback(response) {
            $scope.list = response.data.lists;
            $scope.name_date = response.data.name_date;
            $scope.currentPage = 1;
            $scope.entryLimit = 50;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $(".bg-preload").hide();
            $(".bg-list").show();
        });
    }
    $scope.rows();
    $scope.goTourl = function(code) {
        window.open($("#url").val() + '/agenda/turno/' + code);
    };


    $scope.goTourlZoom = function(code) {
        window.open(code);
    };
    //cambiar numero de pagina
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    // filtrar paginas
    $scope.filter = function() {
        if ($("#search").val() == '') {
            $(".bg-preload").hide();
            $(".bg-list").show();
            $(".cntent-ass").hide();
            $(".pagination").show();
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
        } else {
            show_loader();
            $(".pagination").hide();
            $(".cntent-ass").hide();
            $scope.filteredItems = $scope.filtered.length;
            $scope.totalItems = $scope.filtered.length;
        }
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    $scope.goTourl = function(code) {
        window.open($("#url").val() + '/agenda/turno/' + code);
    };
    $scope.optCan = function(id, code) {
        $(".cntent-ass").hide();
        swal("Confirmá que querés cancelar el turno", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.open($("#url").val() + "/agenda/cancelar/" + code);
                }
            });
    };
    $scope.asisConf = function(id) {
        swal("¿Deseas confirmá la asistencia?", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/update_asis_shift',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            status: '1',
                            id: id,
                        },
                        success: function() {
                            $(".cntent-ass").hide();
                            $.growl.notice({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El estado del turno ha sido actualizado"
                            });
                            $scope.rows();
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
    };

    $scope.desbloqueo = function(id) {


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
                           $scope.rows();
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
    $scope.asisPar = function(id) {
        swal("¿Deseas confirmá la asistencia parcial?", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/update_asis_shift',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            status: '2',
                            id: id,
                        },
                        success: function() {
                            $(".cntent-ass").hide();
                            $.growl({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El estado del turno ha sido actualizado"
                            });
                            $scope.rows();
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
    };
    $scope.asisCan = function(id) {
        swal("¿Deseas informá la ausencia?", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/update_asis_shift',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            status: '0',
                            id: id,
                        },
                        success: function() {
                            $(".cntent-ass").hide();
                            $.growl.warning({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El estado del turno ha sido actualizado"
                            });
                            $scope.rows();
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
    };
    $scope.pdfCliente = function(code, business, id) {
        window.open($("#url").val() + "/pdf/cliente/" + code + "/" + business + "/" + id);
    }
    $scope.pdfTurno = function(code) {
        window.open($("#url").val() + "/pdf/turno/" + code);
    }
    $scope.goToShiftC = function(business, user) {
        window.open($("#url").val() + '/agenda/cliente/' + business + '/' + user);
    };
    $scope.optAct = function(id) {
        $(".cntent-ass").hide();
        swal("Confirmá que querés dar de alta el turno", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/update_status_shift',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            status: '1',
                            id: id,
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El turno  ha sido actualizado"
                            });
                            window.location = $("#url").val() + "/agenda";
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
    };
    $scope.optReasign = function(id, code) {
        $(".cntent-ass").hide();
        swal("Confirmá que querés reasignar el turno", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.open($("#url").val() + "/agenda/reasignar/" + code);
                }
            });
    };

    $scope.optNew = function(id, code) {
        $(".cntent-ass").hide();
        swal("Confirmá que querés agendar un nuevo turno para este cliente y prestador", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.open($("#url").val() + "/agenda/nuevo/" + code);
                }
            });
    };
    $scope.call_mail = function(email) {
        window.open('mailto:' + email);
    }
    $scope.optAsis = function(id) {
        for (var i = 0; i < $scope.totalItems; i++) {
            var item = $scope.list[i];
            if (item.id != id) {
                $("content-" + item.id).hide();
            }
        }
        var el = document.getElementById('content-' + id);
        el.style.display = (el.style.display == 'none') ? 'block' : 'none';
    }
    $scope.OpemModalPas = function(id, code) {
        actModalPac(id, code);
    }
    $scope.buscar_fecha = function() {
        if ($('#fecha_inicial').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_inicial').focus();
            return (false);
        }
        if ($('#fecha_final').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_final').focus();
            return (false);
        } else {
            var fecha_inicial = document.getElementById("fecha_inicial").value;
            var fecha_final = document.getElementById("fecha_final").value;
            var url = $("#url").val() + "/excel/turnos/" + fecha_inicial + "/" + fecha_final + "/" + $("#status").val() + '/' + $("#type_shift-1").val() + '/' + $("#lenders-1").val() + '/' + $("#branch-1").val() + '/' + $("#business").val();
            window.open(url);
        }
    }
    $scope.sortBy = function(propertyName) {
        $scope.propertyName = propertyName;
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.rows();
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
        $http.get($("#url").val() + "/times/" + $("#fecha_turno").val() + "/" + $("#business").val() + "/" + $("#branch-2").val() + "/" + $("#lenders-2").val() + "/" + service_select + "/" + sobreturno).then(function successCallback(response) {

            $scope.text_not = "No quedan turnos disponibles para el " + response.data.name_day;
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
    $scope.disponibilidad = function() {
        if ($("#branch-2").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una sucursal"
            });
            $("#branch-2").focus();
            return false;
        } else if ($("#lenders-2").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná un prestador"
            });
            $("#lenders-2").focus();
            return false;
        } else if ($("#service_select").length && $("#service_select").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná un servicio"
            });
            $("#service").focus();
            return false;
        } else if ($("#fecha_turno").val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $("#fecha_turno").focus();
            return false;
        } else {
            $scope.times();
            $("#agenda").hide();
            $("#datos").hide();
            $("#horas").show();
        }
    }
     $scope.set_items = function(id) {
        $("#baja-selec").hide();
        var item = "," + $('#' + id).val();
        $scope.items_selected = $scope.items_selected.replace(item, '');
        if ($('#' + id).prop('checked')) {
            $scope.items_selected = $scope.items_selected + item;
            $("#all_shift").val($scope.items_selected);
             $("#baja-selec").show();
        }
        if ($scope.items_selected == "") {
            $("#baja-selec").hide();
            $("#all_shift").val("");
        } 
      
    }
    $scope.selectTime = function(time, id) {
        $(".cturno").removeClass('times_active');
        $("#time-" + id).addClass('times_active');
        $("#times").val(time);
        $('#btn-create').attr("disabled", false);
    }
    $scope.buscar_fecha_business = function() {
        if ($('#fecha_inicial').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_inicial').focus();
            return (false);
        }
        if ($('#fecha_final').val() == "") {
            $.growl.error({
                title: "<i class='fa fa-exclamation-circle'></i> Error",
                message: "Seleccioná una fecha"
            });
            $('#fecha_final').focus();
            return (false);
        } else {
            var fecha_inicial = document.getElementById("fecha_inicial").value;
            var fecha_final = document.getElementById("fecha_final").value;
            if ($("#business-1").val() == "") {
                var url = $("#url").val() + "/excel/turnos/sodimac/" + fecha_inicial + "/" + fecha_final + "/" + $("#status").val() + '/' + $("#type_shift-1").val();
            } else {
                var url = $("#url").val() + "/excel/turnos/" + fecha_inicial + "/" + fecha_final + "/" + $("#status").val() + '/' + $("#type_shift-1").val() + '/' + $("#lenders-1").val() + '/' + $("#branch-1").val() + '/' + $("#business-1").val();
            }
            window.open(url);
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
        $("#data-user").html(response);
        $("#panel-user").show();
        $("#d-add").show();
    }
});

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
}

function openMonth() {
    $(".cntent-ass").hide();
    $('.datepicker').click();
    $("#day").removeClass('btn-info');
    $("#day").addClass('btn-default');
    $("#month").removeClass('btn-default');
    $("#month").addClass('btn-info');
}

function openDays() {
    $(".cntent-ass").hide();
    $("#date").val('');
    $("#datepicker").val('');
    $("#month").removeClass('btn-info');
    $("#month").addClass('btn-default');
    $("#day").removeClass('btn-default');
    $("#day").addClass('btn-info');
    searchDate($("#now").val());
}
var elems = document.querySelectorAll('.datepicker');
var instances = M.Datepicker.init(elems);
// Or with jQuery
$(document).ready(function() {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        closeOnSelect: true,
        i18n: {
            cancel: 'Hoy',
            done: 'Seleccionar',
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            weekdaysAbbrev: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        },
        onOpen: function() {
            $("#datepicker").val('');
        },
        onClose: function() {}
    });
});

function setDate() {
    if ($("#datepicker").val() != "") {
        searchDate($("#datepicker").val());
    } else {
        openDays();
    }
}

function searchDate(date) {
    $("#date").val(date);
    angular.element($('[ng-controller="post"]')).scope().rows();
}
$(document).on("click", function(e) {
    if ($(e.target).attr("class") != 'nav-icon-1  confirm-as' && $(e.target).attr("class") != 'li-asis' && $(e.target).attr("class") != 'li-au' && $(e.target).attr("class") != 'li-p' && $(e.target).attr("class") != 'swal-button swal-button--cancel') {
        $(".cntent-ass").hide();
    }
});

function changeSucursal_excel() {
    $('#lenders-1').empty();
    $("#lenders-1").append(`<option value='ALL'> Todos los prestadores</option>`);
    if ($("#branch-1").val() != "") {
        $.get($("#url").val() + "/lista_branch/" + $("#branch-1").val() + "", function(res, sta) {
            res.forEach(element => {
                $("#lenders-1").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    }
}

function changeEmpresa_excel() {
    $('#branch-1').empty();
    $("#branch-1").append(`<option value='ALL'> Todas las sucursales</option>`);
    if ($("#business-1").val() != "") {
        $.get($("#url").val() + "/lists_branch_business/" + $("#business-1").val() + "", function(res, sta) {
            res.forEach(element => {
                $("#branch-1").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    }
}

function changeSucursal() {
    $('#lenders').empty();
    $("#lenders").append(`<option value='ALL'> Todos los prestadores</option>`);
    if ($("#branch").val() != "") {
        $.get($("#url").val() + "/lista_branch/" + $("#branch").val() + "", function(res, sta) {
            res.forEach(element => {
                $("#lenders").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    }
    angular.element($('[ng-controller="post"]')).scope().rows();
}

function changeSucursal2() {
    $("#reps-servs").html('');
    $('#lenders-2').empty();
    $("#lenders-2").append(`<option value="">Seleccionar Prestador</option>`);
    if ($("#branch-2").val() != "") {
        $.get($("#url").val() + "/lista_branch/" + $("#branch-2").val() + "", function(res, sta) {
            res.forEach(element => {
                $("#lenders-2").append(`<option value=${element.id}> ${element.name} </option>`);
            });
        });
    }
}

function changePrestador2() {
    if ($("#lenders-2").val() != "") {
        $("#reps-servs").load($("#url").val() + "/lista_servicios_prestador/" + $("#lenders-2").val());
    }

}

function openModalAgenda() {
    $("#myModalA").modal({
        backdrop: 'static',
        keyboard: false
    }, 'show');

    changePrestador2();
    closeHoras();
    if ($("#count_lender").val() == "1") {
        angular.element($('[ng-controller="post"]')).scope().disponibilidad();

    }
}

function closeModalAgenda() {
    $("#myModalA").modal('hide');
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

function selectService() {
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
    }
}

function enter_filtro(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        filtro();
    }
}

function filtro() {
    if ($("#searchc").val() == "") {
        $(".search-items").hide();
    } else {
        angular.element($('[ng-controller="post"]')).scope().rows_customer();
    }
}
$("#searchc").keyup(function() {
    if (this.value.length <= 1) {
        $(".search-items").hide();
    } else {
        angular.element($('[ng-controller="post"]')).scope().rows_customer();
    }
});

function closeHoras() {
    $("#agenda").show();
    $("#datos").hide();
    $("#horas").hide();
}

function cliente() {
    $("#agenda").hide();
    $("#datos").show();
    $("#horas").hide();
    removeUser();
    $("#searchc").val('');
   filtro();
}

function guardar() {

    if ($("#us_id").val() == "") {
        $.growl.error({
            title: "<i class='fa fa-exclamation-circle'></i> Error",
            message: "Seleccioná un paciente"
        });
        $("#us_id").focus();
        return false;
    } else {
        var route = $("#url").val() + '/store_shift';

        $("#btn-create-2").prop('disabled', true);
        $("#btn-create-2").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: route,
            type: 'POST',
            dataType: 'json',
            data: $("#form").serialize(),
            success: function(data) {
                $("#btn-create-2").prop('disabled', false);
                $("#btn-create-2").html('Agendar Turno');
                if (data.msg == 'false') {
                    launch_toast('El turno para el día y hora solicitado ya fue ocupado');
                } else {
                    $("#myModalA").modal('hide');
                    angular.element($('[ng-controller="post"]')).scope().rows();
                    swal("Su turno ha sido agendado con éxito", {
                            allowOutsideClick: false,
                            closeOnClickOutside: false
                        })
                        .then((value) => {
                            window.open($("#url").val() + "/agenda/turno/" + data.code);
                        });
                }
            },
            error: function(msj) {
                $("#btn-create-2").prop('disabled', false);
                $("#btn-create-2").html('Agendar Turno');
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}

    function openModalCancel() {

        swal("Confirmá que querés cancelar los turnos seleccionados", {
            buttons: {
                cancel: "No",
                confirm: "Si"
            },
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
$("#form-c")[0].reset();
                $("#myModalC").modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            }
        });

    }

    function closeModalCancel() {
        $("#myModalC").modal('hide');
    }
    function set_items(id) {
    angular.element($('[ng-controller="post"]')).scope().set_items(id);
}
function cancela_turnos() {
    if ($("#message").val() == "") {
        launch_toast("Seleccioná un turno");
        $("#message").focus();
        return false;
    } 
    if ($("#message").val() == "") {
        launch_toast("Ingresá tu comentario");
        $("#message").focus();
        return false;
    } else {
        $("#boton-1").prop('disabled', true);
        $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
        $.ajax({
            url: $("#url").val() + '/update_status_all_shift',
            type: 'POST',
            dataType: 'json',
            data: $('#form-c').serialize(),
            success: function(data) {
                $("#form-c")[0].reset();
                $("#boton-3").prop('disabled', false);
                 closeModalCancel();
                swal("Los  turnos seleccionados han sido cancelados con éxito", {
                        allowOutsideClick: false,
                        closeOnClickOutside: false
                    })
                    .then((value) => {
                       
                    angular.element($('[ng-controller="post"]')).scope().rows();
                    });

            },
            error: function(msj) {
                $("#boton-3").prop('disabled', false);
                launch_toast('Ha ocurrido un error por favor intente más tarde');
            }
        });
    }
}

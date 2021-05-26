$(function() {
    $('#fecha_inicial').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $('#fecha_final').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $('#fecha_diary').datetimepicker({
        format: 'DD-MM-YYYY'
    }).on('dp.change', function(ev) {
        if ($("#fecha_diary").val() != "") {
            angular.element($('[ng-controller="post"]')).scope().rows();
        }
    })
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
    $scope.reverse = false;
    $scope.rows = function() {
        if ($("#fecha_diary").val() != "") {
            var date = $("#fecha_diary").val();
        } else {
            var date = 0;
        }
        $(".cntent-ass").hide();
        $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_diary_user/' + $("#us_id").val() + '/' + date + '/' + $("#status").val() + '/' + $("#type_shift").val() + '/' + $("#lenders").val() + '/' + $("#branch").val() + '/' + $("#business").val()).then(function successCallback(response) {
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
     $scope.sortBy = function(propertyName) {
      $scope.propertyName = propertyName;
      $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
      
          $scope.rows();
        
   }

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
    $scope.pdfCliente = function(code, business, id) {
        window.open($("#url").val() + "/pdf/cliente/" + code + "/" + business + "/" + id);
    }
    $scope.pdfTurno = function(code) {
        window.open($("#url").val() + "/pdf/turno/" + code);
    }
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
        }


        else{
          var fecha_inicial =document.getElementById("fecha_inicial").value;
          var fecha_final =document.getElementById("fecha_final").value;
            var url = $("#url").val() + "/excel/turnos/cliente/" + $("#us_id").val() + "/" + fecha_inicial + "/" + fecha_final + "/" + $("#status").val() + '/' + $("#type_shift-1").val() + '/' + $("#lenders-1").val() + '/' + $("#branch-1").val() + '/' + $("#business").val();
            window.open(url);
             }
        
    }
});

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
var date = new Date()
var day = new Date(date.getTime() + 24 * 60 * 60 * 1000);
$(function() {
    $('#fecha_inicial').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: day
    });
    $('#fecha_final').datetimepicker({
        format: 'DD-MM-YYYY',
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
app.controller('posts', function($scope, $http, $window) {
    $scope.titulo = "Registrá los días no laborables de la sucursal aquí:";
    $scope.btn = "Agregar";
    $scope.items_selected = '';
    $scope.rows = function() {
        $scope.items_selected = '';
        $("#baja-selec").hide();
        $("#alta-selec").hide();
        $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_holidays_branch/' + $("#select_branch").val()+'/' + $("#type").val()).then(function successCallback(response) {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.entryLimit = 20;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $(".bg-preload").hide();
            $(".bg-list").show();
        });
    }

    $scope.rows();

    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
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
    $scope.guardar = function() {
        if ($("#fer_desc").val() == "") {
            launch_toast("Ingresá un motivo");
            $("#fer_desc").focus();
        } else if ($("#fecha_inicial").val() == "") {
            launch_toast("Ingresá una fecha");
            $("#fecha_inicial").focus();
        } else {

            if ($("#id").val() == "") {
                $("#boton-1").prop('disabled', true);
                $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
                $.ajax({
                    url: $("#url").val() + "/store_branch",
                    type: 'POST',
                    dataType: 'json',
                    data: $("#form").serialize(),
                    success: function(data) {
                        if (data.response == "error") {
                            $("#boton-1").prop('disabled', false);
                            $("#boton-1").html('Guardar');
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Error",
                                message: data.msg
                            });
                        } else {
                            $('#form')[0].reset();
                            $scope.titulo = "Registrá los días no laborables de la sucursal aquí:";
                            $scope.btn = "Agregar";
                            $("#hasta").show();
                            swal("Su día no laborable fue registrado con éxito", {
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
                        $("#boton-1").html('Guardar');
                        $.growl.error({
                            title: "<i class='fa fa-exclamation-circle'></i> Error",
                            message: 'Ha ocurrido un error por favor intente más tarde'
                        });
                    }
                });

            } else {

                $("#boton-1").prop('disabled', true);
                $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
                $.ajax({
                    url: $("#url").val() + "/holidays/" + $("#id").val(),
                    type: 'PUT',
                    dataType: 'json',
                    data: $("#form").serialize(),
                    success: function(data) {
                        if (data.response == "error") {
                            $("#boton-1").prop('disabled', false);
                            $("#boton-1").html('Guardar');
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Error",
                                message: data.msg
                            });
                        } else {
                            $('#form')[0].reset();
                            $scope.titulo = "Registrá los días no laborables de la sucursal aquí:";
                            $scope.btn = "Agregar";
                            $("#hasta").show();
                            swal("Su día no laborable fue actualizado con éxito", {
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
                        $("#boton-1").html('Guardar');
                        $.growl.error({
                            title: "<i class='fa fa-exclamation-circle'></i> Error",
                            message: 'Ha ocurrido un error por favor intente más tarde'
                        });
                    }
                });

            }

        }
    }
    $scope.asignarValor = function(id, name, desde) {
        $("#id").val(id);
        $("#fer_desc").val(name);
        $("#fecha_inicial").val(desde);
        $("#hasta").hide();
        $scope.titulo = "Actualizá el día no laborable de la sucursal aquí:";
        $scope.btn = "Actualizar";
        $("html, body").animate({
            scrollTop: "0px"
        })
    }
    $scope.optCan = function(date) {
        swal("Confirmá que querés dar de baja el día no laborable", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/delete_holiday_branch',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            date: date,
                            branch: $("#select_branch").val()
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El día no laborable ha sido dado de baja"
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

    $scope.optAlta = function(date) {
        swal("Confirmá que querés dar de alta el día no laborable", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/alta_date_branch',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            date: date,
                            branch: $("#select_branch").val()
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El día no laborable ha sido dado de alta"
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
 $scope.borrar_all = function(id) {
        swal("Confirmá que querés dar de baja la selección", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/up_status_working_branch',
                        type: 'POST',
                        data:{ id: $scope.items_selected, status:'2',branch: $("#select_branch").val() },
                        dataType: 'json',
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "La selección ha sido dada de baja"
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


    $scope.alta_all = function(id) {
        swal("Confirmá que querés dar de alta la selección", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/up_status_working_branch',
                        type: 'POST',
                        data:{ id: $scope.items_selected, status:'1',branch: $("#select_branch").val() },
                        dataType: 'json',
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "La selección ha sido dado de alta"
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


      $scope.set_items = function(id) {
        $("#baja-selec").hide();
        $("#alta-selec").hide();
        var item = "," + $('#' + id).val();
        $scope.items_selected = $scope.items_selected.replace(item, '');
        if ($('#' + id).prop('checked')) {
            $scope.items_selected = $scope.items_selected + item;
        }
        if ($scope.items_selected == "") {
            $("#baja-selec").hide();
            $("#alta-selec").hide();
        } else {
            if ($("#type").val() == "1") {
                $("#baja-selec").show();
            }
            if ($("#type").val() == "2") {
                $("#alta-selec").show();
            }
        }
      
    }
});
function set_items(id) {
    angular.element($('[ng-controller="posts"]')).scope().set_items(id);
}
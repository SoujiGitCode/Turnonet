$(function() {
    $('#fecha_inicial').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: new Date()
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
    $scope.titulo = "Registrá las notas del prestador aquí:";
    $scope.btn = "Guardar";
    $scope.items_selected = '';
    $scope.rows = function() {
         $scope.items_selected = '';
        $("#baja-selec").hide();
        $("#alta-selec").hide();
        $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_blacklists/' + $("#business").val()+'/' + $("#type").val()).then(function successCallback(response) {
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
            if ($("#type").val() == 1) {
                $("#baja-selec").show();
            }
            if ($("#type").val() == 0) {
                $("#alta-selec").show();
            }
        }
      
    }
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
        var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if ($("#email").val() == "") {
        launch_toast("Ingresá tu correo electrónico/DNI");
        $("#email").focus();
        return false;
    }else {
            if ($("#id").val() == "") {

                $("#boton-1").prop('disabled', true);
                $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
                $.ajax({
                    url: $("#url").val() + "/blacklists",
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
                            $scope.titulo = "Registrá las notas del prestador aquí:";
                            $scope.btn = "Guardar";
                            swal("Su correo electrónico/DNI fue registrado con éxito", {
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
                    url: $("#url").val() + "/blacklists/" + $("#id").val(),
                    type: 'PUT',
                    dataType: 'json',
                    data: $("#form").serialize(),
                    success: function(data) {
                        if (data.response == "error") {
                            $("#boton-1").prop('disabled', false);
                            $("#boton-1").html('Actualizar');
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Error",
                                message: data.msg
                            });
                        } else {
                            $('#form')[0].reset();
                            $scope.titulo = "Registrá las notas del prestador aquí:";
                            $scope.btn = "Guardar";
                            swal("Su correo electrónico/DNI fue actualizado con éxito", {
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
                        $("#boton-1").html('Actualizar');
                        $.growl.error({
                            title: "<i class='fa fa-exclamation-circle'></i> Error",
                            message: 'Ha ocurrido un error por favor intente más tarde'
                        });
                    }
                });

            }
        }
    }

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
                        url: $("#url").val() + '/up_status_blacklists',
                        type: 'POST',
                        data:{ id: $scope.items_selected, status:'0' },
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
                        url: $("#url").val() + '/up_status_blacklists',
                        type: 'POST',
                        data:{ id: $scope.items_selected, status:'1' },
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

    $scope.asignarValor = function(id,email) {
        $("#id").val(id);
        $("#email").val(email);
        $scope.titulo = "Actualizá el correo electrónico/DNI aquí:";
        $scope.btn = "Actualizar";
        $("html, body").animate({
            scrollTop: "0px"
        })
    }
    $scope.optCan = function(id) {
        swal("Confirmá que querés dar de baja el correo electrónico/DNI", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/blacklists/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "el correo electrónico/DNI ha sido dada de baja"
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

    $scope.optAlta = function(id) {
        swal("Confirmá que querés dar de alta el correo electrónico/DNI", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/alta_blacklists',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "el correo electrónico/DNI ha sido dado de alta"
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
});

function set_items(id) {
    angular.element($('[ng-controller="posts"]')).scope().set_items(id);
}
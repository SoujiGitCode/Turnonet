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
    $scope.titulo = "Registrá el administrador aquí:";
    $scope.btn = "Guardar";
    $scope.items_selected = '';
    $scope.rows = function() {
        $scope.items_selected = '';
        $("#baja-selec").hide();
        $("#alta-selec").hide();
        $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_admins/' + $("#business").val() + "/" + $("#type").val()).then(function successCallback(response) {
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
        var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if ($("#name").val() == "") {
            launch_toast("Ingresá el nombre");
            $("#name").focus();
            return false;
        } else if ($('#dni').length > 0 && $('#dni').val() == "") {
            launch_toast("Ingresá el DNI");
            $('#dni').focus();
            return false;
        } else if ($("#email").val() == "") {
            launch_toast("Ingresá el correo electrónico");
            $("#email").focus();
            return false;
        } else if (!expr.test($("#email").val())) {
            launch_toast("Ingresá un correo electrónico válido");
            $("#email").focus();
            return false;
        } else if ($("#branch").val() == "") {
            launch_toast('Seleccioná una sucursal');
            $("#branch").focus();
            return (false);
        } else if ($("#password").val() == "") {
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
        } else {
            if ($("#id").val() == "") {

                $("#boton-1").prop('disabled', true);
                $("#boton-1").html('<i class="fa fa-circle-o-notch fa-spin"></i> Enviando');
                $.ajax({
                    url: $("#url").val() + "/admins",
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
                            $scope.titulo = "Registrá el administrador aquí:";
                            $scope.btn = "Guardar";
                            swal("Su administrador fue registrado con éxito", {
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
                    url: $("#url").val() + "/admins/" + $("#id").val(),
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
                            $scope.titulo = "Registrá el administrador aquí:";
                            $scope.btn = "Guardar";
                            swal("Su administrador fue actualizado con éxito", {
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
    $scope.asignarValor = function(id, name, email, password, id_rol, branch, lender) {



        $("#branch").val(branch);
        $('#lender').empty();
        $("#lender").append(`<option value=''> Seleccioná un prestador</option>`);
        $.get($("#url").val() + "/lista_branch/" + $("#branch").val() + "", function(res, sta) {
            res.forEach(element => {
                if ($('#select_lender').length != 0 && element.id == $('#select_lender').val()) {
                    $("#lender").append(`<option value=${element.id} selected="selected"> ${element.name} </option>`);
                } else {
                    $("#lender").append(`<option value=${element.id}> ${element.name} </option>`);
                }
            });

            $("#id").val(id);
            $("#name").val(name);
            $("#email").val(email);
            $("#password").val(password);
            $("#cpasswordr").val(password);

            if (lender == "0") {

                $("#lender").val('');
            } else {
                $("#lender").val(lender);
            }

            if (id_rol == 1) {
                $("#rol").attr('checked', true);

            } else {
                $("#rol").attr('checked', false);
            }
            $scope.titulo = "Actualizá el administrador aquí:";
            $scope.btn = "Actualizar";
            $("html, body").animate({
                scrollTop: "0px"
            })
        });

    }
    $scope.optCan = function(id) {
        swal("Confirmá que querés dar de baja al administrador", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/admins/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "el administrador ha sido dado de baja"
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
        swal("Confirmá que querés dar de alta al administrador", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/alta_admin',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El administrador ha sido dado de alta"
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
                        url: $("#url").val() + '/up_status_admin',
                        type: 'POST',
                        data:{ id: $scope.items_selected, status:'BAJA' },
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
                        url: $("#url").val() + '/up_status_admin',
                        type: 'POST',
                        data:{ id: $scope.items_selected, status:'ALTA' },
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
            if ($("#type").val() == "ALTA") {
                $("#baja-selec").show();
            }
            if ($("#type").val() == "BAJA") {
                $("#alta-selec").show();
            }
        }
      
    }
});


function changeSucursal() {

    $('#lender').empty();
    $("#lender").append(`<option value=''> Seleccioná un prestador</option>`);
    if ($("#branch").val() != "") {

        $.get($("#url").val() + "/lista_branch/" + $("#branch").val() + "", function(res, sta) {
            res.forEach(element => {
                if ($('#select_lender').length != 0 && element.id == $('#select_lender').val()) {
                    $("#lender").append(`<option value=${element.id} selected="selected"> ${element.name} </option>`);
                } else {
                    $("#lender").append(`<option value=${element.id}> ${element.name} </option>`);
                }
            });


        });
    }

}

function changeEmpresa() {

    $('#branch').empty();
    $("#branch").append(`<option value=''> Seleccioná una sucursal</option>`);

    if ($("#business").val() != "") {

        $.get($("#url").val() + "/lists_branch_business/" + $("#business").val() + "", function(res, sta) {
            res.forEach(element => {
                if ($('#select_branch').length != 0 && element.id == $('#select_branch').val()) {
                    $("#branch").append(`<option value=${element.id} selected="selected"> ${element.name} </option>`);
                } else {
                    $("#branch").append(`<option value=${element.id}> ${element.name} </option>`);
                }
            });


        });
    }

}

changeEmpresa();

function set_items(id) {
    angular.element($('[ng-controller="posts"]')).scope().set_items(id);
}
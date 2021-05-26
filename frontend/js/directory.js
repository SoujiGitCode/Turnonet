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
    $scope.predicate = 'name';
    $scope.reverse = false;
    $scope.items_selected = '';
    $scope.rows = function() {
        $scope.items_selected = '';
        $("#baja-selec").hide();
        $("#alta-selec").hide();
        $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_directory/' + $("#business").val()+'/' + $("#type").val()).then(function successCallback(response) {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.entryLimit = 50;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $(".bg-preload").hide();
            $(".bg-list").show();
        });
    }
    $scope.rows();
    //cambiar numero de pagina
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    // filtrar paginas
    $scope.filtro = function() {
        if ($("#search").val() == '') {
            $scope.rows();
            $(".pagination").show();
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
        } else {

             $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_directory_search/'+$("#search").val()+'/' + $("#business").val()+'/' + $("#type").val()).then(function successCallback(response) {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.entryLimit = 50;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $(".bg-preload").hide();
            $(".bg-list").show();
        });
            $(".pagination").hide();
            $scope.filteredItems = $scope.filtered.length;
            $scope.totalItems = $scope.filtered.length;
        }
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
    $scope.goTo = function(code) {
        window.open($("#url").val() + '/directorio/' + code+'/editar');
    };

    $scope.goToShift = function(business,user) {
       window.open($("#url").val() + '/agenda/cliente/' + business +'/'+user);
    };

    $scope.call = function(phone) {
        window.open('tel:'+phone);
    };


    $scope.viewData = function(us_id) {
        $("#lodp").html('');
    if (us_id != "") {
        $.get($("#url").val() + "/get_user_last_data/" + us_id, function(res, sta) {
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


            response +='<div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Los datos adicionales del cliente fueron actualizados al momento de tomar el turno</div>';

           
            $("#lodp").html(response);
            $("#myModalp").modal('show');
        });
    }
    };

    $scope.call_mail = function(email) {
        window.open('mailto:'+email);
    };

    $scope.optCan = function(id) {
        swal("Confirmá que querés dar de baja al cliente", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/delete_user',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El cliente ha sido dado de baja"
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


    $scope.goLists = function(email) {
        swal("Confirmá que querés agregar al cliente a la lista negra", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/blacklists',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            emp_id: $("#business").val(),
                            email:email
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El cliente ha sido agregado a la lista negra"
                            });
                            
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

    $scope.OpemModalPas=function(id) {
    actModalPac(id);
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
                        url: $("#url").val() + '/up_status_directory',
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
                        url: $("#url").val() + '/up_status_directory',
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
    
     $scope.optAlta = function(id) {
        swal("Confirmá que querés dar de alta al cliente", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/alta_user',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El cliente ha sido dado de alta"
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


function removeDuplicates(arrayIn) {
    var arrayOut = [];
    arrayIn.forEach(item=> {
      try {
        if (JSON.stringify(arrayOut[arrayOut.length-1].zone) !== JSON.stringify(item.zone)) {
          arrayOut.push(item);
        }
      } catch(err) {
        arrayOut.push(item);
       }
    })
    return arrayOut;
}

function enter_filtro(e) {
    if (e.keyCode == 13) {
        filtro();
    }
}

function filtro() {
    angular.element($('[ng-controller="post"]')).scope().filtro();
}
$("#search").keyup(function() {
    if (this.value.length > 1) {
       angular.element($('[ng-controller="post"]')).scope().filtro();
    }
});

function set_items(id) {
    angular.element($('[ng-controller="post"]')).scope().set_items(id);
}
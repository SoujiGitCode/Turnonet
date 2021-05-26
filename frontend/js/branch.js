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
    $scope.rows = function() {
        $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_branch/'+$("#business").val()+"/"+$("#type").val()).then(function successCallback(response) {
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


    $scope.goLenders = function(id) {
         window.location=$("#url").val()+"/sucursal/prestadores/"+id;
    };

    $scope.goAgenda = function(code) {
        window.location = $("#url").val() + '/agenda/sucursal/' + code+'';
    };
    $scope.gocreateLender = function(id) {

       
 window.location=$("#url").val()+"/prestador/nuevo/"+id;
    }
 $scope.goEdit = function(id) {

        window.location=$("#url").val()+"/sucursal/editar/"+id;

    };

     $scope.goToConfig= function(code) {
        window.location = $("#url").val() + '/sucursal/configuracion/' + code+'';
    };

   
    $scope.goToShedules= function(code) {
        window.location = $("#url").val() + '/sucursal/horarios/' + code+'';
    };

     $scope.optAlta = function(id) {
        swal("Confirmá que querés dar de alta la sucursal", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/alta_branch',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "La sucursal ha sido dado de alta"
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


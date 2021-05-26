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
    $scope.rows = function() {
        $(".bg-preload").show();
        $(".bg-list").hide();
        $http.get($("#url").val() + '/lists_lenders_branch/'+$("#branch").val()+"/"+$("#type").val()).then(function successCallback(response) {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.entryLimit = 50;
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
            $(".bg-preload").hide();
            $(".bg-list").show();
        });
    }
    //cambiar numero de pagina
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    // filtrar paginas
    $scope.filter = function() {
        if ($("#search").val() == '') {
            $(".bg-preload").hide();
            $(".bg-list").show();
            $(".pagination").show();
            $scope.filteredItems = $scope.list.length;
            $scope.totalItems = $scope.list.length;
        } else {
            show_loader();
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
        window.location = $("#url").val() + '/prestador/editar/' + code+'';
    };
    $scope.goAgenda = function(code) {
        window.location = $("#url").val() + '/agenda/prestador/' + code+'';
    };

    $scope.goToConfig= function(code) {
        window.location = $("#url").val() + '/prestador/configuracion/' + code+'';
    };

    $scope.goToSpeciality= function(code) {
        window.location = $("#url").val() + '/prestador/especialidades/' + code+'';
    };

    $scope.goToShedules= function(code) {
        window.location = $("#url").val() + '/prestador/horarios/' + code+'';
    };

    $scope.call = function(phone) {
        window.open('tel:'+phone);
    };

    $scope.call_mail = function(email) {
        window.open('mailto:'+email);
    };

     $scope.optAlta = function(id) {
        swal("Confirmá que querés dar de alta el prestador", {
                buttons: {
                    cancel: "No",
                    confirm: "Si"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: $("#url").val() + '/alta_lender',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function() {
                            $.growl.error({
                                title: "<i class='fa fa-exclamation-circle'></i> Atención",
                                message: "El prestador ha sido dado de alta"
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

$('#navsm').sortable({
    revert: true,
    opaitem: 0.6,
    cursor: 'move',
    update: function() {
        var order = $('#navsm').sortable("serialize") + '&action=orderState';
        $.post($("#url").val()+"/move-lender", order, function(theResponse) {
            window.location.reload();
        });
    }
});
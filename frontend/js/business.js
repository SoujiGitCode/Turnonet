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
        $http.get($("#url").val() + '/lists_bussiness').then(function successCallback(response) {
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

     $scope.goAgenda = function(id) {

        window.location=$("#url").val()+"/agenda/empresa/"+id;

    };

    $scope.goBranch = function() {

        window.location=$("#url").val()+"/sucursales";

    };

     $scope.goToConfig= function(code) {
        window.location = $("#url").val() + '/empresa/configuracion/' + code+'';
    };

   
    $scope.goToShedules= function(code) {
        window.location = $("#url").val() + '/empresa/horarios/' + code+'';
    };

      $scope.goLenders = function() {

        window.location=$("#url").val()+"/prestadores";

    };
     $scope.goEdit = function(id) {

        window.location=$("#url").val()+"/empresa/editar/"+id;

    };
    $scope.goDirectorio = function(id) {

        window.location=$("#url").val()+"/directorio/empresa/"+id;

    };
    
});


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

        if($("#espid").length>0){
            var  espid=$("#espid").val();

        }else{
            var  espid="ALL";
        }

        if($("#sucid").length>0){
            var  sucid=$("#sucid").val();

        }else{
            var  sucid="ALL";
        }

        $http.get($("#url").val() + '/lista_prestadores/'+$("#business").val()+"/"+espid+"/"+sucid).then(function successCallback(response) {
            $scope.list = response.data;
            $scope.currentPage = 1;
            $scope.entryLimit = $("#limit").val();
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
    $scope.goTo = function(url) {
        window.location = $("#url").val() + '/'+$("#url_business").val()+"/disponibilidad/"+url;
    };

    $scope.call = function(phone) {
        window.open('tel:'+phone);
    };

    $scope.call_mail = function(email) {
        window.open('mailto:'+email);
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
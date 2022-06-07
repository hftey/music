'use strict';

var app = angular.module('myApp.edit', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/edit', {
    templateUrl: 'edit/edit.html',
    controller: 'EditCtrl'
  });
}])

app.controller('EditCtrl', function($scope, $http, $window, $routeParams) {
    var id = $routeParams.id;
    $scope.id = id;
    $scope.init = function () {
        if (id){
            $http.get("http://testcd.localhost/cds/"+id).then(function(response) {
                console.log(response.data.records[0]);
                $scope.fields = response.data.records[0];
            });

        }
    }


    $scope.submitMyForm=function(){
        /* while compiling form , angular created this object*/
        var data=$scope.fields;

        console.log(data);
        if (id){
            $http.put("http://testcd.localhost/cds/"+id, data).then(function(response) {

                if (response.data.status == 'ok'){
                    $window.location.href = '/#!/dashboard';
                }
            },
            function(response){
                console.log('Error', response);
            });

        }else{
            $http.post("http://testcd.localhost/cds", data).then(function(response) {

                if (response.data.status == 'ok'){
                    $window.location.href = '/#!/dashboard';
                }
            },
            function(response){
                console.log('Error', response);
            });

        }

    }

    $scope.cancelAdd = function(index){
        $window.location.href = '/#!/dashboard';
    };

    $scope.delete = function(index){
        $http.delete("http://testcd.localhost/cds/"+id).then(function(response) {

                if (response.data.status == 'ok'){
                    $window.location.href = '/#!/dashboard';
                }
            },
            function(response){
                console.log('Error', response);
            });
    };

    $scope.init();
});
'use strict';

var app = angular.module('myApp.dashboard', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/dashboard', {
    templateUrl: 'dashboard/dashboard.html',
    controller: 'DashboardCtrl'
  });
}])

app.controller('DashboardCtrl', function($scope, $http, $window) {
    $scope.music_list = [];

    $scope.init = function () {
        $http.get("http://testcd.localhost/cds").then(function(response) {
            console.log(response);
            $scope.music_list = response.data.records;
        });
    }

    $scope.editItem = function(id){
        $window.location.href = '/#!/edit/'+id;
    };

    $scope.addItem = function(){
        $window.location.href = '/#!/edit';
    };

    $scope.init();



});
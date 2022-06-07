'use strict';

// Declare app level module which depends on views, and core components
angular.module('myApp', [
  'ngRoute',
  'myApp.dashboard',
  'myApp.edit',
  'myApp.version'
]).
config(['$locationProvider', '$routeProvider', function($locationProvider, $routeProvider) {

  $routeProvider.otherwise({redirectTo: '/dashboard'});

  $routeProvider.when('/edit/:id', {
      templateUrl: 'edit/edit.html',
      controller: 'EditCtrl'
  });

}]);

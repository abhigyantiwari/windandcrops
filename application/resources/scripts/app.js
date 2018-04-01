var windandcrops = angular.module('windandcrops', ['ngRoute']);
windandcrops.config(function($routeProvider, $locationProvider){
	$routeProvider.when('/', {
		templateUrl: 'application/views/splash.view.html',
		controller: 'splashController'
	})
	.when('/account', {
		templateUrl: 'application/views/account.view.html',
		controller: 'accountController'
	})
	.when('/profile', {
		templateUrl: 'application/views/profile.view.html',
		controller: 'profileController'
	});
	$locationProvider.html5Mode(true);
});

var BASE_URI = "http://localhost:8000/";

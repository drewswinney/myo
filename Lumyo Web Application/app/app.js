var MyoSleepApp = angular.module('MyoSleepApp', ['ngRoute']);

// configure our routes
MyoSleepApp.config(function($routeProvider) {
    $routeProvider

        // route for the home page
        .when('/', {
            templateUrl : 'app/components/blank/blank.php',
            controller  : 'blankController'
        })

        // route for the about page
        .when('/dashboard', {
            templateUrl : 'app/components/dashboard/dashboard.php',
            controller  : 'dashboardController'
        })

        // route for the about page
        .when('/sleepdata', {
            templateUrl : 'app/components/sleepdata/sleepdata.php',
            controller  : 'sleepdataController'
        })

        // route for the about page
        .when('/account', {
            templateUrl : 'app/components/account/account.php',
            controller  : 'accountController'
        })
});

// create the controller and inject Angular's $scope
MyoSleepApp.controller('blankController', function($scope) {
    // Use JQuery to call the RESTful API and get account information
});

// create the controller and inject Angular's $scope
MyoSleepApp.controller('sleepdataController', function($scope) {
    // Use JQuery to call the RESTful API and get account information
    
});

// create the controller and inject Angular's $scope
MyoSleepApp.controller('dashboardController', function($scope) {
    // Use JQuery to call the RESTful API and get dashboard information
});

// create the controller and inject Angular's $scope
MyoSleepApp.controller('accountController', function($scope) {
    // Use JQuery to call the RESTful API and get account information
    
});
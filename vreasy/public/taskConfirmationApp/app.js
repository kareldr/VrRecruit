angular.module('taskConfirmationApp',  ['ui.router', 'ngResource'])
.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    // Use hashtags in URL
    $locationProvider.html5Mode(false);

    $urlRouterProvider.otherwise("/");

    $stateProvider
    .state('index', {
      url: "/",
      templateUrl: "/taskConfirmationApp/templates/index.html",
      controller: 'IndexCtrl'
    })
    .state('tasks', {
      url: "/tasks",
      templateUrl: "/taskConfirmationApp/templates/tasks.html",
      controller: 'TaskCtrl'
    });
})
.factory('Task', function($resource) {
    return $resource('/task/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET'},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})
.factory('Taskstate', function($resource) {
    return $resource('/taskstate/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET'},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})
.controller('TaskCtrl', function($scope, Task, Taskstate) {
    $scope.tasks = Task.query();
    $scope.taskstates = Taskstate.query();
})
.controller('IndexCtrl', function($scope) {
});

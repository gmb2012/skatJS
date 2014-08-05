'use strict';

angular.module('skatJS', ['skatJS-Controller', 'ngRoute'])
    .config(['$routeProvider', function($routeProvider) {
        $routeProvider
            .when('/', {
                templateUrl: 'views/dashboard.html',
                controller: 'DashboardController'
            })
            .when('/newMatch', {
                templateUrl: 'views/newMatch.html',
                controller: 'NewMatchController'
            })
            .when('/newPlayer', {
                templateUrl: 'views/newPlayer.html',
                controller: 'PlayersController'
            })
            .when('/ranking', {
                templateUrl: 'views/ranking.html',
                controller: 'RankingController'
            })
            .when('/matches', {
                templateUrl: 'views/matches.html',
                controller: 'MatchesController'
            })
            .when('/matches/:id', {
                templateUrl: 'views/match.html',
                controller: 'MatchController'
            })
            .otherwise({
                redirectTo: '/'
            });
    }]);
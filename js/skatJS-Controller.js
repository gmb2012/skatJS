'use strict';

angular.module('skatJS-Controller', ['skatJS-Service', 'skatJS-Util'])
    .controller('MenuController', ['$scope', '$location', function($scope, $location) {
        $scope.isActive = function(route) {
            return route === $location.path();
        };
    }])
    .controller('DashboardController', ['$scope', function($scope) {
        // games of the last week
        // top 5 players
    }])
    .controller('NewMatchController', ['$scope', 'PlayerService', 'PlayerUtil', 'MatchService', function($scope, playerService, playerUtil, matchService) {
        $scope.players = playerService.getAll();
        $scope.selectedPlayers = [];

        $scope.addPlayers = function() {
            var switchedLists = playerUtil.switch($scope.players, $scope.selectedPlayers, $scope.allPlayersSelected);

            // set lists
            $scope.players = switchedLists.from;
            $scope.selectedPlayers = switchedLists.to;
            $scope.players.sort(playerUtil.sort);

            // remove selection
            $scope.allPlayersSelected = [];

        }

        $scope.removePlayers = function() {
            var switchedLists = playerUtil.switch($scope.selectedPlayers, $scope.players, $scope.selectedPlayersSelected);

            // set lists
            $scope.selectedPlayers = switchedLists.from;
            $scope.players = switchedLists.to;
            $scope.players.sort(playerUtil.sort);

            // remove selection
            $scope.selectedPlayersSelected = [];
        }

        $scope.startGame = function() {
            var playerIds = [];
            angular.forEach($scope.selectedPlayers, function(value, key) {
                playerIds.push(value.id);
            });

            matchService.add({ players: playerIds });
            alert('REDIRECT MISSING');
        }

    }])
    .controller('PlayersController', ['$scope', 'PlayerService', function($scope, playerService) {
        $scope.updatePlayers = function() {
            $scope.items = playerService.getAll();
        }

        $scope.updatePlayers();
    }])
    .controller('NewPlayerController', ['$scope', 'PlayerService', function($scope, playerService) {
        $scope.status = '';
        $scope.name = '';
        $scope.add = function(name) {
            var ret = playerService.add({ name: name });

            ret.$promise.then(function(data) {
                $scope.status = 'success';
                $scope.name = '';
                $scope.updatePlayers();
            }, function(error) {
                $scope.status = 'error';
            })
        }
    }])
    .controller('RankingController', ['$scope', function($scope) {

    }])
    .controller('MatchesController', ['$scope', 'PlayerService', 'PlayerUtil', 'MatchService', function($scope, playerService, playerUtil, matchService) {
        playerService.getAll().$promise.then(function(items) {
            var players = playerUtil.idMapping(items);

            // get all matches
            $scope.matches = [];
            matchService.getAll().$promise.then(function(items) {
                angular.forEach(items, function(value, key) {
                    var matchPlayers = [];
                    angular.forEach(value.players, function(value, key) {
                        matchPlayers.push(players[value]);
                    });

                    $scope.matches.push({ id: value.id, date: value.date, players: matchPlayers });
                });
            });
        });
    }])
    .controller('MatchController', ['$scope', '$routeParams', 'PlayerService', 'PlayerUtil', 'MatchService', function($scope, $routeParams, playerService, playerUtil, matchService) {
        $scope.matchId = $routeParams.id;
        $scope.scores = {};
        playerService.getAll().$promise.then(function(players) {
            players = playerUtil.idMapping(players);

            // get all matches
            matchService.getById($routeParams.id).$promise.then(function(match) {
                $scope.date = match.date;
                $scope.players = [];

                angular.forEach(match.players, function(value, key) {
                    $scope.players.push({ id: value, name: players[value] });
                    $scope.scores[value] = 0;
                });
            });
        });
    }])
    .controller('GamesController', ['$scope', 'GameService', function($scope, gameService) {
        gameService.getByMatchId($scope.matchId).$promise.then(function(games) {
            console.log(games);
        });
    }])
    .controller('NewGameController', ['$scope', 'GameService', function($scope, gameService) {
        $scope.add = function() {
            console.log('addCalled');
            var scores = [];
            angular.forEach($scope.scores, function(value, key) {
                scores.push({ id: key, score: value });
            });

            gameService.add({ match: $scope.matchId, scores: scores, gameScore: $scope.score, bockCount: 0, ramschCount: 0}).$promise.then(function() {
                window.alert('Refresh List & Empty Form');
            });
        }

        $scope.noScoreEntered = function() {
            var scores = 0;
            angular.forEach($scope.scores, function(value, key) {
                scores += value;
            });

            if(scores != 0 && angular.isDefined($scope.score) && $scope.score != 0) {
                return false;
            } else {
                return true;
            }
        }
    }]);
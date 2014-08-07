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
    .controller('NewMatchController', ['$scope', '$location', 'PlayerService', 'PlayerUtil', 'MatchService', function($scope, $location, playerService, playerUtil, matchService) {
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

            matchService.add({ players: playerIds }).$promise.then(function(match) {
                $location.path('/matches/' + match.id);
            });
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
    .controller('MatchController', ['$scope', '$routeParams', 'PlayerService', 'PlayerUtil', 'MatchService', 'GameService', function($scope, $routeParams, playerService, playerUtil, matchService, gameService) {
        $scope.matchId = $routeParams.id;
        $scope.newScores = {};
        $scope.newScore = 0;

        $scope.scores = {};
        $scope.getGames = function() {
            // init score table for the players
            var playerScores = {};
            angular.forEach($scope.players, function(value, key) {
                playerScores[value.id] = 0;
            });

            gameService.getByMatchId($scope.matchId).$promise.then(function(games) {
                angular.forEach(games, function(game, key) {
                    var gameScores = {};
                    angular.forEach(game.scores, function(value, key) {
                        gameScores[value.id] = value.score;
                    });

                    // assign scores to the players
                    var score = { players: [], game: game.gameScore };
                    angular.forEach($scope.players, function(value, key) {
                        var displayScore = playerScores[value.id] != playerScores[value.id] + gameScores[value.id]; // display changed score
                        playerScores[value.id] += gameScores[value.id]; // calculate current score

                        score.players.push({ id: value.id, score: playerScores[value.id], display: displayScore });
                    });

                    $scope.scores[game.id] = score;
                });
            });
        }

        playerService.getAll().$promise.then(function(players) {
            players = playerUtil.idMapping(players);

            // get match
            matchService.getById($routeParams.id).$promise.then(function(match) {
                $scope.date = match.date;
                $scope.players = [];

                angular.forEach(match.players, function(value, key) {
                    $scope.players.push({ id: value, name: players[value] });
                    $scope.newScores[value] = 0;
                });

                // get the games
                $scope.getGames();
            });
        });
    }])
    .controller('NewGameController', ['$scope', 'GameService', function($scope, gameService) {
        $scope.add = function() {
            var scores = [];
            angular.forEach($scope.newScores, function(value, key) {
                scores.push({ id: key, score: value });
            });

            gameService.add({ match: $scope.matchId, scores: scores, gameScore: $scope.newScore, bockCount: 0, ramschCount: 0}).$promise.then(function() {
                $scope.getGames();

                $scope.newScore = 0;
                angular.forEach($scope.newScores, function(value, key) {
                    $scope.newScores[key] = 0;
                });
            });
        }

        // show submit button
        $scope.noScoreEntered = function() {
            var scores = 0;
            angular.forEach($scope.newScores, function(value, key) {
                scores += value;
            });

            if(scores != 0 && angular.isDefined($scope.newScore) && $scope.newScore != 0) {
                return false;
            } else {
                return true;
            }
        }
    }]);
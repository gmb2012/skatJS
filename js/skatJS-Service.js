'use strict';

angular.module('skatJS-Service', ['ngResource'])
    .factory('ResourceService', ['$resource', function($resource) {
        return {
            makeService: function(baseURL) {
                return {
                    getById: function(id) {
                        return $resource(baseURL + id).get();
                    },
                    getAll: function() {
                        return $resource(baseURL).query();
                    },
                    add: function(player) {
                        return $resource(baseURL + 'new').save(player);
                    }
                }
            }
        };
    }])
    .factory('PlayerService', ['ResourceService', function(resourceService) {
        return resourceService.makeService('/backend/players/');
    }])
    .factory('MatchService', ['ResourceService', '$resource', function(resourceService, $resource) {
        var service = resourceService.makeService('/backend/matches/');

        service.getStatsById = function(id) {
            return $resource('/backend/matches/stats/' + id).get();
        }

        return service;

    }])
    .factory('GameService', ['ResourceService', '$resource', function(resourceService, $resource) {
        var service = resourceService.makeService('/backend/games/');

        service.getByMatchId = function(matchId) {
            return $resource('/backend/games/byMatch/' + matchId).query();
        }

        return service;
    }])
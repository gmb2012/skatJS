'use strict';

angular.module('skatJS-Util', [])
    .factory('PlayerUtil', [function() {
        return {
            sortByName: function(a, b) {
                var nameA = a.name.toLowerCase();
                var nameB = b.name.toLowerCase();

                if (nameA < nameB)      { return -1 }
                else if (nameA > nameB) { return 1 }
                else                    { return 0 }
            },
            switch: function(from, to, ids) {
                var fromNew = [];
                angular.forEach(from, function(value, key) {
                    if(ids.indexOf(value.id) != -1) {
                        to.push(value);
                    } else {
                        fromNew.push(value);
                    }
                });

                return { 'from': fromNew, 'to': to }
            },
            idMapping: function(players) {
                var returnValue = {};

                angular.forEach(players, function(value, key) {
                    returnValue[value.id] = value.name;
                });

                return returnValue;
            }
        }
    }]);
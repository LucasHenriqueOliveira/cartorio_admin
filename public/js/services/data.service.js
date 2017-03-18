(function () {
    'use strict';

    angular
        .module('app')
        .factory('DataService', DataService);

    DataService.$inject = ['$http', '$q', 'App'];

    function DataService($http, $q, App) {

        return {
            getDashboard: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'dashboard',
                    params: params
                })
                .then(function(response) {

                    deferred.resolve(response.data);

                }, function(error) {
                    console.log(error);
                });

                return deferred.promise;
            }
        }
    }
})();
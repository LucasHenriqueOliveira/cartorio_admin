(function () {
    'use strict';

    angular
        .module('app')
        .factory('App', App);

    App.$inject = ['$localStorage'];

    function App($localStorage) {

        function clearData() {
            $localStorage.destroy('token');
            $localStorage.destroy('user');
            $localStorage.destroy('sources');
            $localStorage.destroy('publishers');
        }

        return {
            api: 'api/',
            user: false,
            token: null,
            publishers: [],
            sources: [],
            clearData: clearData
        };

    }
})();
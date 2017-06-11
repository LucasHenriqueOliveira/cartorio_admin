(function () {
    'use strict';

    angular
        .module('app')
        .controller('UsuarioController', UsuarioController);

    UsuarioController.$inject = ['$scope', '$location', 'DataService', 'App', '$localStorage'];

    function UsuarioController($scope, $location, DataService, App, $localStorage) {

    }

})();
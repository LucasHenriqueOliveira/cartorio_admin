(function () {
    'use strict';

    angular
        .module('app')
        .controller('RelatoriosController', RelatoriosController);

    RelatoriosController.$inject = ['$scope', 'DataService'];

    function RelatoriosController($scope, DataService) {

        DataService.getProdutos().then(function(response) {
            $scope.produtos = response;
        }, function (error) {
            toastr.error('Erro ao buscar os produtos', 'Produto', {timeOut: 3000});
        });

        DataService.getFornecedores().then(function(response) {
            $scope.fornecedores = response;
        }, function (error) {
            toastr.error('Erro ao buscar os fornecedores', 'Fornecedor', {timeOut: 3000});
        });
    }

})();
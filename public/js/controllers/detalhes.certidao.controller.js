(function () {
    'use strict';

    angular
        .module('app')
        .controller('DetalhesCertidaoController', DetalhesCertidaoController);

    DetalhesCertidaoController.$inject = ['$scope', 'DataService', 'App'];

    function DetalhesCertidaoController($scope, DataService, App) {
        $scope.hasAddMovimentacao = false;
        $scope.descricao = '';
        $scope.certidao = App.getCurrentCertidao();

        $scope.movimentar = function() {
            $scope.hasAddMovimentacao = true;
        };

        $scope.addDescricao = function(descricao) {
            var d = new Date();
            var datestring = d.getDate()  + "/" + (d.getMonth()+1) + "/" + d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes();
            var movimentacao = {
                numero: $scope.certidao.movimentacoes.length + 1,
                descricao: descricao,
                usuario: App.user.nome,
                data: datestring
            };
            $scope.certidao.movimentacoes.push(movimentacao);
            $scope.descricao = '';
        };
    }

})();
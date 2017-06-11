(function () {
    'use strict';

    angular
        .module('app')
        .controller('DetalhesProcuracaoController', DetalhesProcuracaoController);

    DetalhesProcuracaoController.$inject = ['$scope', 'DataService', 'App'];

    function DetalhesProcuracaoController($scope, DataService, App) {
        $scope.hasAddMovimentacao = false;
        $scope.descricao = '';
        $scope.procuracao = App.getCurrentProcuracao();

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
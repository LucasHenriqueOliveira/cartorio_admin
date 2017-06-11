(function () {
    'use strict';

    angular
        .module('app')
        .controller('TestamentoController', TestamentoController);

    TestamentoController.$inject = ['$scope', '$location', 'App'];

    function TestamentoController($scope, $location, App) {

        $scope.testamentos = [{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            pedido_por: 'Lucas',
            status: 'Aguardando'
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            pedido_por: 'Lucas',
            status: 'Em análise'
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            pedido_por: 'Lucas',
            status: 'Pronto'
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            pedido_por: 'Lucas',
            status: 'Entregue'
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            pedido_por: 'Lucas',
            status: 'Em análise'
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            pedido_por: 'Lucas',
            status: 'Aguardando'
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            pedido_por: 'Lucas',
            status: 'Entregue'
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            pedido_por: 'Lucas',
            status: 'Pronto'
        }];

        jQuery(document).ready(function(){
            $('table.display').DataTable( {
                "aaSorting": []
            } );
        });

        $scope.detalhesTestamento = function(testamento) {
            testamento.proximo_passo = '';
            switch (testamento.status) {
                case 'Aguardando':
                    testamento.proximo_passo = "Iniciar Análise";
                    break;
                case 'Em análise':
                    testamento.proximo_passo = "Documento pronto";
                    break;
                case 'Pronto':
                    testamento.proximo_passo = "Realizar a entrega";
                    break;
            }

            App.setCurrentProcuracao(testamento);

            $location.path('/detalhes-testamento');
        };
    }

})();
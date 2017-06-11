(function () {
    'use strict';

    angular
        .module('app')
        .controller('ProcuracaoController', ProcuracaoController);

    ProcuracaoController.$inject = ['$scope', '$location', 'App'];

    function ProcuracaoController($scope, $location, App) {

        $scope.procuracoes = [{
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
            status: 'Em análise'
        }];

        jQuery(document).ready(function(){
            $('table.display').DataTable( {
                "aaSorting": []
            } );
        });

        $scope.detalhesProcuracao = function(procuracao) {
            procuracao.proximo_passo = '';
            switch (procuracao.status) {
                case 'Aguardando':
                    procuracao.proximo_passo = "Iniciar Análise";
                    break;
                case 'Em análise':
                    procuracao.proximo_passo = "Documento pronto";
                    break;
                case 'Pronto':
                    procuracao.proximo_passo = "Realizar a entrega";
                    break;
            }

            App.setCurrentProcuracao(procuracao);

            $location.path('/detalhes-procuracao');
        };
    }

})();
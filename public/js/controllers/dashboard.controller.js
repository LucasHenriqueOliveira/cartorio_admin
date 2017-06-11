(function () {
    'use strict';

    angular
        .module('app')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['$scope', '$rootScope', 'DataService', 'App', '$location'];

    function DashboardController($scope, $rootScope, DataService, App, $location) {

        $scope.certidoes = [{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            cpf: '072.430.124-43',
            pedido_por: 'Lucas',
            tipo: 'Escritura',
            livro: '35B',
            ato: 'ADC',
            outorgante: 'Lucas Henrique',
            outorgado: 'Tiago Ferreira',
            data_hora: '20/04/2017 09:10',
            status: 'Aguardando',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            }]
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            cpf: '072.430.124-43',
            pedido_por: 'Lucas',
            tipo: 'Escritura',
            livro: '35B',
            ato: 'ADC',
            outorgante: 'Lucas Henrique',
            outorgado: 'Tiago Ferreira',
            data_hora: '20/04/2017 09:10',
            status: 'Aguardando',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            }]
        },{
            nome: 'Lucas Henrique',
            telefone: '31992833234',
            email: 'lucas@gmail.com',
            rg: 'MG-12.983.328',
            cpf: '072.430.124-43',
            pedido_por: 'Lucas',
            tipo: 'Escritura',
            livro: '35B',
            ato: 'ADC',
            outorgante: 'Lucas Henrique',
            outorgado: 'Tiago Ferreira',
            data_hora: '20/04/2017 09:10',
            status: 'Aguardando',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            }]
        }];

        $scope.procuracoes = $scope.certidoes;
        $scope.testamentos = $scope.certidoes;

        $scope.mes = {};
        $scope.mes.certidao = 1;
        $scope.mes.procuracao = 2;
        $scope.mes.testamento = 3;
        $scope.mes.total = 6;

        $scope.detalhesCertidao = function(certidao) {
            certidao.proximo_passo = '';
            switch (certidao.status) {
                case 'Aguardando':
                    certidao.proximo_passo = "Iniciar Análise";
                    break;
                case 'Em análise':
                    certidao.proximo_passo = "Documento pronto";
                    break;
                case 'Pronto':
                    certidao.proximo_passo = "Realizar a entrega";
                    break;
            }

            App.setCurrentCertidao(certidao);

            $location.path('/detalhes-certidao');
        };

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

        //var getDashboard = function() {
        //    DataService.getDashboard($scope.query).then(function(response) {
        //        if(response.error === false) {
        //            $scope.produtos = response.estatisticas.produtos;
        //            $scope.fornecedores = response.estatisticas.fornecedores;
        //            $scope.nf_entrada_valor = parseFloat(response.estatisticas.nf_entrada_valor).toLocaleString('pt-BR');
        //            $scope.nf_entrada = response.estatisticas.nf_entrada;
        //            $scope.notas = response.notas;
        //
        //            // notas de entrada
        //            $scope.labels = [];
        //            $scope.data = [];
        //            if($scope.notas) {
        //                $scope.notas.forEach(function(entry){
        //                    $scope.labels.push(entry.data);
        //                    $scope.data.push(parseInt(entry.nota));
        //                });
        //            }
        //
        //            $scope.data = [$scope.data];
        //            $scope.series = ['Notas de Entrada'];
        //            $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }];
        //            $scope.options = options;
        //
        //        } else {
        //            $scope.message = response.message;
        //        }
        //    });
        //};
        //
        //getDashboard();
    }

})();
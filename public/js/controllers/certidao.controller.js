(function () {
    'use strict';

    angular
        .module('app')
        .controller('CertidaoController', CertidaoController);

    CertidaoController.$inject = ['$scope', '$location', 'App'];

    function CertidaoController($scope, $location, App) {
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
            status: 'Em análise',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            },{
                numero: 2,
                descricao: 'Análise iniciada',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
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
            status: 'Pronto',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            },{
                numero: 2,
                descricao: 'Análise iniciada',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
            },{
                numero: 3,
                descricao: 'Documento Pronto',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
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
            status: 'Entregue',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            },{
                numero: 2,
                descricao: 'Análise iniciada',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
            },{
                numero: 3,
                descricao: 'Documento Pronto',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
            },{
                numero: 4,
                descricao: 'Documento entregue',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
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
            status: 'Em análise',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            },{
                numero: 2,
                descricao: 'Análise iniciada',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
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
            status: 'Entregue',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            },{
                numero: 2,
                descricao: 'Análise iniciada',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
            },{
                numero: 3,
                descricao: 'Documento Pronto',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
            },{
                numero: 4,
                descricao: 'Documento entregue',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
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
            status: 'Em análise',
            movimentacoes: [{
                numero: 1,
                descricao: 'Solicitação de certidão',
                usuario: 'Lucas Henrique',
                data: '01/06/2017 15:00'
            },{
                numero: 2,
                descricao: 'Análise iniciada',
                usuario: 'Reinaldo José',
                data: '01/06/2017 15:30'
            }]
        }];

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

        jQuery(document).ready(function(){
            $('table.display').DataTable( {
                "aaSorting": []
            } );
        });
    }

})();
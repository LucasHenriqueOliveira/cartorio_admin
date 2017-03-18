(function () {
    'use strict';

    angular
        .module('app')
        .controller('TituloPagarController', TituloPagarController);

    TituloPagarController.$inject = ['$scope'];

    function TituloPagarController($scope) {
        $scope.notas = [
            {
                'numero':'001-000.000.002-010.804.210-7',
                'fornecedor':'Shanid',
                'cnpj':'99.999.999/9999-99',
                'data':'01/02/2017'
            },
            {
                'numero':'001-000.000.002-010.804.210-8',
                'fornecedor':'Abraham',
                'cnpj':'99.999.999/9999-99',
                'data':'02/02/2017'
            },
            {
                'numero':'001-000.000.002-010.804.210-9',
                'fornecedor':'Mathew',
                'cnpj':'99.999.999/9999-99',
                'data':'03/02/2017'
            },
            {
                'numero':'001-000.000.002-010.804.210-7',
                'fornecedor':'Shanid',
                'cnpj':'99.999.999/9999-99',
                'data':'01/02/2017'
            },
            {
                'numero':'001-000.000.002-010.804.210-8',
                'fornecedor':'Abraham',
                'cnpj':'99.999.999/9999-99',
                'data':'02/02/2017'
            },
            {
                'numero':'001-000.000.002-010.804.210-9',
                'fornecedor':'Mathew',
                'cnpj':'99.999.999/9999-99',
                'data':'03/02/2017'
            },
            {
                'numero':'001-000.000.002-010.804.210-7',
                'fornecedor':'Shanid',
                'cnpj':'99.999.999/9999-99',
                'data':'01/02/2017'
            }];

        setTimeout(function(){
            jQuery(document).ready(function(){
                $('table.display').DataTable();
            } );
        }, 300);
    }

})();
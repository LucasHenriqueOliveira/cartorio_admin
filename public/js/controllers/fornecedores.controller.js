(function () {
    'use strict';

    angular
        .module('app')
        .controller('FornecedoresController', FornecedoresController);

    FornecedoresController.$inject = ['$scope', 'DataService'];

    function FornecedoresController($scope, DataService) {
        $scope.hasFornecedor = false;
        $scope.label_type = 'Salvar';
        $scope.fornecedor = {};
        $scope.fornecedor.tipo = 'pf';
        $scope.tipo_fornecedor = 'CPF';

        $scope.verificaTipo = function(tipo){
            if(tipo == 'pf') {
                $scope.tipo_fornecedor = 'CPF';
            } else {
                $scope.tipo_fornecedor = 'CNPJ';
            }
        };

        DataService.getEstados().then(function(response) {
            $scope.estados = response;
        });

        DataService.getFornecedores().then(function(response) {
            $scope.fornecedores = response;
        }, function (error) {
            toastr.error('Erro ao buscar os fornecedores', 'Fornecedor', {timeOut: 3000});
        });

        $scope.getCidades = function() {
            DataService.getCidades({id: $scope.fornecedor.estado.id}).then(function(response) {
                $scope.cidades = response;
            });
        };

        $scope.clickFornecedor = function(type) {
            if(type == 'Salvar') {
                $scope.fornecedor.estado = $scope.fornecedor.estado.uf;
                DataService.addFornecedor($scope.fornecedor).then(function(response) {
                    $scope.fornecedores = response;
                    toastr.success('Fornecedor cadastrado com sucesso!', 'Fornecedor', {timeOut: 3000});
                }, function (error) {
                    toastr.error('Erro ao cadastrar o fornecedor', 'Fornecedor', {timeOut: 3000});
                });
            } else {
                DataService.atualizarFornecedor({id: $scope.id, nome: $scope.nome_fornecedor}).then(function(response) {
                    $scope.fornecedores = response;
                    toastr.success('Fornecedor alterado com sucesso!', 'Fornecedor', {timeOut: 3000});
                }, function (error) {
                    toastr.error('Erro ao alterar o fornecedor', 'Fornecedor', {timeOut: 3000});
                });
            }

            $scope.hasFornecedor = false;
            $scope.fornecedor = {};
        };

        $scope.detalhesFornecedor = function() {

        };

        $scope.removerFornecedor = function(id) {
            DataService.removeFornecedor({id: id}).then(function(response) {
                $scope.fornecedores = response;
                toastr.success('Fornecedor removido com sucesso!', 'Fornecedor', {timeOut: 3000});
            }, function (error) {
                toastr.error('Erro ao remover o fornecedor', 'Fornecedor', {timeOut: 3000});
            });
            $scope.hasFornecedor = false;
            $scope.fornecedor = {};
        };

        $scope.editarFornecedor = function(fornecedor) {
            $scope.hasFornecedor = true;
            $scope.nome_fornecedor = fornecedor.name;
            $scope.label_type = 'Alterar';
            $scope.id = fornecedor.product_id;
        };

        setTimeout(function(){
            jQuery(document).ready(function(){
                $scope.table = $('table.display').DataTable({
                    "aaSorting": []
                });
            } );
        }, 300);
    }

})();
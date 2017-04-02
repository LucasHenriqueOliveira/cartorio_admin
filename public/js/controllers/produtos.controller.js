(function () {
    'use strict';

    angular
        .module('app')
        .controller('ProdutosController', ProdutosController);

    ProdutosController.$inject = ['$scope', 'DataService'];

    function ProdutosController($scope, DataService) {
        $scope.hasProduto = false;
        $scope.label_type = 'Salvar';

        DataService.getProdutos().then(function(response) {
            $scope.produtos = response;
        }, function (error) {
            toastr.error('Erro ao buscar os produtos', 'Produto', {timeOut: 3000});
        });

        $scope.clickProduto = function(type) {
            if(type == 'Salvar') {
                DataService.addProduto({nome: $scope.nome_produto}).then(function(response) {
                    $scope.produtos = response;
                    toastr.success('Produto cadastrado com sucesso!', 'Produto', {timeOut: 3000});
                }, function (error) {
                    toastr.error('Erro ao cadastrar o produto', 'Produto', {timeOut: 3000});
                });
            } else {
                DataService.atualizarProduto({id: $scope.id, nome: $scope.nome_produto}).then(function(response) {
                    $scope.produtos = response;
                    toastr.success('Produto alterado com sucesso!', 'Produto', {timeOut: 3000});
                }, function (error) {
                    toastr.error('Erro ao alterar o produto', 'Produto', {timeOut: 3000});
                });
            }

            $scope.hasProduto = false;
            $scope.nome_produto = '';
        };

        $scope.removerProduto = function(id) {
            DataService.removeProduto({id: id}).then(function(response) {
                $scope.produtos = response;
                toastr.success('Produto removido com sucesso!', 'Produto', {timeOut: 3000});
            }, function (error) {
                toastr.error('Erro ao remover o produto', 'Produto', {timeOut: 3000});
            });
            $scope.hasProduto = false;
            $scope.nome_produto = '';
        };

        $scope.editarProduto = function(produto) {
            $scope.hasProduto = true;
            $scope.nome_produto = produto.name;
            $scope.label_type = 'Alterar';
            $scope.id = produto.product_id;
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
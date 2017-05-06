(function () {
    'use strict';

    angular
        .module('app')
        .controller('RelatoriosController', RelatoriosController);

    RelatoriosController.$inject = ['$scope', 'DataService', '$rootScope', 'ModalService'];

    function RelatoriosController($scope, DataService, $rootScope, ModalService) {
        $scope.query = {};
        $scope.notas = {};
        $scope.soma_valor_nota = 0;
        $scope.soma_valor_produto = 0;
        $scope.soma_valor_total = 0;

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

        $scope.relatorio = function() {
            DataService.relatorio($scope.query).then(function(response) {
                $scope.notas = response;
                if($scope.notas.length) {
                    $scope.soma_valor_nota = $scope.notas[0].soma_valor_nota;
                    $scope.soma_valor_produto = $scope.notas[0].soma_valor_produto;
                    $scope.soma_valor_total = $scope.notas[0].soma_valor_total;
                    $scope.produtos_diferentes = $scope.notas[0].produtos_diferentes;
                }
                jQuery(document).ready(function(){
                    $('table.display').DataTable();
                });
            }, function (error) {
                toastr.error('Erro ao consultar o relatório', 'Relatório', {timeOut: 3000});
            });
        };

        $scope.backSearch = function() {
            $scope.notas = {};
        };

        $scope.detalhesNotaEntrada = function(nota) {
            $rootScope.nota = nota;
            ModalService.showModal({
                templateUrl: "templates/detalhes_nota_entrada.html",
                controller: function() {
                    if($rootScope.nota.centro_custo == 1) {
                        $rootScope.nota.centro_custo = 'Fazenda Rio Alegre';
                    } else {
                        $rootScope.nota.centro_custo = 'Fazenda Santa Maria';
                    }
                }
            }).then(function(modal) {
                modal.element.modal();
            });
        };
    }

})();
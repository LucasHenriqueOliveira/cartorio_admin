(function () {
    'use strict';

    angular
        .module('app')
        .controller('NotaEntradaController', NotaEntradaController);

    NotaEntradaController.$inject = ['$scope', 'DataService', 'ModalService', '$rootScope'];

    function NotaEntradaController($scope, DataService, ModalService, $rootScope) {
        $scope.hasNota = false;
        $scope.label_type = 'Salvar';
        $scope.nota = {};

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

        DataService.getNotasEntrada().then(function(response) {
            $scope.notas_entrada = response;
        }, function (error) {
            toastr.error('Erro ao buscar as notas de entrada', 'Notas de Entrada', {timeOut: 3000});
        });

        $scope.clickNotaEntrada = function(type)  {
            if(type == 'Salvar') {
                DataService.addNotaEntrada($scope.nota).then(function(response) {
                    $scope.notas_entrada = response;
                    toastr.success('Nota de entrada cadastrada com sucesso!', 'Nota de Entrada', {timeOut: 3000});
                }, function (error) {
                    toastr.error('Erro ao cadastrar a nota de entrada', 'Nota de Entrada', {timeOut: 3000});
                });
            } else {
                DataService.atualizarNotaEntrada($scope.nota).then(function(response) {
                    $scope.notas_entrada = response;
                    toastr.success('Nota de entrada alterada com sucesso!', 'Nota de Entrada', {timeOut: 3000});
                }, function (error) {
                    toastr.error('Erro ao alterar a nota de entrada', 'Nota de Entrada', {timeOut: 3000});
                });
            }

            $scope.hasNota = false;
            $scope.nota = {};
        };

        $scope.removerNotaEntrada = function(id) {
            DataService.removeNotaEntrada({id: id}).then(function(response) {
                $scope.notas_entrada = response;
                toastr.success('Nota de entrada removida com sucesso!', 'Nota de Entrada', {timeOut: 3000});
            }, function (error) {
                toastr.error('Erro ao remover a nota de entrada', 'Nota de Entrada', {timeOut: 3000});
            });
            $scope.hasNota = false;
            $scope.nota = {};
        };

        $scope.editarNotaEntrada = function(nota) {
            $scope.hasNota = true;
            $scope.nota = nota;
            $scope.label_type = 'Alterar';
        };

        $scope.cancelar = function() {
            $scope.hasNota = false;
            $scope.nota = {};

            jQuery(document).ready(function(){
                $('table.display').DataTable();
            });
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

        $scope.pesquisaNfe = function() {
            DataService.pesquisaNfe({nfe: $scope.nota.nfe}).then(function(response) {
                $scope.nota.data_compra = response[0].data_compra;
                $scope.nota.data_pagamento = response[0].data_pagamento;
                $scope.nota.valor_total = response[0].valor_total;
            }, function (error) {
                toastr.error('Erro ao pesquisar a NF-e', 'NF-e', {timeOut: 3000});
            });
        };

        setTimeout(function(){
            jQuery(document).ready(function(){
                $('table.display').DataTable();
            });
        }, 500);
    }

})();
(function () {
    'use strict';

    angular
        .module('app')
        .controller('FornecedoresController', FornecedoresController);

    FornecedoresController.$inject = ['$scope', 'DataService', 'ModalService', '$rootScope'];

    function FornecedoresController($scope, DataService, ModalService, $rootScope) {
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
                DataService.atualizarFornecedor($scope.fornecedor).then(function(response) {
                    $scope.fornecedores = response;
                    toastr.success('Fornecedor alterado com sucesso!', 'Fornecedor', {timeOut: 3000});
                }, function (error) {
                    toastr.error('Erro ao alterar o fornecedor', 'Fornecedor', {timeOut: 3000});
                });
            }

            $scope.hasFornecedor = false;
            $scope.fornecedor = {};

            jQuery(document).ready(function(){
                $(window).scrollTop(0);
            });
        };

        $scope.detalhesFornecedor = function(fornecedor) {
            $rootScope.fornecedor = fornecedor;
            ModalService.showModal({
                templateUrl: "templates/detalhes_fornecedor.html",
                controller: function($scope) {
                    $scope.checkEndereco = function(fornecedor) {
                        var endereco = fornecedor.logradouro;

                        if(fornecedor.complemento) {
                            endereco += ', ' + fornecedor.complemento;
                        }

                        if(fornecedor.bairro) {
                            endereco += ', ' + fornecedor.bairro;
                        }

                        if(fornecedor.cidade) {
                            endereco += ' - ' + fornecedor.cidade;
                        }

                        if(fornecedor.uf) {
                            endereco += '/' + fornecedor.uf;
                        }

                        if(fornecedor.cep) {
                            endereco += ' - ' + fornecedor.cep;
                        }

                        return endereco;
                    };
                }
            }).then(function(modal) {
                modal.element.modal();
            });
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
            $scope.fornecedor = fornecedor;
            $scope.label_type = 'Alterar';


            if($scope.fornecedor.cnpj_cpf.length == 11) {
                $scope.fornecedor.tipo = 'pf';
                $scope.tipo_fornecedor = 'CPF';
            } else {
                $scope.fornecedor.tipo = 'pj';
                $scope.tipo_fornecedor = 'CNPJ';
            }

            $scope.estados.forEach(function(entry){
                if(entry.uf == fornecedor.uf) {
                    $scope.fornecedor.estado = entry;
                }
            });

            if($scope.fornecedor.estado.id) {
                DataService.getCidades({id: $scope.fornecedor.estado.id}).then(function(response) {
                    $scope.cidades = response;

                    $scope.cidades.forEach(function(entry){
                        if(entry.id == fornecedor.cidade_id) {
                            $scope.fornecedor.cidade = entry.id;
                        }
                    });
                });
            }
        };

        $scope.cancelar = function() {
            $scope.hasFornecedor = false;
            $scope.fornecedor = {};

            jQuery(document).ready(function(){
                $scope.table = $('table.display').DataTable({
                    "aaSorting": []
                });
            });
        };

        setTimeout(function(){
            jQuery(document).ready(function(){
                $scope.table = $('table.display').DataTable({
                    "aaSorting": []
                });
            });
        }, 500);
    }

})();
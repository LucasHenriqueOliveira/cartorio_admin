(function () {
    'use strict';

    angular
        .module('app')
        .factory('DataService', DataService);

    DataService.$inject = ['$http', '$q', 'App'];

    function DataService($http, $q, App) {

        return {
            getDashboard: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'dashboard',
                    params: params
                })
                .then(function(response) {

                    deferred.resolve(response.data);

                }, function(error) {
                    console.log(error);
                });

                return deferred.promise;
            },
            getProdutos: function() {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'produtos'
                })
                .then(function(response) {

                    deferred.resolve(response.data);

                }, function(error) {
                    toastr.error('Erro ao buscar os produtos', 'Produto', {timeOut: 3000});
                });

                return deferred.promise;
            },
            addProduto: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'produto',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao cadastrar o produto', 'Produto', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            removeProduto: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'DELETE',
                    url: App.api + 'produto',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao remover o produto', 'Produto', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            atualizarProduto: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'PUT',
                    url: App.api + 'produto',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao alterar o produto', 'Produto', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getFornecedores: function() {
                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'fornecedores'
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao buscar os fornecedores', 'Fornecedor', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            addFornecedor: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'fornecedor',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao cadastrar o fornecedor', 'Fornecedor', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            removeFornecedor: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'DELETE',
                    url: App.api + 'fornecedor',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao remover o fornecedor', 'Fornecedor', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            atualizarFornecedor: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'PUT',
                    url: App.api + 'fornecedor',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao alterar o fornecedor', 'Fornecedor', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getEstados: function() {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'estados'
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar os estados', 'Estado', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getCidades: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'cidades',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar as cidades', 'Cidade', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            getNotasEntrada: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'GET',
                    url: App.api + 'notas-entrada',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar as notas de entrada', 'Notas de Entrada', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            addNotaEntrada: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'nota-entrada',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao cadastrar a nota de entrada', 'Nota de Entrada', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            removeNotaEntrada: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'DELETE',
                    url: App.api + 'nota-entrada',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao remover a nota de entrada', 'Nota de Entrada', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            atualizarNotaEntrada: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'PUT',
                    url: App.api + 'nota-entrada',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao alterar a nota de entrada', 'Nota de Entrada', {timeOut: 3000});
                    });

                return deferred.promise;
            },
            relatorio: function(params) {

                var deferred = $q.defer();

                $http({
                    method: 'POST',
                    url: App.api + 'relatorio',
                    params: params
                })
                    .then(function(response) {

                        deferred.resolve(response.data);

                    }, function(error) {
                        toastr.error('Erro ao consultar o relatório', 'Relatório', {timeOut: 3000});
                    });

                return deferred.promise;
            }
        }
    }
})();
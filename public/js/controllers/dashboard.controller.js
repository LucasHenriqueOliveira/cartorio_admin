(function () {
    'use strict';

    angular
        .module('app')
        .controller('DashboardController', DashboardController);

    DashboardController.$inject = ['$scope', '$rootScope', 'DataService', 'App'];

    function DashboardController($scope, $rootScope, DataService, App) {

        $scope.query = {
            start: '',
            end: ''
        };

        var curr = new Date;
        var dd = curr.getDate();
        var mm = curr.getMonth()+1;
        var yyyy = curr.getFullYear();
        var first = '1';

        $scope.query.start = yyyy + '-' + mm + '-' + first;
        $scope.query.end = yyyy + '-' + mm + '-' + dd;

        var options = {
            responsive: true,
            scales: {
                yAxes: [
                    {
                        id: 'y-axis-1',
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            userCallback: function(value, index, values) {
                                return parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                            }
                        }
                    }
                ]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return parseFloat(tooltipItem.yLabel).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    }
                }
            }
        };

        var getDashboard = function() {
            DataService.getDashboard($scope.query).then(function(response) {
                if(response.error === false) {
                    $scope.produtos = response.estatisticas.produtos;
                    $scope.fornecedores = response.estatisticas.fornecedores;
                    $scope.nf_entrada_valor = parseFloat(response.estatisticas.nf_entrada_valor).toLocaleString('pt-BR');
                    $scope.nf_entrada = response.estatisticas.nf_entrada;
                    $scope.notas = response.notas;

                    // notas de entrada
                    $scope.labels = [];
                    $scope.data = [];
                    if($scope.notas) {
                        $scope.notas.forEach(function(entry){
                            $scope.labels.push(entry.data);
                            $scope.data.push(parseInt(entry.nota));
                        });
                    }

                    $scope.data = [$scope.data];
                    $scope.series = ['Notas de Entrada'];
                    $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }];
                    $scope.options = options;

                } else {
                    $scope.message = response.message;
                }
            });
        };

        getDashboard();
    }

})();
(function () {
    'use strict';

    angular
        .module('app', ['ngRoute', 'chart.js', 'ngResource'])
        .config(config)
        .run(run);

    config.$inject = ['$routeProvider', '$httpProvider'];
    function config($routeProvider, $httpProvider) {

        $httpProvider.interceptors.push('TokenInterceptor');

        $routeProvider

            .when('/login', {
                controller: 'LoginController',
                templateUrl: 'templates/login.html',
                controllerAs: 'vm',
                cache: false
            })

            .when('/dashboard', {
                controller: 'DashboardController',
                templateUrl: 'templates/dashboard.html',
                controllerAs: 'vm',
                cache: false
            })

            .when('/nota-entrada', {
                controller: 'NotaEntradaController',
                templateUrl: 'templates/nota_entrada.html',
                controllerAs: 'vm',
                cache: false
            })

            .when('/nota-saida', {
                controller: 'NotaSaidaController',
                templateUrl: 'templates/nota_saida.html',
                controllerAs: 'vm',
                cache: false
            })

            .when('/titulo-pagar', {
                controller: 'TituloPagarController',
                templateUrl: 'templates/titulo_pagar.html',
                controllerAs: 'vm',
                cache: false
            })

            .when('/titulo-receber', {
                controller: 'TituloReceberController',
                templateUrl: 'templates/titulo_receber.html',
                controllerAs: 'vm',
                cache: false
            })

            .when('/estoque', {
                controller: 'EstoqueController',
                templateUrl: 'templates/estoque.html',
                controllerAs: 'vm',
                cache: false
            })

            .when('/produtos', {
                controller: 'ProdutosController',
                templateUrl: 'templates/produtos.html',
                controllerAs: 'vm',
                cache: false
            })

            .when('/fornecedores', {
                controller: 'FornecedoresController',
                templateUrl: 'templates/fornecedores.html',
                controllerAs: 'vm',
                cache: false
            })

            .otherwise({ redirectTo: '/login' });
    }

    run.$inject = ['$rootScope', '$location', 'Auth', 'App'];
    function run($rootScope, $location, Auth, App) {
        $rootScope.app = App;

        $rootScope.$on('$locationChangeStart', function (event, nextRoute, currentRoute) {
            $rootScope.url = ($location.path().substring(1).split("/"))[0];
            var myElement = angular.element(document.querySelector('#main'));

            if(isEmpty(Auth.isAuthenticated()) && isEmpty(Auth.isAuthorized())){
                $location.path('/login');
            }

            if($rootScope.url == 'login' || $rootScope.url == 'register') {
                myElement.addClass('login');
            } else {
                myElement.removeClass('login');
            }
        });
    }

})();
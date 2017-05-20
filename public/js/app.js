(function () {
    'use strict';

    angular
        .module('app', ['ngRoute', 'chart.js', 'ngResource', 'ui.utils.masks', 'idf.br-filters', 'angularModalService'])
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
                action: 'login',
                cache: false
            })

            .when('/dashboard', {
                controller: 'DashboardController',
                templateUrl: 'templates/dashboard.html',
                controllerAs: 'vm',
                action: 'dashboard',
                cache: false
            })

            .otherwise({ redirectTo: '/login' });
    }

    run.$inject = ['$rootScope', '$location', 'Auth', 'App', '$route'];
    function run($rootScope, $location, Auth, App, $route) {
        $rootScope.app = App;
        var setContentHeight = null;

        $rootScope.$on('$locationChangeSuccess', function () {
            $rootScope.page = $route.current.action;
            if (setContentHeight) {
                setContentHeight();
            }
        });

        $rootScope.$on('$locationChangeStart', function (event, nextRoute, currentRoute) {
            if (isEmpty(Auth.isAuthenticated()) && isEmpty(Auth.isAuthorized())) {
                $location.path('/login');
            }
        });
        $rootScope.menuSize = 'md';
        $rootScope.toggleMenu = function() {
            $rootScope.menuSize = $rootScope.menuSize == 'md' ? 'sm' : 'md';
            setContentHeight();
        };

        setTimeout(function() {
            jQuery(document).ready(function(){
                setContentHeight = function () {
                    setTimeout(function() {
                        var
                            $BODY = $('body'),
                            $SIDEBAR_MENU = $('#sidebar-menu'),
                            $SIDEBAR_FOOTER = $('.sidebar-footer'),
                            $LEFT_COL = $('.left_col'),
                            $RIGHT_COL = $('.right_col'),
                            $NAV_MENU = $('.nav_menu'),
                            $FOOTER = $('footer');

                        // TODO: This is some kind of easy fix, maybe we can improve this

                        // reset height
                        $RIGHT_COL.css('min-height', '630px');

                        var bodyHeight = $BODY.outerHeight(),
                            footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
                            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
                            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

                        // normalize content
                        contentHeight -= $NAV_MENU.height() + footerHeight;

                        $RIGHT_COL.css('min-height', '630px');
                    },1);
                };

                // recompute content when resizing
                $(window).smartresize(function(){
                    setContentHeight();
                });

                setContentHeight();
            });
        },300);
    }

})();
(function () {
    'use strict';

    angular
        .module('app')
        .factory('Auth', Auth);

    Auth.$inject = ['$localStorage', 'App', 'User', '$location', '$rootScope'];

    function Auth($localStorage, App, User, $location, $rootScope) {

        return {

            login: function (data) {
                User.login({email: data.email, password: data.password}, function (res) {
                    App.clearData();
                    App.token = res.data.token;
                    $localStorage.set('token', res.data.token);

                    User.getUser(function (res) {
                        App.user = res.data;
                        App.publishers = res.publishers;
                        App.sources = res.sources;
                        $localStorage.setObject('user', App.user);
                        $localStorage.setObject('publishers', App.publishers);
                        $localStorage.setObject('sources', App.sources);
                        $rootScope.$broadcast('user', App.user);
                        $location.path('/dashboard');
                    }, function (error) {
                        // error
                        $rootScope.$broadcast('error-login', {message: 'User not found!'});
                    });
                }, function (error) {
                    // error
                    $rootScope.$broadcast('error-login', {message: 'User not found!'});
                });
            },
            isAuthenticated: function(){
                if (!App.user) {
                    User.getUser(function (res) {
                        App.user = res.data;
                        App.publishers = res.publishers;
                        App.sources = res.sources;
                        $localStorage.setObject('user', App.user);
                        $localStorage.setObject('publishers', App.publishers);
                        $localStorage.setObject('sources', App.sources);
                        $rootScope.$broadcast('user', App.user);
                    }, function (error) {
                        // error
                        $rootScope.$broadcast('error-login', {message: 'User not found!'});
                    });
                }
                return App.user;
            },
            isAuthorized: function(){
                if (!App.token) {
                    App.token = $localStorage.get('token');
                }
                return App.token;
            }
        };
    }
})();
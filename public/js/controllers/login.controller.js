(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['$scope', 'Auth'];

    function LoginController($scope, Auth) {
        $scope.loginButtonText = "Entrar";
        $scope.loading = false;
        $scope.message = '';

        $scope.login = function() {
            $scope.loginButtonText = "Entrando";
            $scope.loading = true;
            $scope.message = '';

            var formData = {
                email: $scope.email,
                password: $scope.password
            };

            Auth.login(formData, function () {}, function (error) {
                // error
                $scope.loading = false;
            });
        };

        $scope.$on('error-login', function(event, args) {
            $scope.message = args.message;
            $scope.loading = false;
            $scope.loginButtonText = "Entrar";
            $scope.password = '';
        });
    }

})();
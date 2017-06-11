(function () {
    'use strict';

    angular
        .module('app')
        .controller('UsuariosController', UsuariosController);

    UsuariosController.$inject = ['$scope', '$rootScope', '$location'];

    function UsuariosController($scope, $rootScope, $location) {

        $scope.usuarios = [{
            nome: 'Lucas Henrique',
            email: 'lucas@gmail.com',
            perfil: 'Administrador',
            perfil_id: 1
        },{
            nome: 'Lucas Henrique',
            email: 'lucas@gmail.com',
            perfil: 'Gerente',
            perfil_id: 2
        },{
            nome: 'Lucas Henrique',
            email: 'lucas@gmail.com',
            perfil: 'Analista',
            perfil_id: 3
        },{
            nome: 'Lucas Henrique',
            email: 'lucas@gmail.com',
            perfil: 'Atendimento',
            perfil_id: 4
        }];

        jQuery(document).ready(function(){
            $('table.display').DataTable( {
                "aaSorting": []
            } );
        });

        var openModal = function() {
            ModalService.showModal({
                templateUrl: "templates/detalhes-usuario.html",
                controller: function() {

                }
            }).then(function(modal) {
                modal.element.modal();
            });
        };

        $scope.novo = function() {
            $rootScope.usuario = {};
            $location.path('/usuario');
        };

        $scope.editar = function(usuario) {
            $rootScope.usuario = usuario;
            openModal();
        };

    }

})();
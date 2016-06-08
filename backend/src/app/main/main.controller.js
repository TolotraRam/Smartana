(function () {
    'use strict';

    angular
        .module('backend')
        .controller('MainController', MainController);
        function MainController($scope, $location, userService, authenticationService) {

            var vm = this;

            //============================================
            //History
            //============================================
            $scope.$back = function(window) { 
                window.history.back();
            };

            //============================================
            //User Control
            //============================================
            if (authenticationService.check()) {
                userService.getMe({}).then(function (result) {
                    vm.me = result;
                });
            }
            vm.logout = function () {
                authenticationService.logout().then(function () {
                    $location.path('auth/login');
                });
            };

        }
})();
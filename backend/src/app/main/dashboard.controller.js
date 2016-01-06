(function() {

	'use strict';

  angular.module('backend')
          .controller('DashboardController', DashboardController);
  function DashboardController(userService, $scope, $location, toaster, $translate, $q, angularMomentConfig, roleService) {

    var vm = this;
    vm.loadState = false;

    vm.callServer = function callServer() {
      userService.get({cache: false}).then(function (result) {
          vm.rowCollection = result;
          vm.loadState = true;
      });
    };
    vm.callServer();
  }

})();
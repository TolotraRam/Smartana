(function() {
	'use strict';

  angular
    .module('backend')
    .controller('DashboardController', DashboardController);

    function DashboardController(userService) {

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
(function () {
    'use strict';

    angular
        .module('settingModule')
        .controller('SettingFormController', SettingFormController);

        function SettingFormController(settingService, messageService, toaster, $translate, $location, $q, setting) {
            var vm = this;

            vm.setting = (_.isEmpty(setting) || _.isUndefined(setting)) ? settingService.init() : setting;
            vm.save = function() {
            	var setting = angular.copy(vm.setting);
            	console.log(setting);
            }
        }
})();

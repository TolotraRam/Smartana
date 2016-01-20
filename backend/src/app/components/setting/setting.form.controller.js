(function () {
    'use strict';

    angular
        .module('settingModule')
        .controller('SettingFormController', SettingFormController);

        function SettingFormController(settingService, messageService, toaster, $translate, $location, $q, $state) {
            var vm = this;
        }
})();

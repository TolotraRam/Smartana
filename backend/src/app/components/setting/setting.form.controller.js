(function () {
    'use strict';

    angular
        .module('settingModule')
        .controller('SettingFormController', SettingFormController);

        function SettingFormController(settingService, messageService, toaster, $translate, $location, $q, $state, setting) {
            var vm = this;

            //==========================================
	        // Variable
	        //==========================================
            vm.setting = (_.isEmpty(setting) || _.isUndefined(setting)) ? settingService.init() : setting;
            //==========================================
	        // save
	        //==========================================
            var save = function () {
	            var setting = angular.copy(vm.setting);
	            var deferred = $q.defer();
                settingService.update(setting).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
	            return deferred.promise;
	        };

	        vm.saveLoading = false;
	        vm.save = function () {
	            vm.saveLoading = true;
	            save().then(function (result) {
	                vm.saveLoading = false;
	                toaster.pop('success', '', $translate.instant('setting.settingd update_success_msg'));
	                $state.go('main.setting-form');
	            }, function (result) {
	                vm.saveLoading = false;
	                messageService.formError(result);
	            });
	        };
        }
})();

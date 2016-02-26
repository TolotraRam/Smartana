
(function () {
    'use strict';

    angular.module('localisationModule')
    .controller('CountryFormController', CountryFormController);

    function CountryFormController($scope, countryService, messageService, toaster, $translate, country, $location, $q, $state) {
        var vm = this;

        //==========================================
        // Variable
        //==========================================
        vm.country = (_.isEmpty(country) || _.isUndefined(country)) ? countryService.init() : country;

        //==========================================
        // save
        //==========================================
        var save = function () {
            var country = angular.copy(vm.country);
            var deferred = $q.defer();
            if (country.id !== '') {
                countryService.update(country.id, country).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
            } else {
                countryService.store(country).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
            }

            return deferred.promise;

        };

        vm.isSaveAndExit = false;
        vm.saveLoading = false;
        vm.save = function () {
            vm.saveLoading = true;
            save().then(function (result) {
                vm.saveLoading = false;
                toaster.pop('success', '', $translate.instant('country.' + (vm.country.id !== '' ? 'update_success_msg' : 'create_success_msg')));
                if (vm.isSaveAndExit) {
                    $state.go('main.country-list');
                } else if (vm.country.id === '') {
                    $state.go('main.country-edit', {id: result.id});
                } else {
                    vm.country = result;
                }
            }, function (result) {
                vm.saveLoading = false;
                messageService.formError(result);
            });
        };

        vm.saveAndExit = function () {
            vm.isSaveAndExit = true;
            vm.save();
        };

        //==========================================
        // Delete
        //==========================================
        vm.deleteLoading = false;
        vm.delete = function () {
            if (vm.country.id !== '') {
                vm.deleteLoading = true;
                countryService.destroy(vm.country.id).then(function () {
                    toaster.pop('success', '', $translate.instant('country.delete_success_msg'));
                    $location.path('countrys');
                }, function () {
                    vm.deleteLoading = false;
                    toaster.pop('success', '', $translate.instant('country.delete_error_msg'));
                });
            }

        };

    }
})();

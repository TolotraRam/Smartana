
(function () {
    'use strict';

    angular.module('localisationModule')
    .controller('StateFormController', StateFormController);

    function StateFormController($scope, stateService, countryService, messageService, toaster, $translate, state, $location, $q, $state) {
        var vm = this;

        //==========================================
        // Variable
        //==========================================
        vm.state = (_.isEmpty(state) || _.isUndefined(state)) ? stateService.init() : state;

        //==========================================
        // Function
        //==========================================
        vm.country = [];
        vm.refreshCountry = function (string) {
            if (string !== '') {
                countryService.get({search: string}).then(function (result) {
                    vm.country = result;
                });
            }
        };

        //==========================================
        // save
        //==========================================
        var save = function () {
            var state = angular.copy(vm.state);
            state.country = _.pluck(state.country, 'id');
            var deferred = $q.defer();
            if (state.id !== '') {
                stateService.update(state.id, state).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
            } else {
                stateService.store(state).then(function (result) {
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
                toaster.pop('success', '', $translate.instant('state.' + (vm.state.id !== '' ? 'update_success_msg' : 'create_success_msg')));
                if (vm.isSaveAndExit) {
                    $state.go('main.state-list');
                } else if (vm.state.id === '') {
                    $state.go('main.state-edit', {id: result.id});
                } else {
                    vm.state = result;
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
            if (vm.state.id !== '') {
                vm.deleteLoading = true;
                stateService.destroy(vm.state.id).then(function () {
                    toaster.pop('success', '', $translate.instant('state.delete_success_msg'));
                    $location.path('states');
                }, function (result) {
                    vm.deleteLoading = false;
                    toaster.pop('success', '', $translate.instant('state.delete_error_msg'));
                });
            }

        };

    }
})();

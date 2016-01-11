
(function () {
    'use strict';

    angular.module('userModule')
    .controller('UserFormController', UserFormController);

    function UserFormController($scope, Upload, $timeout, userService, cityService, countryService, stateService, messageService, toaster, $translate, user, $location, $q, roleService, $state) {
        var vm = this;

        //==========================================
        // Variable
        //==========================================
        vm.user = (_.isEmpty(user) || _.isUndefined(user)) ? userService.init() : user;


        //==========================================
        // Function
        //==========================================
        vm.roles = [];
        vm.refreshRole = function (string) {
            if (string !== '') {
                roleService.get({search: string}).then(function (result) {
                    vm.roles = result;
                });
            }
        };

        vm.filter = {
            page: parseInt($location.page) || 1,
            search: $location.search().search || '',
            state_ids: []
        };
        //==========================================
        // Load Data
        //==========================================
        vm.countries = [];
        vm.refreshCountries = function (string) {
            if (string !== '') {
                countryService.get({search: string}).then(function (result) {
                    vm.countries = result;
                });
            }
        };
        vm.refreshCountries();


        vm.states = [];
        vm.loadState = false;
        vm.refreshStates = function (string) {
            if (string !== '') {
                stateService.get({search: string}).then(function (result) {
                    vm.states = result;
                });
            }
            vm.loadState = true;
        };
        vm.cities = [];
        vm.loadCity = false;
        vm.refreshCities = function (string) {
            if (string !== '') {
                var params = angular.copy(vm.filter);
                if (params.state_ids.length > 0) {
                    params['state_ids[]'] = params.state_ids[0];
                    delete params.state_ids;
                }
                cityService.get(params, {cache: false}).then(function (result) {
                    vm.cities = result;
                });
            }
            vm.loadCity = true;
        };

        //==========================================
        // Profile image
        //==========================================
        

        //==========================================
        // save
        //==========================================
        var save = function () {
            var user = angular.copy(vm.user);
            user.roles = _.pluck(user.roles, 'id');
            var deferred = $q.defer();
            if (user.id !== '') {
                userService.update(user.id, user).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
            } else {
                userService.store(user).then(function (result) {
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
                toaster.pop('success', '', $translate.instant('user.' + (vm.user.id !== '' ? 'update_success_msg' : 'create_success_msg')));
                if (vm.isSaveAndExit) {
                    $state.go('main.user-list');
                } else if (vm.user.id === '') {
                    $state.go('main.user-edit', {id: result.id});
                } else {
                    vm.user = result;
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
            if (vm.user.id !== '') {
                vm.deleteLoading = true;
                userService.destroy(vm.user.id).then(function () {
                    toaster.pop('success', '', $translate.instant('user.delete_success_msg'));
                    $location.path('users');
                }, function (result) {
                    vm.deleteLoading = false;
                    toaster.pop('success', '', $translate.instant('user.delete_error_msg'));
                });
            }

        };

    }
})();

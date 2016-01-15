(function () {
    'use strict';

    angular
        .module('userModule')
        .controller('UserFormController', UserFormController);

        function UserFormController($scope, Upload, $http, $timeout, userService, uploadService, cityService, countryService, stateService, messageService, toaster, $translate, user, $location, $q, roleService, $state) {
            var vm = this;
            vm.file = null;

            //==========================================
            // Variable
            //==========================================
            vm.user = (_.isEmpty(user) || _.isUndefined(user)) ? userService.init() : user;
            if(vm.user.id) {
                vm.country = user.city.state.country;
                vm.state = user.city.state;
                vm.city = user.city;
            }


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

            //==========================================
            // Load Data
            //==========================================
            vm.countries = [];
            vm.states = [];
            vm.cities = [];
            vm.loadState = false;
            vm.loadCity = false;

            vm.refreshCountries = function (string) {
                if (string !== '') {
                    countryService.get().then(function (result) {
                        vm.countries = result;
                    });
                }
            };

            vm.refreshStates = function (string) {
                if (string !== '') {
                    stateService.get({country_id: string}).then(function (result) {
                        vm.states = result;
                    });
                }
                vm.loadState = true;
            };

            vm.refreshCities = function (string) {
                if (string !== '') {
                    cityService.get({state_id: string}).then(function (result) {
                        vm.cities = result;
                    });
                }
                vm.loadCity = true;
            };


            vm.refreshCountries();
            if(vm.user.id) {
                vm.refreshStates();
                vm.refreshCities();
            }
            //==========================================
            // Profile image
            //==========================================

            //==========================================
            // save
            //==========================================
            var save = function () {
                var user = angular.copy(vm.user);
                user.roles = _.pluck(user.roles, 'id');
                user.city_id = vm.city;
                var deferred = $q.defer();

                var fd = new FormData();
                var url = 'http://api.dev/api/admin/users';
                if(vm.files) {
                    fd.append('attachment',vm.files);
                    fd.append('filename', vm.files.name);
                }

                fd.append("data", JSON.stringify(user));

                if (user.id !== '') {
                    userService.update(user.id, user).then(function (result) {
                        deferred.resolve(result);
                    }, function (result) {
                        deferred.reject(result);
                    });
                } else {
                    userService.store(fd).then(function (result) {
                        deferred.resolve(result);
                    }, function (result) {
                        deferred.reject(result);
                    });
                    /*uploadService.uploadfile(vm.file, data).then(function (result) {
                        console.log(result);
                        deferred.resolve(result);
                    }, function(result) {
                        console.log(result);
                        deferred.resolve(result);
                    });*/
                }
                return deferred.promise;
            };

            vm.isSaveAndExit = false;
            vm.saveLoading = false;
            vm.save = function () {
                vm.saveLoading = true;
                save().then(function (result) {
                    console.log(result);
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

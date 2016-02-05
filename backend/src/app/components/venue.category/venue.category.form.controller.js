(function () {
    'use strict';

    angular
        .module('venueCategoryModule')
        .controller('VenueCategoryFormController', VenueCategoryFormController);

        function VenueCategoryFormController(venueCategoryService, messageService, toaster, $translate, category, $location, $q, $state) {
            var vm = this;
            vm.file = null;

            //==========================================
            // Variable
            //==========================================
            vm.category = (_.isEmpty(category) || _.isUndefined(category)) ? venueCategoryService.init() : category;

            //==========================================
            // save
            //==========================================
            var save = function () {
                var category = angular.copy(vm.category);

                var deferred = $q.defer();
                var fd = new FormData();
                if(vm.file) {
                    fd.append('attachment',vm.file);
                    fd.append('filename', vm.file.name);
                }
                fd.append("data", angular.toJson(category));

                if (category.id !== '') {
                    fd.append('_method', 'PUT');
                    venueCategoryService.update(category.id, fd).then(function (result) {
                        deferred.resolve(result);
                    }, function (result) {
                        deferred.reject(result);
                    });
                } else {
                    venueCategoryService.store(fd).then(function (result) {
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
                    toaster.pop('success', '', $translate.instant('venue.category.' + (vm.category.id !== '' ? 'update_success_msg' : 'create_success_msg')));
                    if (vm.isSaveAndExit) {
                        $state.go('main.venue-category-list');
                    } else if (vm.category.id === '') {
                        $state.go('main.venue-category-edit', {id: result.id});
                    } else {
                        vm.category = result;
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
                if (vm.category.id !== '') {
                    vm.deleteLoading = true;
                    venueCategoryService.destroy(vm.category.id).then(function () {
                        toaster.pop('success', '', $translate.instant('venue.category.delete_success_msg'));
                        $location.path('venue/category');
                    }, function (result) {
                        vm.deleteLoading = false;
                        toaster.pop('success', '', $translate.instant('venue.category.delete_error_msg'));
                    });
                }

            };
        }
})();

(function () {

    'use strict';

    angular
        .module('venueCategoryModule')
        .controller('VenueCategoryListController', VenueCategoryListController);

    function VenueCategoryListController($location, $q, $scope, venueCategoryService, messageService, toaster, $translate, category, Restangular) {

        var vm = this;
        //==========================================
        // init
        //==========================================
        vm.category = (_.isEmpty(category) || _.isUndefined(category)) ? venueCategoryService.init() : category;

        //================================================
        // Table & Filter
        //================================================
        vm.tableLoading = false;
        vm.paginationAction = '';
        vm.rowCollection = [];
        vm.itemsByPage = 20;

        vm.filter = {
            limit: vm.itemsByPage,
            page: parseInt($location.page) || 1,
            search: $location.search().search || ''
        };

        vm.resetFilter = function () {
            var deferred = $q.defer();
            vm.filter.search = null;

            deferred.resolve();
            return deferred.promise;
        };

        vm.filterAction = function () {
            var deferred = $q.defer();

            vm.filter.page = 1;
            deferred.resolve();
            return deferred.promise;
        };

        vm.resetFilter();

        vm.callServer = function callServer(tableVenueCategory) {
            var params = angular.copy(vm.filter);

            var pagination = tableVenueCategory.pagination;
            if ($scope.paginationAction === 'next') {
                params.page = pagination.next;
            } else if ($scope.paginationAction === 'prev') {
                params.page = pagination.prev;
            }

            vm.tableLoading = true;
            venueCategoryService.get(params, {cache: false}).then(function (result) {
                tableVenueCategory.pagination.next = result.meta.pagination.next_page || null;
                tableVenueCategory.pagination.prev = result.meta.pagination.prev_page || null;

                vm.rowCollection = result;
                vm.tableLoading = false;
            });
        };

        //================================================
        //Event
        //================================================
        vm.remove = function removeItem(row) {
            var index = vm.rowCollection.indexOf(row);
            if (index !== -1 && row.id !== '') {
                venueCategoryService.destroy(row.id).then(function () {
                    vm.rowCollection.splice(index, 1);

                    toaster.pop('success', '', $translate.instant('venue.category.delete_success_msg'));
                },
                function() {
                    toaster.pop('error', '', $translate.instant('venue.category.delete_error_msg'));
                });
            }
        };

        vm.bulkRemove = function () {
            var removePromises = [];
            _.each(vm.rowCollection, function (row, index) {
                if (row.isSelected === true && row.id !== '') {
                    removePromises.push(venueCategoryService.destroy(row.id));
                }
            });
            $q.all(removePromises).then(function () {
                var newRowCollection = [];
                for (var i = 0; i < vm.rowCollection.length; i++) {
                    if (!(vm.rowCollection[i].isSelected === true && vm.rowCollection[i].id !== '')) {
                        newRowCollection.push(vm.rowCollection[i]);
                    }
                }
                vm.rowCollection = newRowCollection;

                toaster.pop('success', '', $translate.instant('venue.category.delete_success_msg'));
            }, function () {
                toaster.pop('error', '', $translate.instant('venue.category.delete_error_msg'));
            });
        };
        //==========================================
        // save
        //==========================================
        vm.save = function (row) {
            var deferred = $q.defer();
            var fd = new FormData();
            fd.append("data", angular.toJson({'enabled': row.enabled, 'is_featured': row.is_featured}));
            fd.append('_method', 'PUT');
            venueCategoryService.update(row.id, fd).then(function (result) {
                toaster.pop('success', '', $translate.instant('venue.category.update_success_msg'));
            }, function () {
                toaster.pop('error', '', $translate.instant('venue.category.update_error_msg'));
            });
        };

    }
})();

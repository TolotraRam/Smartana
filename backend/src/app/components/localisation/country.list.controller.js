(function () {
    'use strict';

    angular.module('localisationModule')
    .controller('CountryListController', CountryListController);

    function CountryListController(countryService, country, messageService, $scope, $state, $location, toaster, $translate, Restangular, $q) {
        var vm = this;

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

        vm.callServer = function callServer(tableState) {
            var params = angular.copy(vm.filter);

            var pagination = tableState.pagination;
            if ($scope.paginationAction === 'next') {
                params.page = pagination.next;
            } else if ($scope.paginationAction === 'prev') {
                params.page = pagination.prev;
            }

            vm.tableLoading = true;
            countryService.get(params, {cache: false}).then(function (result) {
                tableState.pagination.next = result.meta.pagination.next_page || null;
                tableState.pagination.prev = result.meta.pagination.prev_page || null;

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
                countryService.destroy(row.id).then(function () {
                        vm.rowCollection.splice(index, 1);

                        toaster.pop('success', '', $translate.instant('country.delete_success_msg'));
                    },
                    function () {
                        toaster.pop('error', '', $translate.instant('country.delete_error_msg'));
                    });
            }
        };

        vm.bulkRemove = function () {
            var removePromises = [];
            _.each(vm.rowCollection, function (row, index) {
                if (row.isSelected === true && row.id !== '') {
                    removePromises.push(countryService.destroy(row.id));
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

                toaster.pop('success', '', $translate.instant('country.delete_success_msg'));
            }, function () {
                toaster.pop('error', '', $translate.instant('country.delete_error_msg'));
            });
        };
        //==========================================
        // save
        //==========================================
        vm.save = function (row) {
            countryService.update(row.id, {'enabled': row.enabled}).then(function (result) {
                toaster.pop('success', '', $translate.instant('country.update_success_msg'));
            }, function () {
                toaster.pop('error', '', $translate.instant('country.update_error_msg'));
            });
        };

    }


})();


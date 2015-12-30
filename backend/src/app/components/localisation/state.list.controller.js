(function () {
    'use strict';

    angular.module('localisationModule')
    .controller('StateListController', StateListController);

    function StateListController(stateService, countryService, $scope, $location, toaster, $translate, $q) {
        var vm = this;
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
            search: $location.search().search || '',
            country_ids: []
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
            if (params.country_ids.length > 0) {
                params['country_ids[]'] = params.country_ids[0];
                delete params.country_ids;
            }

            var pagination = tableState.pagination;
            if ($scope.paginationAction === 'next') {
                params.page = pagination.next;
            } else if ($scope.paginationAction === 'prev') {
                params.page = pagination.prev;
            }

            vm.tableLoading = true;
            stateService.get(params, {cache: false}).then(function (result) {
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
                stateService.destroy(row.id).then(function () {
                        vm.rowCollection.splice(index, 1);

                        toaster.pop('success', '', $translate.instant('state.delete_success_msg'));
                    },
                    function () {
                        toaster.pop('error', '', $translate.instant('state.delete_error_msg'));
                    });
            }
        };

        vm.bulkRemove = function () {
            var removePromises = [];
            _.each(vm.rowCollection, function (row, index) {
                if (row.isSelected === true && row.id !== '') {
                    removePromises.push(stateService.destroy(row.id));
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

                toaster.pop('success', '', $translate.instant('state.delete_success_msg'));
            }, function () {
                toaster.pop('error', '', $translate.instant('state.delete_error_msg'));
            });
        };
        //==========================================
        // save
        //==========================================

        vm.save = function (row) {
            stateService.update(row.id, {'enabled': row.enabled}).then(function (result) {
                toaster.pop('success', '', $translate.instant('state.update_success_msg'));
            }, function () {
                toaster.pop('error', '', $translate.instant('state.update_error_msg'));
            });
        };

    }


})();


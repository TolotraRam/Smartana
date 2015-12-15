(function () {
    'use strict';

    angular.module('localisationModule')
    .controller('CityListController', CityListController);

    function CityListController(cityService, countryService, stateService, $scope, $location, toaster, $translate, $q) {
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
        vm.states = [];
        vm.refreshStates = function (string) {
            if (string !== '') {
                stateService.get({search: string}).then(function (result) {
                    vm.states = result;
                });
            }
        };
        vm.refreshStates();
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
            state_ids: []
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

        vm.callServer = function callServer(tableCity) {
            var params = angular.copy(vm.filter);

            var pagination = tableCity.pagination;
            if ($scope.paginationAction === 'next') {
                params.page = pagination.next;
            } else if ($scope.paginationAction === 'prev') {
                params.page = pagination.prev;
            }

            vm.tableLoading = true;
            cityService.get(params, {cache: false}).then(function (result) {
                tableCity.pagination.next = result.meta.pagination.next_page || null;
                tableCity.pagination.prev = result.meta.pagination.prev_page || null;

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
                cityService.destroy(row.id).then(function () {
                        vm.rowCollection.splice(index, 1);

                        toaster.pop('success', '', $translate.instant('city.delete_success_msg'));
                    },
                    function () {
                        toaster.pop('error', '', $translate.instant('city.delete_error_msg'));
                    });
            }
        };

        vm.bulkRemove = function () {
            var removePromises = [];
            _.each(vm.rowCollection, function (row, index) {
                if (row.isSelected === true && row.id !== '') {
                    removePromises.push(cityService.destroy(row.id));
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

                toaster.pop('success', '', $translate.instant('city.delete_success_msg'));
            }, function () {
                toaster.pop('error', '', $translate.instant('city.delete_error_msg'));
            });
        };

    }


})();


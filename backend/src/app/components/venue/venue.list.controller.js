
(function () {
    'use strict';

    angular
        .module('venueModule')
        .controller('VenueListController', VenueListController);

        function VenueListController(venueService, venueCategoryService, $scope, $location, toaster, $translate, $q) {
            var vm = this;

            //==========================================
            // Load Data
            //==========================================
            vm.categories = [];
            vm.refreshCategory = function (string) {
                if (string !== '') {
                    venueCategoryService.get({search: string}).then(function (result) {
                        vm.categories = result;
                    });
                }
            };
            vm.refreshCategory();

            //================================================
            // Table & Filter
            //================================================
            vm.tableLoading = false;
            vm.paginationAction = '';
            vm.rowCollection = [];
            vm.itemsByPage = 4;

            vm.filter = {
                limit: vm.itemsByPage,
                page: parseInt($location.search().page) || 1,
                search: $location.search().search || '',
                created_at: '',
                category_ids: []
            };


            vm.resetFilter = function () {
                var deferred = $q.defer();
                vm.filter.category_ids = [];
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
                if (params.category_ids.length > 0) {
                    params['category_ids[]'] = params.category_ids[0];
                    delete params.category_ids;
                };

                var pagination = tableState.pagination;
                if ($scope.paginationAction === 'next') {
                    params.page = pagination.next;
                } else if ($scope.paginationAction === 'prev') {
                    params.page = pagination.prev;
                };

                console.log(params);

                vm.tableLoading = true;
                venueService.get(params, {cache: false}).then(function (result) {
                    tableState.pagination.next = result.meta.pagination.next_page || null;
                    tableState.pagination.prev = result.meta.pagination.prev_page || null;
                    //update venues list
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
                    venueService.destroy(row.id).then(function () {
                            // remove the row data
                            vm.rowCollection.splice(index, 1);

                            // show notifications
                            toaster.pop('success', '', $translate.instant('venue.delete_success_msg'));
                        },
                        function () {
                            // show notifications
                            toaster.pop('error', '', $translate.instant('venue.delete_error_msg'));

                        });
                }
            };

            vm.bulkRemove = function () {
                var removePromises = [];
                _.each(vm.rowCollection, function (row) {
                    if (row.isSelected === true && row.id !== '') {
                        removePromises.push(venueService.destroy(row.id));
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

                    // show notifications
                    toaster.pop('success', '', $translate.instant('venue.delete_success_msg'));
                }, function () {
                    // show notifications
                    toaster.pop('error', '', $translate.instant('venue.delete_error_msg'));
                });
            };

        }


})();


(function (ng, undefined) {
    'use strict';
    ng.module('smart-table')
        .directive('stNextPrevPagination', function () {
            return {
                restrict: 'EA',
                require: '^stTable',
                scope: {
                    stItemsByPage: '=?',
                    stDisplayedPages: '=?'
                },
                templateUrl: function (element, attrs) {
                    if (attrs.stTemplate) {
                        return attrs.stTemplate;
                    }
                    return 'app/shared/override/smart-table/pagination.html';
                },
                link: function (scope, element, attrs, ctrl) {
                    scope.stItemsByPage = scope.stItemsByPage ? +(scope.stItemsByPage) : 10;
                    scope.stDisplayedPages = scope.stDisplayedPages ? +(scope.stDisplayedPages) : 5;

                    scope.setPaginationAction = function splice(action) {
                        scope.$parent.paginationAction = action;
                        ctrl.slice((scope.currentPage - 1) * scope.stItemsByPage, scope.stItemsByPage);
                    };

                    scope.currentPage = 1;
                    scope.cantPageForward = true;
                    scope.cantPageBackward = true;

                    function redraw() {
                        var paginationState = ctrl.tableState().pagination;
                        if (paginationState.start === 0) {
                            scope.currentPage = 1;
                        }
                        scope.cantPageBackward = scope.currentPage === 1;
                        if (!_.has(paginationState, 'next')) {
                            scope.cantPageForward = true;
                        } else {
                            scope.cantPageForward = _.isNull(paginationState.next);
                        }
                        if (!_.has(paginationState, 'prev')) {
                            scope.cantPageBackward = true;
                        } else {
                            scope.cantPageBackward = _.isNull(paginationState.prev);
                        }
                    }

                    scope.prev = function () {
                        scope.currentPage = Math.max(scope.currentPage - 1, 1);
                        if (scope.currentPage !== 1) {
                            scope.setPaginationAction('prev');
                        } else {
                            scope.setPaginationAction('');
                        }
                    };
                    scope.next = function () {
                        scope.currentPage += 1;
                        scope.setPaginationAction('next');
                    };

                    //table state --> view
                    scope.$watch(function () {
                        return ctrl.tableState().pagination;
                    }, redraw, true);

                    //scope --> table state  (--> view)
                    scope.$watch('stItemsByPage', function (newValue, oldValue) {
                        if (newValue !== oldValue) {
                            scope.selectPage(1);
                        }
                    });

                    //view -> table state
                    scope.selectPage = function (page) {
                        if (page > 0) {
                            ctrl.slice(0, scope.stItemsByPage);
                        }
                    };

                    if (!ctrl.tableState().pagination.number) {
                        ctrl.slice(0, scope.stItemsByPage);
                    }

                }
            };
        })
        .directive('stSelectAll', function () {
            return {
                require: '^stTable',
                template: '<md-checkbox class="md-primary" ng-model="isAllSelected" aria-label="stTableAll"></md-checkbox>',
                scope: {
                    rows: '=all',
                    ngModel: '=',
                    countSelectedRows: '='
                },
                link: function (scope) {

                    function getAllSelected() {
                        return (getTotalRows() === getSelectedRows());
                    }

                    function getTotalRows() {
                        return scope.rows.length;
                    }

                    function getSelectedRows() {
                        var selectedRows = 0;
                        scope.rows.forEach(function (row) {
                            if (row.isSelected) {
                                selectedRows++;
                            }
                        });
                        scope.countSelectedRows = selectedRows;
                        return selectedRows;
                    }

                    function setAllRows(bool) {
                        scope.rows.forEach(function (row) {
                            if (row.isSelected !== bool) {
                                row.isSelected = bool;
                            }
                        });
                    }

                    scope.$watch('rows', function () {
                        scope.isAllSelected = getAllSelected();
                    }, true);

                    scope.$watch('isAllSelected', function () {
                        if (scope.isAllSelected) {
                            setAllRows(true);
                        } else {
                            if (getAllSelected()) {
                                setAllRows(false);
                            }
                        }
                    });

                }
            };
        })
        .directive('stCustomAction', function () {
            var directiveConfig = {
                restrict: 'AE',
                require: '^stTable',
                link: function (scope, element, attrs, ctrl) {
                    //var table = ctrl.tableState();
                    element.on('click', function () {
                        scope.callback.call().then(function () {
                            ctrl.pipe();
                        });
                    });
                },
                scope: {
                    callback: '&'
                }
            };
            return directiveConfig;
        });

})(angular);
(function () {
    'use strict';

    angular
        .module('theme.directives', [])
        .directive('slideOutNav', function ($timeout) {
            return {
                restrict: 'A',
                scope: {
                    show: '=slideOutNav'
                },
                link: function (scope, element) {
                    scope.$watch('show', function (newVal) {
                        if (newVal) {
                            element.slideDown({
                                complete: function () {
                                    $timeout(function () {
                                        scope.$apply();
                                    });
                                }
                            });
                        } else if (!newVal) {
                            element.slideUp({
                                complete: function () {
                                    $timeout(function () {
                                        scope.$apply();
                                    });
                                }
                            });
                        }
                    });
                }
            };
        });

})();

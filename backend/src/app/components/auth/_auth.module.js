
(function () {
    'use strict';

    angular.module('authModule', ['ui.router'])
        .config(function ($stateProvider) {
            $stateProvider
                .state('fullscreen.login', {
                    url: 'auth/login',
                    templateUrl: 'app/components/auth/login.html',
                    controller: 'LoginController as loginCtrl',
                    resolve: {
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('auth.login').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                });
        });
})();

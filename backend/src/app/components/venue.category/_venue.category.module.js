(function () {
    'use strict';

    angular
        .module('venueCategoryModule', [])
        .run(function (Restangular) {
            //================================================
            // Restangular init
            //================================================
            Restangular.extendCollection('venues', function (model) {
                return model;
            });
            Restangular.extendModel('venues', function (model) {
                model.init = function () {
                    _.extend(model, {
                        id: ''
                    });
                };

                // event ===================================================
                if (model.id === '') {
                    model.init();
                }

                return model;

            });
        })
        .config(function ($stateProvider) {
            $stateProvider
                .state('main.venue-category-list', {
                  url: 'venue/category',
                  templateUrl: 'app/components/venue.category/venue.category.list.html',
                  controller: 'VenueCategoryListController as listCtrl',
                  resolve: {
                    hasPermission: function (userService, $state, $q) {
                      var deferred = $q.defer();
                      userService.getMe().then(function (result) {
                          if (!result.can('country.index')) {
                            $state.go('main.index');
                            deferred.resolve(false);
                          }
                          deferred.resolve(true);
                      }, function () {
                        $state.go('main.index');
                        deferred.reject();
                      });
                      return deferred.promise;
                    },
                    category: function () {
                      return;
                    },
                    meta: function ($rootScope, $translate, $q) {
                      var deferred = $q.defer();
                      $translate('venue.category.categories').then(function (translation) {
                        $rootScope.meta.pageTitle = translation;
                        deferred.resolve(true);
                      }, function () {
                        deferred.reject();
                      });
                      return deferred.promise;
                    }
                  }
                })
                .state('main.venue-category-create', {
                  url: 'venue/category/create',
                  templateUrl: 'app/components/venue.category/venue.category.form.html',
                  controller: 'VenueCategoryFormController as formCtrl',
                  resolve: {
                    hasPermission: function (userService, $state, $q) {
                      var deferred = $q.defer();
                      userService.getMe().then(function (result) {
                        if (!result.can('users.store')) {
                          $state.go('main.index');
                          deferred.resolve(false);
                        }
                        deferred.resolve(true);
                      }, function () {
                        $state.go('main.index');
                        deferred.reject(false);
                      });
                      return deferred.promise;
                    },
                    category: function () {
                      return;
                    },
                    meta: function ($rootScope, $translate, $q) {
                      var deferred = $q.defer();
                      $translate('venue.category.add_a_category').then(function (translation) {
                        $rootScope.meta.pageTitle = translation;
                        deferred.resolve(true);
                      }, function () {
                        deferred.reject();
                      });
                      return deferred.promise;
                    }
                  }
                })
                .state('main.venue-category-edit', {
                  url: 'venue/category/:id/edit',
                  templateUrl: 'app/components/venue.category/venue.category.form.html',
                  controller: 'VenueCategoryFormController as formCtrl',
                  resolve: {
                    hasPermission: function (userService, $state, $q) {
                      var deferred = $q.defer();
                      userService.getMe().then(function (result) {
                        if (!result.can(['users.index', 'users.update'], true)) {
                          $state.go('main.index');
                          deferred.resolve(false);
                        }
                        deferred.resolve(true);
                      }, function () {
                        $state.go('main.index');
                        deferred.reject(false);
                      });
                      return deferred.promise;
                    },
                    category: function (venueCategoryService, $stateParams, $q, $state) {
                      var deferred = $q.defer();
                      venueCategoryService.find($stateParams.id, {cache: false}).then(function (result) {
                        deferred.resolve(result);
                      }, function () {
                        $state.go('main.venue.category-list');
                        deferred.reject();
                      });
                      return deferred.promise;
                    },
                    meta: function ($rootScope, $translate, $q) {
                      var deferred = $q.defer();
                      $translate('venue.category.edit_category').then(function (translation) {
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

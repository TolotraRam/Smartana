(function () {
  'use strict';

  angular
    .module('localisationModule', ['ui.router'])
    .run(function (Restangular) {
      //================================================
      // Restangular init
      //================================================
      Restangular.extendCollection('country', function (model) {
        return model;
      });
      Restangular.extendModel('country', function (model) {
        // event ===================================================
        model.init = function () {
          _.extend(model, {
            id: '',
            enabled: 1
          });
        };
        // data event ===================================================
        if (model.id === '') {
          model.init();
        }
        return model;

      });
    })
    .config(function ($stateProvider) {
      $stateProvider
        .state('main.country-list', {
          url: 'country',
          templateUrl: 'app/components/localisation/country.list.html',
          controller: 'CountryListController as listCtrl',
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
            country: function () {
              return;
            },
            meta: function ($rootScope, $translate, $q) {
              var deferred = $q.defer();
              $translate('country.countries').then(function (translation) {
                $rootScope.meta.pageTitle = translation;
                deferred.resolve(true);
              }, function () {
                deferred.reject();
              });
              return deferred.promise;
            }
          }
        })
        .state('main.state-list', {
          url: 'state',
          templateUrl: 'app/components/localisation/state.list.html',
          controller: 'StateListController as listCtrl',
          resolve: {
            hasPermission: function (userService, $state, $q) {
              var deferred = $q.defer();
              userService.getMe().then(function (result) {
                  if (!result.can('state.index')) {
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
            meta: function ($rootScope, $translate, $q) {
              var deferred = $q.defer();
              $translate('state.states').then(function (translation) {
                $rootScope.meta.pageTitle = translation;
                deferred.resolve(true);
              }, function () {
                deferred.reject();
              });
              return deferred.promise;
            }
          }
        })
        .state('main.city-list', {
          url: 'city',
          templateUrl: 'app/components/localisation/city.list.html',
          controller: 'CityListController as listCtrl',
          resolve: {
            hasPermission: function (userService, $state, $q) {
              var deferred = $q.defer();
              userService.getMe().then(function (result) {
                  if (!result.can('city.index')) {
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
            meta: function ($rootScope, $translate, $q) {
              var deferred = $q.defer();
              $translate('city.cities').then(function (translation) {
                $rootScope.meta.pageTitle = translation;
                deferred.resolve(true);
              }, function () {
                deferred.reject();
              });
              return deferred.promise;
            }
          }
        })
        .state('main.country-create', {
          url: 'country/create',
          templateUrl: 'app/components/localisation/country.form.html',
          controller: 'CountryFormController as formCtrl',
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
            country: function () {
              return;
            },
            meta: function ($rootScope, $translate, $q) {
              var deferred = $q.defer();
              $translate('country.add_a_country').then(function (translation) {
                $rootScope.meta.pageTitle = translation;
                deferred.resolve(true);
              }, function () {
                deferred.reject();
              });
              return deferred.promise;
            }
          }
        })
        .state('main.country-edit', {
          url: 'country/:id/edit',
          templateUrl: 'app/components/localisation/country.form.html',
          controller: 'CountryFormController as formCtrl',
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
            country: function (countryService, $stateParams, $q, $state) {
              var deferred = $q.defer();
              countryService.find($stateParams.id, {cache: false}).then(function (result) {
                deferred.resolve(result);
              }, function () {
                $state.go('main.country-list');
                deferred.reject();
              });
              return deferred.promise;
            },
            meta: function ($rootScope, $translate, $q) {
              var deferred = $q.defer();
              $translate('country.edit_country').then(function (translation) {
                $rootScope.meta.pageTitle = translation;
                deferred.resolve(true);
              }, function () {
                deferred.reject();
              });
              return deferred.promise;
            }
          }
        })
        .state('main.state-create', {
          url: 'state/create',
          templateUrl: 'app/components/localisation/state.form.html',
          controller: 'StateFormController as formCtrl',
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
            state: function () {
              return;
            },
            meta: function ($rootScope, $translate, $q) {
              var deferred = $q.defer();
              $translate('state.add_a_state').then(function (translation) {
                $rootScope.meta.pageTitle = translation;
                deferred.resolve(true);
              }, function () {
                deferred.reject();
              });
              return deferred.promise;
            }
          }
        })
        .state('main.state-edit', {
          url: 'state/:id/edit',
          templateUrl: 'app/components/localisation/state.form.html',
          controller: 'StateFormController as formCtrl',
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
            state: function (stateService, $stateParams, $q, $state) {
              var deferred = $q.defer();
              stateService.find($stateParams.id, {cache: false}).then(function (result) {
                deferred.resolve(result);
              }, function () {
                $state.go('main.state-list');
                deferred.reject();
              });
              return deferred.promise;
            },
            meta: function ($rootScope, $translate, $q) {
              var deferred = $q.defer();
              $translate('state.edit_country').then(function (translation) {
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
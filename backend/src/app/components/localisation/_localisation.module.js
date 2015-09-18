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
        // variable ===================================================
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
    .config(function ($stateProvider, $urlRouterProvider) {
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
    });
})();
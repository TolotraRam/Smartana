(function () {
  'use strict';

  angular
  .module('venueGalleryModule', [])
  .run(function (Restangular) {
    //================================================
    // Restangular init
    //================================================
    Restangular.extendCollection('gallery', function (model) {
      return model;
    });
    Restangular.extendModel('gallery', function (model) {
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
    .state('main.venue-gallery-list', {
      url: 'venue/gallery',
      templateUrl: 'app/components/venue.gallery/venue.gallery.list.html',
      controller: 'VenueGalleryListController as listCtrl',
      resolve: {
        hasPermission: function (userService, $state, $q) {
          var deferred = $q.defer();
          userService.getMe().then(function (result) {
            if (!result.can('venue.gallery.index')) {
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
        gallery: function () {
          return;
        },
        meta: function ($rootScope, $translate, $q) {
          var deferred = $q.defer();
          $translate('venue.gallery.galleries').then(function (translation) {
            $rootScope.meta.pageTitle = translation;
            deferred.resolve(true);
          }, function () {
            deferred.reject();
          });
          return deferred.promise;
        }
      }
    })
    .state('main.venue-gallery-create', {
      url: 'venue/gallery/create',
      templateUrl: 'app/components/venue.gallery/venue.gallery.form.html',
      controller: 'VenueGalleryFormController as formCtrl',
      resolve: {
        hasPermission: function (userService, $state, $q) {
          var deferred = $q.defer();
          userService.getMe().then(function (result) {
            if (!result.can('venue.gallery.store')) {
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
        gallery: function () {
          return;
        },
        meta: function ($rootScope, $translate, $q) {
          var deferred = $q.defer();
          $translate('venue.gallery.add_a_gallery').then(function (translation) {
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

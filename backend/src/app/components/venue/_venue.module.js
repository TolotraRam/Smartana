(function() {
	'use strict';

	angular
    	.module('venueModule', ['ui.router'])
    	.run(function (Restangular) {
	      	//================================================
	      	// Restangular init
	      	//================================================
	      	Restangular.extendCollection('venues', function (model) {
	        	return model;
	      	});
	      	Restangular.extendModel('venues', function (model) {
	        	// variable ===================================================
	        	// event ===================================================
	        	model.init = function () {
	          		_.extend(model, {
	            		id: '',
                        categories: []
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
	      	.state('main.venue-list', {
                url: 'venues',
                templateUrl: 'app/components/venue/venue.list.html',
                controller: 'VenueListController as listCtrl',
                resolve: {
                    hasPermission: function (userService, $state, $q) {
                        var deferred = $q.defer();
                        userService.getMe().then(function (result) {
                            if (!result.can('venues.index')) {
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
                    meta: function ($rootScope, $translate, $q) {
                        var deferred = $q.defer();
                        $translate('venue.venues').then(function (translation) {
                            $rootScope.meta.pageTitle = translation;
                            deferred.resolve(true);
                        }, function () {
                            deferred.reject();
                        });
                        return deferred.promise;
                    }
                }
            })
	        .state('main.venue-create', {
	          url: 'venues/create',
	          templateUrl: 'app/components/venue/venue.form.html',
	          controller: 'VenueFormController as formCtrl',
	          resolve: {
	            hasPermission: function (userService, $state, $q) {
	              var deferred = $q.defer();
	              userService.getMe().then(function (result) {
	                if (!result.can(['venues.store'], true)) {
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
	            venue: function () {
	            	return;
                },
	            meta: function ($rootScope, $translate, $q) {
	              var deferred = $q.defer();
	              $translate('venue.add_a_venue').then(function (translation) {
	                $rootScope.meta.pageTitle = translation;
	                deferred.resolve(true);
	              }, function () {
	                deferred.reject();
	              });
	              return deferred.promise;
	            }
	          }
	        })
			.state('main.venue-edit', {
                url: 'venues/:id/edit',
                templateUrl: 'app/components/venue/venue.form.html',
                controller: 'VenueFormController as formCtrl',
                resolve: {
                    hasPermission: function (userService, $state, $q) {
                        var deferred = $q.defer();
                        userService.getMe().then(function (result) {
                            if (!result.can(['venues.index', 'venues.update'], true)) {
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
                    venue: function (venueService, $stateParams, $q, $state) {
                        var deferred = $q.defer();
                        venueService.find($stateParams.id, {cache: false}).then(function (result) {
                            deferred.resolve(result);
                        }, function () {
                            $state.go('main.venue-list');
                            deferred.reject();
                        });
                        return deferred.promise;
                    },
                    meta: function ($rootScope, $translate, $q) {
                        var deferred = $q.defer();
                        $translate('venue.edit_venue').then(function (translation) {
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
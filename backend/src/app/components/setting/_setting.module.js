
(function () {
    'use strict';

    angular
    	.module('settingModule', ['ui.router'])
    	.run(function (Restangular) {
	      	//================================================
	      	// Restangular init
	      	//================================================
	      	Restangular.extendCollection('setting', function (model) {
	        	return model;
	      	});
	      	Restangular.extendModel('setting', function (model) {
	        	// variable ===================================================
	        	// event ===================================================
	        	model.init = function () {
	          		_.extend(model, {
	            		id: ''
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
	        .state('main.setting-edit', {
	          url: 'setting/edit',
	          templateUrl: 'app/components/setting/setting.form.html',
	          controller: 'SettingFormController as formCtrl',
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
	            setting: function (settingService, $stateParams, $q, $state) {
	              var deferred = $q.defer();
	              settingService.find({cache: false}).then(function (result) {
	                deferred.resolve(result);
	              }, function () {
	                $state.go('main.dashboard');
	                deferred.reject();
	              });
	              return deferred.promise;
	            },
	            meta: function ($rootScope, $translate, $q) {
	              var deferred = $q.defer();
	              $translate('setting.edit_setting').then(function (translation) {
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

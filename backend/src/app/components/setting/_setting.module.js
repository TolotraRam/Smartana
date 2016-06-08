(function () {
    'use strict';

    angular
    	.module('settingModule', ['ui.router'])
    	.run(function (Restangular) {
	      	//================================================
	      	// Restangular init
	      	//================================================
	      	Restangular.extendCollection('settings', function (model) {
	        	return model;
	      	});
	      	Restangular.extendModel('settings', function (model) {
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
	    .config(function ($stateProvider) {
	      $stateProvider
	        .state('main.setting-form', {
	          url: 'setting',
	          templateUrl: 'app/components/setting/setting.form.html',
	          controller: 'SettingFormController as formCtrl',
	          resolve: {
	            hasPermission: function (userService, $state, $q) {
	              var deferred = $q.defer();
	              userService.getMe().then(function (result) {
	                if (!result.can(['setting.index', 'setting.store'], true)) {
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
	            setting: function (settingService, $q, $state) {
                    var deferred = $q.defer();
	              	settingService.get({'cache': false}).then(function (result) {
	                	deferred.resolve(result);
	              	}, function () {
	                	$state.go('main.dashboard');
	                	deferred.reject();
	              	});
	              	return deferred.promise;
                },
	            meta: function ($rootScope, $translate, $q) {
	              var deferred = $q.defer();
	              $translate('setting.settings').then(function (translation) {
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

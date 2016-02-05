(function () {
    'use strict';

    angular
        .module('venueCategoryModule')
        .factory('venueCategoryService', venueCategoryService);
        function venueCategoryService(Restangular) {

            var service = {};

            service.init = function () {
                return Restangular.one('venues/categories', '');
            };
            service.get = function (param, httpConfig) {
                param = param || {};
                httpConfig = httpConfig || {};
                return Restangular.all('venues/categories').withHttpConfig(httpConfig).getList(param);
            };
            service.find = function (id) {
                return Restangular.one('venues/categories', id).get();
            };
            service.update = function (id, data) {
                return Restangular.one('venues/categories', id)
                .withHttpConfig({transformRequest: angular.identity})
                .customPOST(data, undefined, undefined, {'Content-Type': undefined});
            };
            service.store = function (data) {
                return Restangular.all('venues/categories')
                .withHttpConfig({transformRequest: angular.identity})
                .customPOST(data, undefined, undefined, {'Content-Type': undefined});
            };
            service.destroy = function (id) {
                return Restangular.one('venues/categories', id).remove();
            };

            return service;

        }
})();

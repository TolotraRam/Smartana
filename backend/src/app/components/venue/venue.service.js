
(function () {
    'use strict';

    angular
        .module('venueModule')
        .factory('venueService', venueService);
        function venueService(Restangular) {

            var service = {};

            service.init = function () {
                return Restangular.one('venues', '');
            };
            service.get = function (param, httpConfig) {
                param = param || {};
                httpConfig = httpConfig || {};
                return Restangular.all('venues').withHttpConfig(httpConfig).getList(param);
            };
            service.find = function (id, httpConfig) {
                httpConfig = httpConfig || {};
                return Restangular.one('venues', id).withHttpConfig(httpConfig).get();
            };
            service.update = function (id, data) {
                return Restangular.one('venues', id)
                .withHttpConfig({transformRequest: angular.identity})
                .customPOST(data, undefined, undefined, {'Content-Type': undefined});
            };
            service.store = function (data) {
                return Restangular.all('venues')
                .withHttpConfig({transformRequest: angular.identity})
                .customPOST(data, undefined, undefined, {'Content-Type': undefined});
            };
            service.destroy = function (id) {
                return Restangular.one('venues', id).remove();
            };

            return service;

        }
})();

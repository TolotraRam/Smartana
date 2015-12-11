(function () {
    'use strict';

    angular.module('localisationModule').factory('countryService', countryService);
    function countryService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('country', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('country').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id, httpConfig) {
            httpConfig = httpConfig || {};
            return Restangular.one('country', id).withHttpConfig(httpConfig).get();
        };
        service.update = function (id, data) {
            return Restangular.one('country', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('country').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('country', id).remove();
        };

        return service;

    }
})();
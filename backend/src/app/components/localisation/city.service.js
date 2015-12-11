(function () {
    'use strict';

    angular.module('localisationModule').factory('cityService', cityService);
    function cityService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('city', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('city').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id, httpConfig) {
            httpConfig = httpConfig || {};
            return Restangular.one('city', id).withHttpConfig(httpConfig).get();
        };
        service.update = function (id, data) {
            return Restangular.one('city', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('city').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('city', id).remove();
        };

        return service;

    }
})();
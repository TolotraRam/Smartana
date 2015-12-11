(function () {
    'use strict';

    angular.module('localisationModule').factory('stateService', stateService);
    function stateService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('state', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('state').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id, httpConfig) {
            httpConfig = httpConfig || {};
            return Restangular.one('state', id).withHttpConfig(httpConfig).get();
        };
        service.update = function (id, data) {
            return Restangular.one('state', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('state').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('state', id).remove();
        };

        return service;

    }
})();
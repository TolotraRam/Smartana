(function () {
    'use strict';

    angular
        .module('venueGalleryModule')
        .factory('venueGalleryService', venueGalleryService);
        function venueGalleryService(Restangular) {

            var service = {};

            service.init = function () {
                return Restangular.one('venue/gallery', '');
            };
            service.get = function (param, httpConfig) {
                param = param || {};
                httpConfig = httpConfig || {};
                return Restangular.all('venue/gallery').withHttpConfig(httpConfig).getList(param);
            };
            service.find = function (id) {
                return Restangular.one('venue/gallery', id).get();
            };
            service.update = function (id, data) {
                return Restangular.one('venue/gallery', id).customPUT(data);
            };
            service.store = function (data) {
                return Restangular.all('venue/gallery').post(data);
            };
            service.destroy = function (id) {
                return Restangular.one('venue/gallery', id).remove();
            };
            return service;

        }
})();

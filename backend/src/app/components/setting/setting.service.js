(function () {
    'use strict';

    angular
        .module('settingModule')
        .factory('settingService', settingService);
        function settingService(Restangular) {

            // private variable
            var settings = {
                store_name: 'YOOV CMS',
                store_email: 'mark@yoov.com',
                store_address: '',
                store_phone: '',
                timezone: 'Asia/Hong_Kong',
                currency: 'HKD',
                active: true,
                meta_title: '',
                meta_description: '',
                meta_keywords: ''
            };

            var service = {};

            service.init = function () {
                return Restangular.one('setting', '');
            };
            service.get = function (param, httpConfig) {
                param = param || {};
                httpConfig = httpConfig || {};
                return Restangular.all('setting').withHttpConfig(httpConfig).getList(param);
            };
            service.findByName = function (name) {
                return Restangular.one('setting', name).get();
            };
            service.update = function (data) {
                return Restangular.all('setting').customPUT(data);
            };
            service.findSettings = function (key) {
                return settings[key];
            };
            return service;
        }
})();

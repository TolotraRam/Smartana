
(function () {
    'use strict';

    angular.module('settingModule').factory('settingService', settingService);
    function settingService() {

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
        service.get = function () {
            return settings;
        };
        service.find = function (key) {
            return settings[key];
        };
        return service;
    }
})();

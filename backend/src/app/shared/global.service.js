
(function () {
    'use strict';

    angular.module('global.service', [])
        .factory('messageService', messageService);
    function messageService(toaster) {
        var _self = this;
        var service = {};
        service.formError = function (result) {
            toaster.pop('error', '', result.data.result.message);
        };

        return service;
    }
})();

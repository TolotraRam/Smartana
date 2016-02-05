
(function () {
    'use strict';

    angular
        .module('theme.services', [])
        .service('siteThemeService', siteThemeService);
        function siteThemeService($rootScope) {
            this.settings = {
                isOpenSidebar: true,
                isOpenControlSidebar: false,
                isBoxedLayout: false,
                isFixedLayout: true,
                fullscreen: false
            };

            this.get = function (key) {
                return this.settings[key];
            };
            this.set = function (key, value) {
                this.settings[key] = value;
                $rootScope.$broadcast('siteThemeService:changed', {
                    key: key,
                    value: this.settings[key]
                });
                $rootScope.$broadcast('siteThemeService:changed:' + key, this.settings[key]);
            };
            this.values = function () {
                return this.settings;
            };
        }
})();

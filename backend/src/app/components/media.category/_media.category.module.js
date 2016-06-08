
(function () {
    'use strict';

    angular.module('mediaCategoryModule', [])
        .run(function (Restangular) {
            //================================================
            // Restangular init
            //================================================
            Restangular.extendCollection('medias', function (model) {
                return model;
            });
            Restangular.extendModel('medias', function (model) {
                model.init = function () {
                    _.extend(model, {
                        id: ''
                    });
                };

                // event ===================================================
                if (model.id === '') {
                    model.init();
                }

                return model;

            });
        });
})();

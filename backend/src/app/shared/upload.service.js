
(function () {
    'use strict';

    angular
        .module('upload.service', [])
        .factory('uploadService', uploadService);

        function uploadService($http, authenticationService, $q) {
            return {
                uploadfile : uploadfile
            }

            function uploadfile(files, data) {

                var deferred = $q.defer();
                var fd = new FormData();
                var url = 'http://api.dev/api/admin/users';
                fd.append('attachment',files);
                fd.append('filename', files.name);
                var data = data;

                fd.append("data", JSON.stringify(data));

                $http.post(url, fd, {
                    withCredentials : false,
                    headers : {
                        'Content-Type' : undefined,
                        'Authorization': authenticationService.getToken()
                    },
                    transformRequest : angular.identity
                }).success(function(data) {
                    deferred.resolve(data);
                }).error(function(data) {
                    deferred.reject(data);
                });

                return deferred.promise;
            }
        }
})();

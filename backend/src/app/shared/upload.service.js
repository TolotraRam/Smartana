
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
                if(files) {
                    fd.append('attachment',files);
                    fd.append('filename', files.name);
                }

                fd.append("data", JSON.stringify(data));

                $http.post(url, fd, {
                    withCredentials : false,
                    headers : {
                        'Content-Type' : undefined,
                        'Authorization': authenticationService.getToken()
                    },
                    transformRequest : angular.identity
                }).then(function(response) {
                    deferred.resolve(response);
                }, function(response) {
                    deferred.reject(response);
                });
                return deferred.promise;
            }
        }
})();


(function () {
    'use strict';

    angular
        .module('upload.service', [])
        .factory('uploadService', uploadService);

        function uploadService($http, authenticationService) {
            return {
                uploadfile : uploadfile
            }

            function uploadfile( files, data, success, error ) {

                var fd = new FormData();
                var url = 'http://api.dev/api/admin/users';
                console.log(files.name);
                fd.append('attachment',files);
                fd.append('filename', files.name);
                //sample data
                var data = data;

                fd.append("data", JSON.stringify(data));

                $http.post(url, fd, {
                    withCredentials : false,
                    headers : {
                        'Content-Type' : undefined,
                        'Authorization': authenticationService.getToken()
                    },
                    transformRequest : angular.identity
                })
                .success(function(data) {
                    return data;
                })
                .error(function(data) {
                    return data;
                });
            }
        }
})();


(function () {
    'use strict';

    angular
        .module('global.directive', [])
        .directive('ngReallyClick', function ($modal) {

            var ModalInstanceCtrl = function ($scope, $modalInstance) {
                $scope.ok = function () {
                    $modalInstance.close();
                };

                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            };

            return {
                restrict: 'A',
                scope: {
                    ngReallyClick: "&"
                },
                link: function (scope, element, attrs) {
                    element.bind('click', function () {
                        var message = attrs.ngReallyMessage || "Are you sure ?";

                        var modalHtml = '<div class="modal-body">' + message + '</div>';
                        modalHtml += '<div class="modal-footer"><button class="btn btn-primary" ng-click="ok()">{{ \'button.yes\' | translate }}</button><button class="btn btn-default" ng-click="cancel()">Cancel</button></div>';

                        var modalInstance = $modal.open({
                            template: modalHtml,
                            controller: ModalInstanceCtrl
                        });

                        modalInstance.result.then(function () {
                            scope.ngReallyClick();
                        }, function () {
                            //Modal dismissed
                        });

                    });

                }
            }
        })
        .directive("ngFileSelect",function(){    
          return {
            link: function($scope,el){          
              el.bind("change", function(e){          
                $scope.file = (e.srcElement || e.target).files[0];
                $scope.getFile();
              });          
            }        
          }
        });

})();

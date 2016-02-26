(function () {
    'use strict';

    angular
        .module('venueGalleryModule')
        .controller('VenueGalleryFormController', VenueGalleryFormController);

        function VenueGalleryFormController(venueGalleryService, messageService, toaster, $translate, gallery, $location, $q, $state) {
            var vm = this;

            //==========================================
            // Variable
            //==========================================
            vm.gallery = (_.isEmpty(gallery) || _.isUndefined(gallery)) ? venueGalleryService.init() : gallery;

            //==========================================
            // save
            //==========================================
            var save = function () {
                var gallery = angular.copy(vm.gallery);

                var deferred = $q.defer();
                venueGalleryService.update(gallery).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
                return deferred.promise;
            };

            vm.isSaveAndExit = false;
            vm.saveLoading = false;
            vm.save = function () {
                vm.saveLoading = true;
                save().then(function () {
                    vm.saveLoading = false;
                    toaster.pop('success', '', $translate.instant('venue.gallery.update_success_msg'));
                    $state.go('main.venue-gallery-edit');
                }, function (result) {
                    vm.saveLoading = false;
                    messageService.formError(result);
                });
            };

            vm.saveAndExit = function () {
                vm.isSaveAndExit = true;
                vm.save();
            };

            //==========================================
            // Delete
            //==========================================
            vm.deleteLoading = false;
            vm.delete = function () {
                if (vm.gallery.id !== '') {
                    vm.deleteLoading = true;
                    venueGalleryService.destroy(vm.gallery.id).then(function () {
                        toaster.pop('success', '', $translate.instant('venue.gallery.delete_success_msg'));
                        $location.path('venue/gallery');
                    }, function () {
                        vm.deleteLoading = false;
                        toaster.pop('success', '', $translate.instant('venue.gallery.delete_error_msg'));
                    });
                }

            };
        }
})();

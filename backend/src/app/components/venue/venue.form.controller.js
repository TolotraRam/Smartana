(function () {
    'use strict';

    angular
        .module('venueModule')
        .controller('VenueFormController', VenueFormController)
        .controller('ModalInstanceCtrl', ModalInstanceCtrl);
        function VenueFormController($scope, venueService, venueCategoryService, countryService, stateService, cityService, messageService, toaster, $translate, venue, $location, $q) {
            var vm = this;
            //==========================================
            // Variable
            //==========================================
            vm.venue = (_.isEmpty(venue) || _.isUndefined(venue)) ? venueService.init() : venue;

            if(vm.venue.id) {
                vm.country = venue.city.state.country;
                vm.state = venue.city.state;
                vm.city = venue.city;
            }
            vm.place = null;
            if(!vm.venue.location) {
                vm.venue.location = {
                    lat: -18.8791902,
                    lng: 47.50790549999999
                };
            }
            vm.map = { 
                center: { 
                    latitude: (vm.venue.location) ? vm.venue.location.lat : -18.8791902, 
                    longitude: (vm.venue.location) ? vm.venue.location.lng : 47.50790549999999 
                },
                options: {
                    scrollwheel: false, 
                    disableDoubleClickZoom: true,
                    zoomControl: true,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: false
                },
                zoom: 18
            };
            vm.autocompleteOptions = {
                componentRestrictions: { country: 'mg' }
            };
            vm.marker = {
                id: 0,
                coords: {
                    latitude: (vm.venue.location) ? vm.venue.location.lat : -18.8791902,
                    longitude: (vm.venue.location) ? vm.venue.location.lng : 47.50790549999999 
                },
                options: { draggable: true },
                events: {
                    dragend: function (marker) {
                        vm.venue.location.lat = marker.getPosition().lat();
                        vm.venue.location.lng = marker.getPosition().lng();
                    }
                }
            };
            vm.placeChange = function() {
                if(typeof vm.place === 'object') {
                    vm.venue.name = vm.place.name;
                    vm.venue.phone = vm.place.international_phone_number || '';
                    vm.venue.location = {
                        lat: vm.place.geometry.location.lat() || '',
                        lng: vm.place.geometry.location.lng() || ''
                    };
                    vm.map = {
                        center: {
                            latitude: vm.place.geometry.location.lat() || '',
                            longitude: vm.place.geometry.location.lng() || ''
                        }
                    };
                    vm.venue.website = vm.place.website || '';
                    var address = _.split(vm.place.formatted_address, ',') || '';
                    vm.venue.address = address[0];
                    vm.marker = {
                        id: 0,
                        coords: {
                            latitude: vm.venue.location.lat,
                            longitude: vm.venue.location.lng
                        },
                        options: { draggable: true },
                        events: {
                            dragend: function (marker) {
                                vm.venue.location.lat = marker.getPosition().lat();
                                vm.venue.location.lng = marker.getPosition().lng();
                            }
                        }
                    };
                }
            };

            //==========================================
            // Load Data
            //==========================================
            if (vm.venue.categories.length > 0) {
                venueCategoryService.get({'ids[]': vm.venue.categories, limit: 10}).then(function (result) {
                    vm.venue.categories = result;
                });
            }

            vm.categories = [];
            vm.refreshCategory = function (string) {
                if (string !== '') {
                    venueCategoryService.get({name: string, limit: 250}).then(function (result) {
                        vm.categories = result;
                    });
                }
            };

            vm.countries = [];
            vm.states = [];
            vm.cities = [];
            vm.loadState = false;
            vm.loadCity = false;

            vm.refreshCountries = function (string) {
                if (string !== '') {
                    countryService.get().then(function (result) {
                        vm.countries = result;
                    });
                }
            };

            vm.refreshStates = function (string) {
                if (string !== '') {
                    stateService.get({country_id: string}).then(function (result) {
                        vm.states = result;
                    });
                }
                vm.loadState = true;
            };

            vm.refreshCities = function (string) {
                if (string !== '') {
                    cityService.get({state_id: string}).then(function (result) {
                        vm.cities = result;
                    });
                }
                vm.loadCity = true;
            };

            vm.refreshCountries();
            if(vm.venue.id) {
                vm.refreshStates();
                vm.refreshCities();
            }

            //==========================================
            // TinyMCE
            //==========================================

            vm.tinymceOptions = {
                inline: false,
                plugins : [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern",
                ],
                theme : 'modern'
            };

            //==========================================
            // save
            //==========================================
            var save = function () {
                var venue = angular.copy(vm.venue);
                if(typeof vm.city === 'object') {
                    venue.city_id = vm.city.id;
                } else {
                    venue.city_id = vm.city;
                }
                venue.location = venue.location.lat + ',' + venue.location.lng;
                venue.categories = _.map(vm.venue.categories, 'id');

                var deferred = $q.defer();

                var fd = new FormData();
                if(vm.file) {
                    fd.append('attachment',vm.file);
                    fd.append('filename', vm.file.name);
                }
                fd.append("data", angular.toJson(venue));

                if (venue.id !== '') {
                    fd.append('_method', 'PUT');
                    venueService.update(venue.id, fd).then(function (result) {
                        deferred.resolve(result);
                    }, function (result) {
                        deferred.reject(result);
                    });
                } else {
                    venueService.store(fd).then(function (result) {
                        deferred.resolve(result);
                    }, function (result) {
                        deferred.reject(result);
                    });
                }

                return deferred.promise;

            };

            vm.isSaveAndExit = false;
            vm.saveLoading = false;
            vm.save = function () {
                vm.saveLoading = true;
                save().then(function (result) {
                    vm.saveLoading = false;
                    toaster.pop('success', '', $translate.instant('venue.' + (vm.venue.id !== '' ? 'update_success_msg' : 'create_success_msg')));
                    if (vm.isSaveAndExit) {
                        $location.path('/venue');
                    } else if (vm.venue.id === '') {
                        $location.path('/venues/' + result.id + '/edit');
                    } else {
                        vm.venue = result;
                        if (vm.venue.categories.length > 0) {
                            venueCategoryService.get({'ids[]': vm.venue.categories, limit: 10}).then(function (result) {
                                vm.venue.categories = result;
                            });
                        }
                    }
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
            /*vm.deleteLoading = false;
            vm.delete = function () {
                if (vm.post.id !== '') {
                    vm.deleteLoading = true;
                    postService.destroy(vm.post.id).then(function () {
                        toaster.pop('success', '', $translate.instant('post.delete_success_msg'));
                        $location.path('/posts');
                    }, function (result) {
                        deferred.reject(result);
                    });
                }

            };*/
        }
        function ModalInstanceCtrl($scope, $modalInstance, items) {
            $scope.items = items;
            $scope.selected = {
                item: $scope.items[0]
            };

            $scope.ok = function () {
                $modalInstance.close($scope.selected.item);
            };

            $scope.cancel = function () {
                $modalInstance.dismiss('cancel');
            };
        }
})();

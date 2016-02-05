
(function () {
    'use strict';

    angular
        .module('venueModule')
        .controller('VenueFormController', VenueFormController);
        function VenueFormController($scope, venueService, venueCategoryService, countryService, stateService, cityService, messageService, toaster, $translate, venue, $location, Restangular, $q, $log) {
            var vm = this;
            //==========================================
            // Variable
            //==========================================
            vm.venue = (_.isEmpty(venue) || _.isUndefined(venue)) ? venueService.init() : venue;
            console.log(vm.venue);
            if(vm.venue.id) {
                vm.country = venue.city.state.country;
                vm.state = venue.city.state;
                vm.city = venue.city;
            }
            if(!vm.venue.id) {
                vm.place = null;
                vm.map = { 
                    center: { 
                        latitude: vm.venue.longitude || -18.8791902, 
                        longitude: vm.venue.longitude || 47.50790549999999 
                    },
                    options: {
                        scrollwheel: false, 
                        disableDoubleClickZoom: true,
                    },
                    zoom: 15
                };
                vm.autocompleteOptions = {
                    componentRestrictions: { country: 'mg' }
                }
                vm.marker = {
                    id: 0,
                    coords: {
                        latitude: vm.venue.longitude || -18.8791902,
                        longitude: vm.venue.longitude || 47.50790549999999 
                    },
                    options: { draggable: true },
                    events: {
                        dragend: function (marker, eventName, args) {
                            vm.venue.latitude = marker.getPosition().lat();
                            vm.venue.longitude = marker.getPosition().lng();
                        }
                    }
                };
                vm.placeChange = function() {
                    if(typeof vm.place === 'object') {
                        console.log(vm.place);
                        vm.venue.name = vm.place.name;
                        vm.venue.phone = vm.place.international_phone_number || '';
                        vm.venue.longitude = vm.place.geometry.location.lng() || '';
                        vm.venue.latitude = vm.place.geometry.location.lat() || '';
                        vm.venue.website = vm.place.website || '';
                        vm.venue.address = vm.place.address_components[1].short_name || '';
                        vm.marker = {
                            id: 0,
                            coords: {
                                latitude: vm.venue.latitude,
                                longitude: vm.venue.longitude
                            },
                            options: { draggable: true },
                            events: {
                                dragend: function (marker, eventName, args) {
                                    vm.venue.latitude = marker.getPosition().lat();
                                    vm.venue.longitude = marker.getPosition().lng();
                                }
                            }
                        };
                    }
                }
            }
            //==========================================
            // Load Data
            //==========================================
            if (vm.venue.categories.length > 0) {
                console.log('msg');
                venueCategoryService.get({'ids[]': vm.venue.categories}).then(function (result) {
                    vm.venue.categories = result;
                });
            }

            vm.categories = [];
            vm.refreshCategory = function (string) {
                if (string !== '') {
                    venueCategoryService.get({name: string}).then(function (result) {
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
            // Date & Time picker
            //==========================================
            /*vm.open = function ($event) {
                $event.preventDefault();
                $event.stopPropagation();
                vm.opened = true;
            };

            vm.dateOptions = {
                startingDay: 1,
                showWeeks: false,
                initDate: null,
                maxDate: moment().add(3, 'year').format('YYYY-MM-DD')
            };

            vm.form = {
                publishedDate: moment(vm.post.published_at).format('YYYY-MM-DD'),
                publishedTime: vm.post.published_at
            };*/

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
            // Function
            //==========================================
            /*vm.generateSlug = function () {
                vm.generlateSlugLoading = true;
                Restangular.all('slug').post({'slug': vm.post.title}).then(function (result) {
                    vm.generlateSlugLoading = false;
                    vm.post.slug = result.slug;
                });
            };*/

            //==========================================
            // save
            //==========================================
            /*var save = function () {
                var post = angular.copy(vm.post);
                var deferred = $q.defer();

                post.categories = _.pluck(vm.post.categories, 'id');

                post.published_at = moment(vm.form.publishedDate).hour(new Date(vm.form.publishedTime).getHours()).minutes(new Date(vm.form.publishedTime).getMinutes()).tz(angularMomentConfig.timezone).utc().format('YYYY-MM-DD HH:mm:ss');

                if (post.id !== '') {
                    postService.update(post.id, post).then(function (result) {
                        deferred.resolve(result);
                    }, function (result) {
                        deferred.reject(result);
                    });
                } else {
                    postService.store(post).then(function (result) {
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
                    toaster.pop('success', '', $translate.instant('post.' + (vm.post.id !== '' ? 'update_success_msg' : 'create_success_msg')));
                    if (vm.isSaveAndExit) {
                        $location.path('/posts');
                    } else if (vm.post.id === '') {
                        $location.path('/posts/' + result.id + '/edit');
                    } else {
                        vm.post = result;
                    }
                }, function (result) {
                    vm.saveLoading = false;
                    messageService.formError(result);
                });
            };

            vm.saveAndExit = function () {
                vm.isSaveAndExit = true;
                vm.save();
            };*/

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
})();

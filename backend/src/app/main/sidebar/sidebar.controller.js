(function () {
    'use strict';

    angular.module('backend')
        .controller('SidebarController', SidebarController);
    function SidebarController($scope, $location, $timeout, $state, userService) {

        var vm = this;
        vm.menu = [];

        userService.getMe().then(function (result) {
            vm.me = result;
        }).then(function () {

            vm.menu = [{
                label: 'Dashboard',
                iconClasses: 'fa fa-home',
                url: '/admin/',
                enabled: true
            }, {
                label: 'User',
                iconClasses: 'fa fa-users',
                enabled: vm.me.can(['users.index', 'users.store', 'roles.index']),
                children: [
                    {
                        label: 'All Users',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.user-list',
                        enabled: vm.me.can(['users.index'])
                    }, {
                        label: 'Add new',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.user-create',
                        enabled: vm.me.can(['users.store'])
                    }, {
                        label: 'Roles',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.role-list',
                        enabled: vm.me.can(['roles.index'])
                    }
                ]
            }, {
                label: 'Post',
                iconClasses: 'fa fa-tags',
                enabled: vm.me.can(['posts.index', 'posts.store', 'posts.categories.index']),
                children: [
                    {
                        label: 'All Posts',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.post-list',
                        enabled: vm.me.can(['posts.index'])
                    }, {
                        label: 'Add new',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.post-create',
                        enabled: vm.me.can(['posts.store'])
                    }, {
                        label: 'Categories',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.post-category-list',
                        enabled: vm.me.can(['posts.categories.index'])
                    }
                ]
            }, {
                label: 'Localisation',
                iconClasses: 'fa fa-globe',
                enabled: vm.me.can(['country.index', 'state.index', 'city.categories.index']),
                children: [
                    {
                        label: 'Countries',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.country-list',
                        enabled: vm.me.can(['country.index'])
                    }, {
                        label: 'States',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.state-list',
                        enabled: vm.me.can(['state.index'])
                    }, {
                        label: 'City',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.city-list',
                        enabled: vm.me.can(['city.index'])
                    }
                ]
            }, {
                label: 'Media',
                iconClasses: 'fa fa-file-archive-o',
                state: 'main.media-list',
                enabled: vm.me.can(['media.index'])
            }, {
                label: 'Setting',
                iconClasses: 'fa fa-gear',
                state: 'main.setting-form',
                enabled: vm.me.can(['setting.index', 'setting.store', 'setting.clear.cache'])
            }, {
                label: 'Venue',
                iconClasses: 'fa fa-map-marker',
                enabled: vm.me.can(['venues.index', 'venues.store'/*, 'venues.categories.index'*/]),
                children: [
                    {
                        label: 'All Venues',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.venue-list',
                        enabled: vm.me.can(['venues.index'])
                    }, {
                        label: 'Add new',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.venue-create',
                        enabled: vm.me.can(['venues.store'])
                    }/*, {
                        label: 'Categories',
                        iconClasses: 'fa fa-circle-o',
                        state: 'main.post-category-list',
                        enabled: vm.me.can(['posts.categories.index'])
                    }*/
                ]
            }];

            var setParent = function (children, parent) {
                angular.forEach(children, function (child) {
                    child.parent = parent;
                    if (child.children !== undefined) {
                        setParent(child.children, child);
                    }
                });
            };


            vm.findItemByUrl = function (children, url) {
                for (var i = 0, length = children.length; i < length; i++) {
                    if ($state.is(children[i].state)) {
                        return children[i];
                    }
                    if (children[i].children !== undefined) {
                        var item = vm.findItemByUrl(children[i].children, url);
                        if (item) {
                            return item;
                        }
                    }
                }
            };

            setParent(vm.menu, null);

            vm.openItems = [];
            vm.selectedItems = [];
            vm.selectedFromNavMenu = false;

            vm.select = function (item) {
                // close open nodes
                if (item.open) {
                    item.open = false;
                    return;
                }
                for (var i = vm.openItems.length - 1; i >= 0; i--) {
                    vm.openItems[i].open = false;
                }

                vm.openItems = [];
                var parentRef = item;
                while (parentRef !== null) {
                    parentRef.open = true;
                    vm.openItems.push(parentRef);
                    parentRef = parentRef.parent;
                }

                // handle leaf nodes
                if (!item.children || (item.children && item.children.length < 1)) {
                    vm.selectedFromNavMenu = true;
                    for (var j = vm.selectedItems.length - 1; j >= 0; j--) {
                        vm.selectedItems[j].selected = false;
                    }

                    vm.selectedItems = [];
                    parentRef = item;
                    while (parentRef !== null) {
                        parentRef.selected = true;
                        vm.selectedItems.push(parentRef);
                        parentRef = parentRef.parent;
                    }
                }

            };

            $scope.$watch(function () {
                return $location.path();
            }, function (newVal, oldVal) {
                if (vm.selectedFromNavMenu === false) {
                    var item = vm.findItemByUrl(vm.menu, newVal);
                    if (item) {
                        $timeout(function () {
                            vm.select(item);
                        });
                    }
                }
                vm.selectedFromNavMenu = false;
            });

        });


    }
})();

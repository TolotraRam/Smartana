<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UserAndRoleTableSeeder extends Seeder
{

    public function run()
    {
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'owner']);
        $administratorRole = Role::create(['name' => 'administrator', 'display_name' => 'administator']);
        $redactorRole = Role::create(['name' => 'redactor', 'display_name' => 'redactor']);
        $clientRole = Role::create(['name' => 'client', 'display_name' => 'client']);



        $allPermissions = [];
        $redactorPermission = [];

        $adminRolePermission = Permission::create(['name' => 'auth.backend', 'display_name' => 'Login to backend']);
        array_push($allPermissions, $adminRolePermission->id);
        array_push($redactorPermission, $adminRolePermission->id);

        $listRolePermission = Permission::create(['name' => 'roles.index', 'display_name' => 'List Roles']);
        $createRolePermission = Permission::create(['name' => 'roles.store', 'display_name' => 'Create Roles']);
        $editRolePermission = Permission::create(['name' => 'roles.update', 'display_name' => 'Edit Roles']);
        $deleteRolePermission = Permission::create(['name' => 'roles.destroy', 'display_name' => 'Delete Roles']);
        array_push($allPermissions, $listRolePermission->id, $createRolePermission->id, $editRolePermission->id, $listRolePermission->id, $deleteRolePermission->id);

        $listUserPermission = Permission::create(['name' => 'users.index', 'display_name' => 'List Users']);
        $createUserPermission = Permission::create(['name' => 'users.store', 'display_name' => 'Create Users']);
        $editUserPermission = Permission::create(['name' => 'users.update', 'display_name' => 'Edit Users']);
        $deleteUserPermission = Permission::create(['name' => 'users.destroy', 'display_name' => 'Delete Users']);
        array_push($allPermissions, $listUserPermission->id, $createUserPermission->id, $editUserPermission->id, $listUserPermission->id, $deleteUserPermission->id);


        $listPostPermission = Permission::create(['name' => 'posts.index', 'display_name' => 'List Posts']);
        $createPostPermission = Permission::create(['name' => 'posts.store', 'display_name' => 'Create Posts']);
        $editPostPermission = Permission::create(['name' => 'posts.update', 'display_name' => 'Edit Posts']);
        $deletePostPermission = Permission::create(['name' => 'posts.destroy', 'display_name' => 'Delete Posts']);
        array_push($allPermissions, $listPostPermission->id, $createPostPermission->id, $editPostPermission->id, $listPostPermission->id, $deletePostPermission->id);
        array_push($redactorPermission, $listPostPermission->id, $createPostPermission->id, $editPostPermission->id, $listPostPermission->id, $deletePostPermission->id);

        $listPostCategoryPermission = Permission::create(['name' => 'posts.categories.index', 'display_name' => 'List Posts Categories']);
        $createPostCategoryPermission = Permission::create(['name' => 'posts.categories.store', 'display_name' => 'Create Posts Categories']);
        $editPostCategoryPermission = Permission::create(['name' => 'posts.categories.update', 'display_name' => 'Edit Posts Categories']);
        $deletePostCategoryPermission = Permission::create(['name' => 'posts.categories.destroy', 'display_name' => 'Delete Posts Categories']);
        array_push($allPermissions, $listPostCategoryPermission->id, $createPostCategoryPermission->id, $editPostCategoryPermission->id, $listPostCategoryPermission->id, $deletePostCategoryPermission->id);
        array_push($redactorPermission, $listPostCategoryPermission->id, $createPostCategoryPermission->id, $editPostCategoryPermission->id, $listPostCategoryPermission->id, $deletePostCategoryPermission->id);

        $listMediaPermission = Permission::create(['name' => 'media.index', 'display_name' => 'List Media']);
        $createMediaPermission = Permission::create(['name' => 'media.store', 'display_name' => 'Create Media']);
        $editMediaPermission = Permission::create(['name' => 'media.update', 'display_name' => 'Edit Media']);
        $deleteMediaPermission = Permission::create(['name' => 'media.destroy', 'display_name' => 'Delete Media']);
        array_push($allPermissions, $listMediaPermission->id, $createMediaPermission->id, $editMediaPermission->id, $listMediaPermission->id, $deleteMediaPermission->id);
        array_push($redactorPermission, $listMediaPermission->id, $createMediaPermission->id, $editMediaPermission->id, $listMediaPermission->id, $deleteMediaPermission->id);

        $listMediaCategoryPermission = Permission::create(['name' => 'media.categories.index', 'display_name' => 'List Media Categories']);
        $createMediaCategoryPermission = Permission::create(['name' => 'media.categories.store', 'display_name' => 'Create Media Categories']);
        $editMediaCategoryPermission = Permission::create(['name' => 'media.categories.update', 'display_name' => 'Edit Media Categories']);
        $deleteMediaCategoryPermission = Permission::create(['name' => 'media.categories.destroy', 'display_name' => 'Delete Media Categories']);
        array_push($allPermissions, $listMediaCategoryPermission->id, $createMediaCategoryPermission->id, $editMediaCategoryPermission->id, $listMediaCategoryPermission->id, $deleteMediaCategoryPermission->id);
        array_push($redactorPermission, $listMediaCategoryPermission->id, $createMediaCategoryPermission->id, $editMediaCategoryPermission->id, $listMediaCategoryPermission->id, $deleteMediaCategoryPermission->id);

        $listCountryPermission = Permission::create(['name' => 'country.index', 'display_name' => 'List Countries']);
        $createCountryPermission = Permission::create(['name' => 'country.store', 'display_name' => 'Create Country']);
        $editCountryPermission = Permission::create(['name' => 'country.update', 'display_name' => 'Edit Country']);
        $deleteCountryPermission = Permission::create(['name' => 'country.destroy', 'display_name' => 'Delete Country']);
        array_push($allPermissions, $listCountryPermission->id, $createCountryPermission->id, $editCountryPermission->id, $listCountryPermission->id, $deleteCountryPermission->id);

        $listStatePermission = Permission::create(['name' => 'state.index', 'display_name' => 'List States']);
        $createStatePermission = Permission::create(['name' => 'state.store', 'display_name' => 'Create State']);
        $editStatePermission = Permission::create(['name' => 'state.update', 'display_name' => 'Edit State']);
        $deleteStatePermission = Permission::create(['name' => 'state.destroy', 'display_name' => 'Delete State']);
        array_push($allPermissions, $listStatePermission->id, $createStatePermission->id, $editStatePermission->id, $listStatePermission->id, $deleteStatePermission->id);

        $listCityPermission = Permission::create(['name' => 'city.index', 'display_name' => 'List Cities']);
        $createCityPermission = Permission::create(['name' => 'city.store', 'display_name' => 'Create City']);
        $editCityPermission = Permission::create(['name' => 'city.update', 'display_name' => 'Edit City']);
        $deleteCityPermission = Permission::create(['name' => 'city.destroy', 'display_name' => 'Delete City']);
        array_push($allPermissions, $listCityPermission->id, $createCityPermission->id, $editCityPermission->id, $listCityPermission->id, $deleteCityPermission->id);

        $ownerRole->perms()->sync($allPermissions);
        $administratorRole->perms()->sync($allPermissions);
        $redactorRole->perms()->sync($redactorPermission);

        $owner = User::create(['firstname' => 'Mark', 'email' => 'admin@emcoo.com', 'password' => 'adminmark', 'last_login' => date('Y-m-d H:i:s'), 'city_id' => 1]);
        $owner->attachRole($ownerRole);
        $administrator = User::create(['firstname' => 'Tolotra', 'lastname' => 'Ram', 'email' => 'admin@smartana.com', 'password' => '123456', 'last_login' => date('Y-m-d H:i:s'), 'city_id' => 1]);
        $administrator->attachRole($administratorRole);
        $redactor = User::create(['firstname' => 'Redactor', 'lastname' => 'Red', 'email' => 'redactor@smartana.com', 'password' => '123456', 'last_login' => date('Y-m-d H:i:s'), 'city_id' => 1]);
        $redactor->attachRole($redactorRole);
        $client1 = User::create(['firstname' => 'John', 'lastname' => 'Lennon', 'email' => 'john@gmail.com', 'password' => '123456', 'last_login' => date('Y-m-d H:i:s'), 'city_id' => 1]);
        $client1->attachRole($clientRole);
        $client2 = User::create(['firstname' => 'Martha', 'lastname' => 'Smith', 'email' => 'martha@gmail.com', 'password' => '123456', 'last_login' => date('Y-m-d H:i:s'), 'city_id' => 1]);
        $client2->attachRole($clientRole);
        $client3 = User::create(['firstname' => 'Martin', 'lastname' => 'Solveig', 'email' => 'martin@gmail.com', 'password' => '123456', 'last_login' => date('Y-m-d H:i:s'), 'city_id' => 1]);
        $client3->attachRole($clientRole);

    }

}
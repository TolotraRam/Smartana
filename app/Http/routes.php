<?php
if (App::environment('local'))
{
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 0');
}

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::post('slug', 'Api\HelperController@slug');


        //AUTH =================================

        Route::post('auth/login', ['uses' => 'Api\Backend\AuthController@postLogin', 'as' => 'auth.login']);
        Route::post('auth/logout', ['uses' => 'Api\Backend\AuthController@postLogout', 'as' => 'auth.logout']);
        Route::post('auth/refresh-token', ['uses' => 'Api\Backend\AuthController@postRefreshToken', 'as' => 'auth.refresh']);

        Route::group(['middleware' => ['auth.user']], function () {
            Route::get('me', 'Api\Backend\UserController@index');

            //Country =================================
            Route::get('country', ['uses' => 'Api\CountryController@index', 'as' => 'country.index', 'middleware' => 'permission:country.index']);
            Route::get('country/{id}', ['uses' => 'Api\CountryController@show', 'as' => 'country.show', 'middleware' => 'permission:country.index']);
            Route::post('country', ['uses' => 'Api\CountryController@store', 'as' => 'country.store', 'middleware' => 'permission:country.store']);
            Route::put('country/{id}', ['uses' => 'Api\CountryController@update', 'as' => 'country.update', 'middleware' => 'permission:country.update']);
            Route::delete('country/{id}', ['uses' => 'Api\CountryController@destroy', 'as' => 'country.destroy', 'middleware' => 'permission:country.destroy']);

            //State =================================
            Route::get('state', ['uses' => 'Api\StateController@index', 'as' => 'state.index', 'middleware' => 'permission:state.index']);
            Route::get('state/{id}', ['uses' => 'Api\StateController@show', 'as' => 'state.show', 'middleware' => 'permission:state.index']);
            Route::post('state', ['uses' => 'Api\StateController@store', 'as' => 'state.store', 'middleware' => 'permission:state.store']);
            Route::put('state/{id}', ['uses' => 'Api\StateController@update', 'as' => 'state.update', 'middleware' => 'permission:state.update']);
            Route::delete('state/{id}', ['uses' => 'Api\StateController@destroy', 'as' => 'state.destroy', 'middleware' => 'permission:state.destroy']);

            //City =================================
            Route::get('city', ['uses' => 'Api\CityController@index', 'as' => 'city.index', 'middleware' => 'permission:city.index']);
            Route::get('city/{id}', ['uses' => 'Api\CityController@show', 'as' => 'city.show', 'middleware' => 'permission:city.index']);
            Route::post('city', ['uses' => 'Api\CityController@store', 'as' => 'city.store', 'middleware' => 'permission:city.store']);
            Route::put('city/{id}', ['uses' => 'Api\CityController@update', 'as' => 'city.update', 'middleware' => 'permission:city.update']);
            Route::delete('city/{id}', ['uses' => 'Api\CityController@destroy', 'as' => 'city.destroy', 'middleware' => 'permission:city.destroy']);

            //Media Categories =================================
            Route::get('media/categories', ['uses' => 'Api\MediaCategoryController@index', 'as' => 'media.categories.index', 'middleware' => 'permission:media.categories.index']);
            Route::get('media/categories/{id}', ['uses' => 'Api\MediaCategoryController@show', 'as' => 'media.categories.show', 'middleware' => 'permission:media.categories.index']);
            Route::post('media/categories', ['uses' => 'Api\MediaCategoryController@store', 'as' => 'media.categories.store', 'middleware' => 'permission:media.categories.store']);
            Route::put('media/categories/{id}', ['uses' => 'Api\MediaCategoryController@update', 'as' => 'media.categories.update', 'middleware' => 'permission:media.categories.update']);
            Route::delete('media/categories/{id}', ['uses' => 'Api\MediaCategoryController@destroy', 'as' => 'media.categories.destroy', 'middleware' => 'permission:media.categories.destroy']);

            //Media =================================
            Route::get('media', ['uses' => 'Api\MediaController@index', 'as' => 'media.index', 'middleware' => 'permission:media.index']);
            Route::get('media/{id}', ['uses' => 'Api\MediaController@show', 'as' => 'media.show', 'middleware' => 'permission:media.index']);
            Route::post('media', ['uses' => 'Api\MediaController@store', 'as' => 'media.store', 'middleware' => 'permission:media.store']);
            Route::put('media/{id}', ['uses' => 'Api\MediaController@update', 'as' => 'media.update', 'middleware' => 'permission:media.update']);
            Route::delete('media/{id}', ['uses' => 'Api\MediaController@destroy', 'as' => 'media.destroy', 'middleware' => 'permission:media.destroy']);

            //Post Categories =================================
            Route::get('posts/categories', ['uses' => 'Api\PostCategoryController@index', 'as' => 'posts.categories.index', 'middleware' => 'permission:posts.categories.index']);
            Route::get('posts/categories/{id}', ['uses' => 'Api\PostCategoryController@show', 'as' => 'posts.categories.show', 'middleware' => 'permission:posts.categories.index']);
            Route::post('posts/categories/', ['uses' => 'Api\PostCategoryController@store', 'as' => 'posts.categories.store', 'middleware' => 'permission:posts.categories.store']);
            Route::put('posts/categories/{id}', ['uses' => 'Api\PostCategoryController@update', 'as' => 'posts.categories.update', 'middleware' => 'permission:posts.categories.update']);
            Route::delete('posts/categories/{id}', ['uses' => 'Api\PostCategoryController@destroy', 'as' => 'posts.categories.destroy', 'middleware' => 'permission:posts.categories.destroy']);
            Route::post('posts/categories/{id}/move', ['uses' => 'Api\PostCategoryController@move', 'as' => 'posts.categories.move', 'middleware' => 'permission:posts.categories.update']);

            //Posts =================================
            Route::get('posts', ['uses' => 'Api\PostController@index', 'as' => 'posts.index', 'middleware' => 'permission:posts.index']);
            Route::get('posts/{id}', ['uses' => 'Api\PostController@show', 'as' => 'posts.show', 'middleware' => 'permission:posts.index']);
            Route::post('posts', ['uses' => 'Api\PostController@store', 'as' => 'posts.store', 'middleware' => 'permission:posts.store']);
            Route::put('posts/{id}', ['uses' => 'Api\PostController@update', 'as' => 'posts.update', 'middleware' => 'permission:posts.update']);
            Route::delete('posts/{id}', ['uses' => 'Api\PostController@destroy', 'as' => 'posts.destroy', 'middleware' => 'permission:posts.destroy']);

            //Venue Categories =================================
            Route::get('venues/categories', ['uses' => 'Api\VenueCategoryController@index', 'as' => 'venues.categories.index', 'middleware' => 'permission:venues.categories.index']);
            Route::get('venues/categories/{id}', ['uses' => 'Api\VenueCategoryController@show', 'as' => 'venues.categories.show', 'middleware' => 'permission:venues.categories.index']);
            Route::post('venues/categories', ['uses' => 'Api\VenueCategoryController@store', 'as' => 'venues.categories.store', 'middleware' => 'permission:venues.categories.store']);
            Route::put('venues/categories/{id}', ['uses' => 'Api\VenueCategoryController@update', 'as' => 'venues.categories.update', 'middleware' => 'permission:venues.categories.update']);
            Route::delete('venues/categories/{id}', ['uses' => 'Api\VenueCategoryController@destroy', 'as' => 'venues.categories.destroy', 'middleware' => 'permission:venues.categories.destroy']);

            //Venue Galleries =================================
            Route::get('venues/galleries', ['uses' => 'Api\VenueGalleryController@index', 'as' => 'venues.galleries.index', 'middleware' => 'permission:venues.galleries.index']);
            Route::get('venues/galleries/{id}', ['uses' => 'Api\VenueGalleryController@show', 'as' => 'venues.galleries.show', 'middleware' => 'permission:venues.galleries.index']);
            Route::post('venues/galleries', ['uses' => 'Api\VenueGalleryController@store', 'as' => 'venues.galleries.store', 'middleware' => 'permission:venues.galleries.store']);
            Route::put('venues/galleries/{id}', ['uses' => 'Api\VenueGalleryController@update', 'as' => 'venues.galleries.update', 'middleware' => 'permission:venues.galleries.update']);
            Route::delete('venues/galleries/{id}', ['uses' => 'Api\VenueGalleryController@destroy', 'as' => 'venues.galleries.destroy', 'middleware' => 'permission:venues.galleries.destroy']);

            //Venues =================================
            Route::get('venues', ['uses' => 'Api\VenueController@index', 'as' => 'venues.index', 'middleware' => 'permission:venues.index']);
            Route::get('venues/{id}', ['uses' => 'Api\VenueController@show', 'as' => 'venues.show', 'middleware' => 'permission:venues.index']);
            Route::post('venues', ['uses' => 'Api\VenueController@store', 'as' => 'venues.store', 'middleware' => 'permission:venues.store']);
            Route::put('venues/{id}', ['uses' => 'Api\VenueController@update', 'as' => 'venues.update', 'middleware' => 'permission:venues.update']);
            Route::delete('venues/{id}', ['uses' => 'Api\VenueController@destroy', 'as' => 'venues.destroy', 'middleware' => 'permission:venues.destroy']);

            //Users =================================
            Route::get('users', ['uses' => 'Api\UserController@index', 'as' => 'users.index', 'middleware' => 'permission:users.index']);
            Route::get('users/{id}', ['uses' => 'Api\UserController@show', 'as' => 'users.show', 'middleware' => 'permission:users.index']);
            Route::post('users', ['uses' => 'Api\UserController@store', 'as' => 'users.store', 'middleware' => 'permission:users.store']);
            Route::put('users/{id}', ['uses' => 'Api\UserController@update', 'as' => 'users.update', 'middleware' => 'permission:users.update']);
            Route::delete('users/{id}', ['uses' => 'Api\UserController@destroy', 'as' => 'users.destroy', 'middleware' => 'permission:users.destroy']);

            //Roles =================================
            Route::get('roles', ['uses' => 'Api\RoleController@index', 'as' => 'roles.index', 'middleware' => 'permission:roles.index']);
            Route::get('roles/{id}', ['uses' => 'Api\RoleController@show', 'as' => 'roles.show', 'middleware' => 'permission:roles.index']);
            Route::post('roles', ['uses' => 'Api\RoleController@store', 'as' => 'roles.store', 'middleware' => 'permission:roles.store']);
            Route::put('roles/{id}', ['uses' => 'Api\RoleController@update', 'as' => 'roles.update', 'middleware' => 'permission:roles.update']);
            Route::delete('roles/{id}', ['uses' => 'Api\RoleController@destroy', 'as' => 'roles.destroy', 'middleware' => 'permission:roles.destroy']);

            //Settings =================================
            Route::get('setting', ['uses' => 'Api\SettingController@index', 'as' => 'setting.index', 'middleware' => 'permission:setting.index']);
            Route::put('setting', ['uses' => 'Api\SettingController@store', 'as' => 'setting.store', 'middleware' => 'permission:setting.store']);
            Route::get('setting/cache/clear', ['uses' => 'Api\SettingController@ClearCache', 'as' => 'setting.clear.cache', 'middleware' => 'permission:setting.clear.cache']);
            Route::get('setting/{name}', 'Api\SettingController@findByName');

            //Permissions =================================
            Route::get('permissions', ['uses' => 'Api\PermissionController@index', 'as' => 'permissions.index']);
            Route::get('permissions/{id}', ['uses' => 'Api\PermissionController@show', 'as' => 'permissions.show']);

            //Upload =================================
            Route::put('upload/{id}', ['uses' => 'Api\AvatarController@update', 'as' => 'avatar.update']);
        });
    });

    Route::group(['middleware' => ['before' => 'csrf']], function () {
        // Get media
        Route::get('/media/{type}/{yearAndMonth}/{day}/{filename}', 'Api\MediaController@get');
        // Get avatar
        Route::get('/media/{type}/{filename}', 'Api\UserController@get');
        // Get venue category image
        Route::get('/media/category/{type}/{filename}', 'Api\VenueCategoryController@get');
        // Get venue image
        //Route::get('/media/{type}/{filename}', 'Api\VenueController@get');

        Route::get('setting/{name}', 'Api\SettingController@findByName');

        Route::group(['middleware' => 'App\Http\Middleware\ThrottleMiddleware:600,5', 'prefix' => ''], function () {
        });
    });
});


Route::group(['prefix' => 'backend'], function () {
    Route::any('{path?}', function () {
        View::addExtension('html', 'php');
        View::addNamespace('backendTheme', public_path().'/assets-backend');

        return view::make('backendTheme::index');
    })->where('path', '.+');
});

Route::any('{path?}', function () {
    View::addExtension('html', 'php');
    View::addNamespace('frontendTheme', public_path());

    return view::make('frontendTheme::index');
})->where('path', '.+');

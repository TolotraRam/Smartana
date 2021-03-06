<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Guard;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Auth;

class MultiAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Auth::extend('userEloquent', function($app) {
            // you can use Config::get() to retrieve the model class name from config file
            $myProvider = new EloquentUserProvider($app['hash'], '\App\Models\User');

            return new Guard($myProvider, $app['session.store']);
        });
        $this->app->singleton('auth.driver_user', function($app) {
            return Auth::driver('userEloquent');
        });
    }
}

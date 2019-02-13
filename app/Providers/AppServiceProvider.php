<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        //Schema::defaultStringLength(191);
        
        // Count less than balance
        Validator::extend('count_less', function($attribute, $value, $parameters, $validator) {
            if($value > $parameters){
                return true;
            }
                return false;
        });
        
        //Reject less than current
        Validator::extend('input_less', function($attribute, $value, $parameters, $validator) {
            if($value > $parameters){
                return true;
            }
                return false;
        }); 
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

<?php

namespace Armincms\Store;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider; 
use Laravel\Nova\Nova as LaravelNova; 

class ServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{ 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->configureWebComponents();

        $this->app->resolving('conversion', function($manager) {
            $manager->extend('logo', function() {
                return new Conversions\LogoConversion;
            });
            $manager->extend('product', function() {
                return new Conversions\ProductConversion;
            });
        });

        LaravelNova::resources([
            Nova\Brand::class,
            Nova\Carrier::class,
            Nova\Product::class,
            Nova\Feature::class,
            Nova\Category::class,
            Nova\Attribute::class,
            Nova\Combination::class,
            Nova\FeatureValue::class,
            Nova\AttributeValue::class,
        ]);
    }

    public function configureWebComponents()
    { 
        \Site::push('store', function($store) {
            $store->directory('store');
  
            $store->pushComponent(new Components\Product); 
            $store->pushComponent(new Components\Category); 
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            \Illuminate\Console\Events\ArtisanStarting::class,
            \Laravel\Nova\Events\ServingNova::class,
            \Core\HttpSite\Events\ServingFront::class,
        ];
    }
}

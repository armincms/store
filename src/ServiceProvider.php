<?php

namespace Armincms\Store;

use Illuminate\Support\Facades\Gate;
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
        $this->registerPolicies(); 
        $this->registerCart(); 

        $this->app->booted(function() {
            $this->routes();
        });

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
            Nova\AttributeGroup::class,
        ]);

        LaravelNova::script('armincms-store-scrips', __DIR__.'/../dist/js/field.js');
    }

    public function configureWebComponents()
    { 
        \Site::push('store', function($store) {
            $store->directory('store');
  
            $store->pushComponent(new Components\Cart); 
            $store->pushComponent(new Components\Product); 
            $store->pushComponent(new Components\Category); 
        });
    }

    public function registerPolicies()
    { 
        Gate::policy(Models\StoreBrand::class, Policies\Policy::class);
        Gate::policy(Models\StoreFeature::class, Policies\Policy::class);
        Gate::policy(Models\StoreProduct::class, Policies\Policy::class);
        Gate::policy(Models\StoreCarrier::class, Policies\Policy::class);
        Gate::policy(Models\StoreAttribute::class, Policies\Policy::class); 
        Gate::policy(Models\StoreCombination::class, Policies\Policy::class); 
        Gate::policy(Models\StoreFeatureValue::class, Policies\Policy::class);
        Gate::policy(Models\StoreAttributeGroup::class, Policies\Policy::class);
    }

    public function registerCart()
    {
        $this->app->bind('store.cart', function() {
            return new Cart;
        });

        \Helper::registerAlias([
            'ShoppingCart' => Facades\ShoppingCart::class, 
        ]); 
    }

    public function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        \Route::prefix('store/cart')
            ->middleware(['web'])
            ->namespace('Armincms\Store\Http\Controllers')
            ->name('store.cart.')
            ->group(__DIR__.'/../routes/web.php'); 

        app('routes')->refreshNameLookups();
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
            'store.cart',
        ];
    }
}

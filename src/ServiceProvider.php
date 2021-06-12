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
        $this->registerResources(); 
        $this->registerPolicies(); 
        $this->registeLocates();  
        $this->registerCart();  
        $this->routes(); 

        $this->app->resolving('conversion', function($manager) {
            $manager->extend('logo', function() {
                return new Conversions\LogoConversion;
            });
            $manager->extend('product', function() {
                return new Conversions\ProductConversion;
            });
        });

        LaravelNova::script('armincms-store-scrips', __DIR__.'/../dist/js/field.js');
    }

    public function configureWebComponents()
    { 
        \Site::push('store', function($store) {
            $store->directory('store');
  
            $store->pushComponent(new Components\Cart); 
            $store->pushComponent(new Components\Product); 
            $store->pushComponent(new Components\Category); 
            $store->pushComponent(new Components\Shipping); 
            $store->pushComponent(new Components\Checkout); 
            $store->pushComponent(new Components\Invoice); 
        });

        \Site::push('profile', function($store) {
            $store->pushComponent(new Components\Address);    
        });
    }

    public function registerResources()
    { 
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

    public function registeLocates()
    {    
        \Config::set('module.locatables.store', [
            'title' => 'Store', 
            'name'  => 'store',
            'items' => [Locate::class, 'moduleLocales']
        ]);    

        \Config::set('menu.menuables.store', [
            'title' => 'Product Category',
            'callback' => [Locate::class, 'categoryLocates'],
        ]);   
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

        \Route::middleware(['web'])
            ->namespace('Armincms\Store\Http\Controllers')
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
            \Core\Crud\Events\CoreServing::class,
            'store.cart',
        ];
    }
}
